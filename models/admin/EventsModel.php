<?php
require_once __DIR__ . '/../BaseModel.php';

    class EventsModel extends BaseModel{
        protected $events = 'events';

        public function index(){
            try{
                $query = "SELECT * FROM {$this->events} ORDER BY created_at DESC";
                
                $stmt = $this->con->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                $events = [];
                while($row = $result->fetch_assoc()){
                    $events[] = $row;
                }

                return $events;
                exit(); // Ensure no further code is executed after returning the events
            }catch(Exception $e){
                throw new Exception("Error fetching events: " . $e->getMessage());
            }
        }

        //create a class method to store image in the database
        public function storeImage($imageData){
            $imageName = time() . '_' . $imageData['name'];
            $imagePath = __DIR__ . '/../../storage/events/' . $imageName;
            move_uploaded_file($imageData['tmp_name'], $imagePath);
            return $imageName;
        }

        public function create($data){
            try{
                $query = "INSERT INTO {$this->events} (title, description, image, location, capacity, price) VALUES (?, ?, ?, ?, ?, ?)";
                
                $stmt = $this->con->prepare($query);
                $stmt->bind_param(
                    "ssssii", 
                    $data['title'], 
                    $data['description'], 
                    $data['image'], 
                    $data['location'], 
                    $data['capacity'], 
                    $data['price']
                );

                if(!$stmt->execute()){
                    throw new Exception("Failed to create event: " . $stmt->error);
                }
            }catch(Exception $e){
                throw new Exception("Error creating event: " . $e->getMessage());
            }
        }

        public function update($id, $data){
            try{
                if (!empty($data['image'])) {
                    $query = "UPDATE {$this->events} SET title = ?, description = ?, image = ?, location = ?, capacity = ?, price = ? WHERE id = ?";
                    $stmt = $this->con->prepare($query);
                    $stmt->bind_param(
                        "sssssii", 
                        $data['title'], 
                        $data['description'], 
                        $data['image'],
                        $data['location'], 
                        $data['capacity'], 
                        $data['price'],
                        $id
                    );
                } else {
                    $query = "UPDATE {$this->events} SET title = ?, description = ?, location = ?, capacity = ?, price = ? WHERE id = ?";
                    $stmt = $this->con->prepare($query);
                    $stmt->bind_param(
                        "sssii", 
                        $data['title'], 
                        $data['description'], 
                        $data['location'], 
                        $data['capacity'], 
                        $data['price'],
                        $id
                    );
                }

                if(!$stmt->execute()){
                    throw new Exception("Failed to update event: " . $stmt->error);
                }
            }catch(Exception $e){
                throw new Exception("Error updating event: " . $e->getMessage());
            }
        }

        public function delete($id){
            try{
                $query = "DELETE FROM {$this->events} WHERE id = ?";
                $stmt = $this->con->prepare($query);
                $stmt->bind_param("i", $id);

                if(!$stmt->execute()){
                    throw new Exception("Failed to delete event: " . $stmt->error);
                }
            }catch(Exception $e){
                throw new Exception("Error deleting event: " . $e->getMessage());
            }
        }

    }
    

?>