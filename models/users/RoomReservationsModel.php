<?php
require_once __DIR__ . '/../BaseModel.php';
require_once __DIR__ . '/../admin/RoomTypesModel.php';

    class RoomReservationsModel extends BaseModel{
        protected $room_types = 'room_types';
        protected $rooms = 'rooms';
        protected $bookings = 'bookings';

        public function index($user_id){
                try{
                //perform query using mysqli
                $sql = "
                        SELECT 
                        b.id,
                        b.booking_id, 
                        b.check_in_date, 
                        b.check_out_date, 
                        b.status, 
                        r.room_number, 
                        r.images,
                        rt.title as room_type
                        FROM {$this->bookings} b
                        JOIN {$this->rooms} r ON b.room_id = r.id
                        JOIN {$this->room_types} rt ON r.room_type_id = rt.id
                        WHERE b.user_id = ?";

                $stmt = $this->con->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $reservations = [];
                while($row = $result->fetch_assoc()){
                    $reservations[] = $row;
                }
                return $reservations;
            }catch(Exception $e){
                die("Error fetching reservations: " . $e->getMessage());
            }
        }
    }

?>