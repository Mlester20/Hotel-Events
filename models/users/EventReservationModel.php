<?php
require_once __DIR__ . '/../BaseModel.php'; 

    class EventReservationModel extends BaseModel {
        protected $table = 'event_bookings';
        protected $events_table = 'events';

        public function index($user_id) {
            $query = "SELECT b.*, e.title, e.location 
                    FROM {$this->table} b
                    INNER JOIN {$this->events_table} e ON b.event_id = e.id
                    WHERE b.user_id = ? 
                    ORDER BY b.created_at DESC";
            
            $stmt = $this->con->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getAllEvents() {
            try {
                $query = "SELECT * FROM {$this->events_table} ORDER BY created_at DESC";
                $stmt = $this->con->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result->fetch_all(MYSQLI_ASSOC);
            } catch (Exception $e) {
                error_log("Error fetching events: " . $e->getMessage());
                return [];
            }
        }

        public function getEventById($event_id) {
            $query = "SELECT * FROM {$this->events_table} WHERE id = ?";
            $stmt = $this->con->prepare($query);
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        public function create($data) {
            $query = "INSERT INTO {$this->table} 
                    (event_booking_id, user_id, event_id, booking_date_start, booking_date_end, start_time, end_time, number_of_guests, total_price, special_requests, status, payment_status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->con->prepare($query);
            
            // Set default status and payment_status
            $status = 'pending';
            $payment_status = 'unpaid';
            
            // Prepare end_time - use start_time + 3 hours if not provided
            $end_time = isset($data['end_time']) && !empty($data['end_time']) ? $data['end_time'] : null;
            
            // "siisssissss" -> string, int, int, string, string, string, string, int, double, string, string, string
            $stmt->bind_param(
                "siissssissss", 
                $data['event_booking_id'],
                $data['user_id'],
                $data['event_id'],
                $data['booking_date_start'],
                $data['booking_date_end'],
                $data['start_time'],
                $end_time,
                $data['number_of_guests'],
                $data['total_price'],
                $data['special_requests'],
                $status,
                $payment_status
            );

            return $stmt->execute();
        }

        public function findDateConflicts($event_id, $booking_date_start, $booking_date_end) {
            // Check for any overlapping bookings
            $query = "SELECT id, event_booking_id, user_id, booking_date_start, booking_date_end, status 
                    FROM {$this->table} 
                    WHERE event_id = ? 
                    AND status IN ('pending', 'confirmed', 'completed')
                    AND booking_date_start <= ?
                    AND booking_date_end >= ?
                    ORDER BY booking_date_start ASC";
            
            $stmt = $this->con->prepare($query);
            $stmt->bind_param("iss", $event_id, $booking_date_end, $booking_date_start);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

    }

?>