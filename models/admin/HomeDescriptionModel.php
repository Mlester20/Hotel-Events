<?php   
require_once __DIR__ . '/../BaseModel.php';

    class HomeDescriptionModel extends BaseModel{
        protected $home_descriptions = 'home_descriptions';

        public function index(){
            try{
                $query = "SELECT * FROM {$this->home_descriptions} ORDER BY id DESC";
                $stmt = $this->con->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                //store the results in an array
                $home_descriptions = [];
                while($row = $result->fetch_assoc()){
                    $home_descriptions[] = $row;
                }
                return $home_descriptions;
            }catch(Exception $e){
                die('Error fetching home descriptions: ' . $e->getMessage());
            }
        }

        public function create($data){
                try{
                    $query = "INSERT INTO {$this->home_descriptions} (title, content) VALUES (?, ?)";
                    $stmt = $this->con->prepare($query);
                    $stmt->bind_param('ss', $data['title'], $data['content']);
                    $stmt->execute();
                }catch(Exception $e){
                    die('Error creating home description: ' . $e->getMessage());
                }
        }

        public function update($id, $data){
            try{
                $query = "UPDATE {$this->home_descriptions} SET title = ?, content = ? WHERE id = ?";
                $stmt = $this->con->prepare($query);
                $stmt->bind_param('ssi', $data['title'], $data['content'], $id);
                $stmt->execute();
            }catch(Exception $e){
                die('Error updating home description: ' . $e->getMessage());
            }
        }

        public function delete($id){
            try{
                $query = "DELETE FROM {$this->home_descriptions} WHERE id = ?";
                $stmt = $this->con->prepare($query);
                $stmt->bind_param('i', $id);
                $stmt->execute();
            }catch(Exception $e){
                die('Error deleting home description: ' . $e->getMessage());
            }
        }
    }

?>