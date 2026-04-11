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

        public function create($data){
            try{
                $query = "INSERT INTO {$this->events} (title, description, location, capacity, price) VALUES (?, ?, ?, ?, ?)";
                
                $stmt = $this->con->prepare($query);
                $stmt->bind_param(
                    "sssii", 
                    $data['title'], 
                    $data['description'], 
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