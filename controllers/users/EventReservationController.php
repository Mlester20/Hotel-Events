<?php
session_start();
require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../../models/users/EventReservationModel.php';
require_once __DIR__ . '/../../helpers/bookings_id.php';

class EventReservationController {
    private $model;
    private $con;

    public function __construct($con) {
        $this->con = $con;
        $this->model = new EventReservationModel($con);
    }

    public function create() {
        try {
            // Check if user is authenticated
            if (!isset($_SESSION['user_id'])) {
                return [
                    'success' => false,
                    'message' => 'Please login to make a reservation'
                ];
            }

            // Get POST data
            $event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
            $booking_date_start = isset($_POST['booking_date_start']) ? $_POST['booking_date_start'] : '';
            $booking_date_end = isset($_POST['booking_date_end']) ? $_POST['booking_date_end'] : '';
            $start_time = isset($_POST['start_time']) ? $_POST['start_time'] . ':00' : '14:00:00';
            $end_time = isset($_POST['end_time']) && !empty($_POST['end_time']) ? $_POST['end_time'] . ':00' : null;
            $number_of_guests = isset($_POST['number_of_guests']) ? (int)$_POST['number_of_guests'] : 0;
            $special_requests = isset($_POST['special_requests']) ? $_POST['special_requests'] : '';

            // Validate input
            if (empty($event_id) || empty($booking_date_start) || empty($number_of_guests)) {
                return [
                    'success' => false,
                    'message' => 'Please fill in all required fields'
                ];
            }

            // Fetch event details to get price
            $event = $this->model->getEventById($event_id);
            if (!$event) {
                return [
                    'success' => false,
                    'message' => 'Event not found'
                ];
            }

            // Validate number of guests
            if ($number_of_guests > $event['capacity']) {
                return [
                    'success' => false,
                    'message' => 'Number of guests exceeds event capacity (' . $event['capacity'] . ' max)'
                ];
            }

            // Calculate total price
            $total_price = $event['price']; // Base price for the event

            // Generate booking ID
            $event_booking_id = GenerateBookingID::generate();

            // Prepare data for insertion
            $data = [
                'event_booking_id' => $event_booking_id,
                'user_id' => (int)$_SESSION['user_id'],
                'event_id' => $event_id,
                'booking_date_start' => $booking_date_start,
                'booking_date_end' => !empty($booking_date_end) ? $booking_date_end : $booking_date_start,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'number_of_guests' => $number_of_guests,
                'total_price' => $total_price,
                'special_requests' => $special_requests
            ];

            // Create the reservation
            if ($this->model->create($data)) {
                return [
                    'success' => true,
                    'message' => 'Event reservation created successfully!',
                    'booking_id' => $event_booking_id,
                    'total_price' => $total_price
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to create reservation. Please try again.'
                ];
            }

        } catch (Exception $e) {
            error_log("Event Reservation Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }

    public function getAll($user_id) {
        return $this->model->index($user_id);
    }

    public function getAllEvents() {
        return $this->model->getAllEvents();
    }

    public function checkDateConflict($event_id, $booking_date_start, $booking_date_end = null) {
        try {
            // If end date not provided, use start date
            if (empty($booking_date_end)) {
                $booking_date_end = $booking_date_start;
            }

            // Check for conflicts
            $conflicts = $this->model->findDateConflicts($event_id, $booking_date_start, $booking_date_end);
            
            if (!empty($conflicts)) {
                return [
                    'success' => false,
                    'hasConflict' => true,
                    'message' => 'This event is already booked on some of these dates',
                    'conflicts' => $conflicts,
                    'conflictDates' => array_map(function($c) {
                        return $c['booking_date_start'];
                    }, $conflicts)
                ];
            } else {
                return [
                    'success' => true,
                    'hasConflict' => false,
                    'message' => 'Dates are available!',
                    'conflicts' => []
                ];
            }
        } catch (Exception $e) {
            error_log("Date Conflict Check Error: " . $e->getMessage());
            return [
                'success' => false,
                'hasConflict' => false,
                'message' => 'Error checking availability'
            ];
        }
    }
}

?>