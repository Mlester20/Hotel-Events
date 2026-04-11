<?php
require_once __DIR__ . '/../BaseModel.php';

    class HomeModel extends BaseModel{
        protected $descriptions = 'home_descriptions';

        public function index(){
            try{
                $query = "SELECT title, content, is_active FROM {$this->descriptions} WHERE is_active = 1 LIMIT 1";
                $stmt = $this->con->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                $descriptions = [];
                while($row = $result->fetch_assoc()){
                    $descriptions[] = $row;
                }
                return $descriptions;
            }catch(Exception $e){
                throw new Exception("Error " . $e->getMessage());
            }
        }
    }

?>