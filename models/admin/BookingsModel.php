<?php 
require_once __DIR__ . '/../BaseModel.php';

    class BookingsModel extends BaseModel{
        protected $bookings = 'bookings';
        protected $users = 'users';
        protected $rooms = 'rooms';
        protected $room_types = 'room_types';

        public function index(){
            try {
                $query = "
                    SELECT 
                        b.booking_id,
                        b.check_in_date,
                        b.check_out_date,
                        b.total_price,
                        b.status as booking_status,
                        b.payment_status,
                        b.special_requests,
                        b.created_at,
                        u.full_name as guess_name,
                        u.email as guest_email,
                        r.room_number as room_number,
                        rt.title as room_type
                    FROM {$this->bookings} b
                    LEFT JOIN {$this->users} u ON b.user_id = u.user_id
                    LEFT JOIN {$this->rooms} r ON b.room_id = r.id
                    LEFT JOIN {$this->room_types} rt ON r.room_type_id = rt.id
                    ORDER BY b.created_at DESC
                ";
                
                $stmt = $this->con->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                $reservations = [];
                while($row = $result->fetch_assoc()){
                    $reservations[] = $row;
                }

                return $reservations;
            } catch(Exception $e) {
                throw new Exception("Error " . $e->getMessage());
            }
        }

        public function findById($booking_id){
            try {
                $query = "
                    SELECT 
                        b.*, 
                        u.full_name as guest_name, u.email as guest_email, u.phone as guest_phone,
                        r.number as room_number,
                        rt.title as room_type, rt.price_per_night
                    FROM {$this->bookings} b
                    LEFT JOIN {$this->users} u ON b.user_id = u.user_id
                    LEFT JOIN {$this->rooms} r ON b.room_id = r.id
                    LEFT JOIN {$this->room_types} rt ON r.room_type_id = rt.id
                    WHERE b.booking_id = ?
                ";
                
                $stmt = $this->con->prepare($query);
                $stmt->bind_param("i", $booking_id); // "i" means integer
                $stmt->execute();
                $result = $stmt->get_result();
                
                return $result->fetch_assoc(); // Isang row lang ang ibabalik nito
            } catch(Exception $e) {
                throw new Exception("Error fetching booking details: " . $e->getMessage());
            }
        }
    }

?>