<?php
require_once __DIR__ . '/../BaseModel.php';

    class RoomsModel extends BaseModel{
        protected $rooms = 'rooms';
        protected $room_types = 'room_types';

        public function index(){
            try{
                $query = "
                    SELECT
                        r.id,
                        r.room_number,
                        r.room_type_id,
                        rt.title AS room_type,
                        r.amenities,
                        r.images,
                        r.price_hourly,
                        r.price_overnight,
                        r.price_day
                    FROM {$this->rooms} r
                    JOIN {$this->room_types} rt ON r.room_type_id = rt.id
                ";
                $stmt = $this->con->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $rooms = [];
                while($row = $result->fetch_assoc()){
                    $rooms[] = $row;
                }
                return $rooms;
            }catch(Exception $e){
                echo "Error: " . $e->getMessage();
            }
        }

        //function to store image in the database
        public function storeImage($imageData){
            $imageName = time() . '_' . $imageData['name'];
            $imagePath = __DIR__ . '/../../storage/rooms/' . $imageName;
            move_uploaded_file($imageData['tmp_name'], $imagePath);
            return $imageName;
        }

        public function roomTypes(){
            try{
                $query = "SELECT * FROM {$this->room_types}";
                $stmt = $this->con->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $roomTypes = [];
                while($row = $result->fetch_assoc()){
                    $roomTypes[] = $row;
                }
                return $roomTypes;
            }catch(Exception $e){
                echo "Error: " . $e->getMessage();
            }
        }

        public function create($data){
            try{
                $query = "INSERT INTO {$this->rooms} 
                        (room_number, room_type_id, amenities, images, price_hourly, price_overnight, price_day) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->con->prepare($query);

                if(!$stmt){
                    throw new Exception("Failed to prepare query: " . $this->con->error);
                }

                $stmt->bind_param(
                    "sissddd", 
                    $data['room_number'],
                    $data['room_type_id'],
                    $data['amenities'],
                    $data['images'],
                    $data['price_hourly'],
                    $data['price_overnight'],
                    $data['price_day']
                );

                $executed = $stmt->execute();
                $stmt->close(); // always close

                if(!$executed){
                    throw new Exception("Failed to execute query: " . $stmt->error);
                }

                return true;

            } catch(Exception $e){
                // Re-throw so the controller's catch block can handle it properly
                throw new Exception($e->getMessage());
            }
        }   

        public function find($id){
            try{
                $query = "SELECT * FROM {$this->rooms} WHERE id = ?";
                $stmt  = $this->con->prepare($query);
                if(!$stmt) throw new Exception("Prepare failed: " . $this->con->error);

                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row    = $result->fetch_assoc();
                $stmt->close();
                return $row;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        public function update($id, $data){
            try{
                $query = "UPDATE {$this->rooms} 
                        SET room_number = ?, room_type_id = ?, amenities = ?, images = ?,
                            price_hourly = ?, price_overnight = ?, price_day = ?
                        WHERE id = ?";
                $stmt = $this->con->prepare($query);
                if(!$stmt) throw new Exception("Prepare failed: " . $this->con->error);

                $stmt->bind_param(
                    "sissdddi",  
                    $data['room_number'],    // s
                    $data['room_type_id'],   // i
                    $data['amenities'],      // s
                    $data['images'],         // s
                    $data['price_hourly'],   // d
                    $data['price_overnight'],// d
                    $data['price_day'],      // d
                    $id                      // i
                );

                $executed = $stmt->execute();
                $stmt->close();

                if(!$executed) throw new Exception("Failed to execute query: " . $this->con->error);
                return true;

            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        public function delete($id){
            try{
                $query = "DELETE FROM {$this->rooms} WHERE id = ?";
                $stmt = $this->con->prepare($query);
                $stmt->bind_param("i", $id);
                if($stmt->execute()){
                    $stmt->close();
                    return true;
                }
                throw new Exception("Failed to execute query");
            }catch(Exception $e){
                echo "Error: " . $e->getMessage();
            }
        }
    }

?>