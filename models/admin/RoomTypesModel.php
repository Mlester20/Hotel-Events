<?php 
require_once __DIR__ . '/../BaseModel.php';

    class RoomTypesModel extends BaseModel{

        protected $room_types = "room_types";

        public function index(){
            try{
                $query = "SELECT * FROM {$this->room_types}";
                $stmt = $this->con->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                //create an array to hold the room types
                $roomTypes = [];
                while($row = $result->fetch_assoc()){
                    $roomTypes[] = $row;
                }
                return $roomTypes;
            }catch(Exception $e){
                throw new Exception("Error fetching room types: " . $e->getMessage());
            }
        }

        public function create($data){
            try{
                $query = "INSERT INTO {$this->room_types} (title, details) VALUES (?, ?)";
                $stmt = $this->con->prepare($query);
                $stmt->bind_param("ss", $data['title'], $data['details']);
                
                // execute the query and check if it was successful
                if($stmt->execute()){
                    $stmt->close();
                    return true;
                }
                throw new Exception("Failed to execute query");

            }catch(Exception $e){
                throw new Exception("Error creating room type: " . $e->getMessage());
            }
        }

        public function update($id, $data){
            try{
                
                $query = "UPDATE {$this->room_types} SET title = ?, details = ? WHERE id = ?";
                $stmt = $this->con->prepare($query);
                $stmt->bind_param("ssi", $data['title'], $data['details'], $id);
                
                if($stmt->execute()){
                    $stmt->close();
                    return true;
                }
                throw new Exception("Failed to execute query");
            }catch(Exception $e){
                throw new Exception("Error updating room type: " . $e->getMessage());
            }
        }

        public function delete($id){
            try{
                $query = "DELETE FROM {$this->room_types} WHERE id = ?";
                $stmt = $this->con->prepare($query);
                $stmt->bind_param("i", $id);
                if($stmt->execute()){
                    $stmt->close();
                    return true;
                }
                throw new Exception("Failed to execute query");
            }catch(Exception $e){
                throw new Exception("Error deleting room type: " . $e->getMessage());
            }
        }
    }

?>