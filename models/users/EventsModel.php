<?php
require_once __DIR__ . '/../BaseModel.php';

    class EventsModel extends BaseModel {
        protected $events = 'events';

        public function get(){
            try{
                $query = "SELECT * FROM {$this->events} LIMIT 3";
                $stmt = $this->con->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                $events = [];
                while($row = $result->fetch_assoc()){
                    $events[] = $row;
                }

                //return the events data as an array
                return $events;

                exit(); //make sure to exit after returning data to prevent further execution
            } catch (Exception $e) {
                error_log("Error fetching events: " . $e->getMessage());
                return [];
            }
        }
    }

?>