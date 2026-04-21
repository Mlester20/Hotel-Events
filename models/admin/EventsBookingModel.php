<?php
require_once __DIR__ . '/../BaseModel.php';


    class EventsBookingModel extends BaseModel {
        protected $events = 'events';
        protected $event_bookings = 'event_bookings';
        protected $users = 'users';

        public function index() {
            $query = "
                SELECT 
                    eb.id,
                    eb.event_booking_id,
                    eb.booking_date_start,
                    eb.booking_date_end,
                    eb.start_time,
                    eb.number_of_guests,
                    eb.total_price,
                    eb.status,
                    eb.payment_status,
                    eb.special_requests,
                    eb.created_at,
                    eb.updated_at,
                    e.title AS event_title,
                    e.description AS event_description,
                    e.location AS event_location,
                    e.capacity AS event_capacity,
                    e.price AS event_price,
                    u.full_name AS user_name,
                    u.email AS user_email
                FROM {$this->event_bookings} eb
                INNER JOIN {$this->events} e ON eb.event_id = e.id
                INNER JOIN {$this->users} u ON eb.user_id = u.user_id
                ORDER BY eb.created_at DESC
            ";

            $stmt = $this->con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            $event_bookings = [];

            while ($row = $result->fetch_assoc()) {
                $event_bookings[] = $row;
            }

            return $event_bookings;
        }
    }
    
?>