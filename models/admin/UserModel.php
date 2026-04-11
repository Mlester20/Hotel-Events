<?php
require_once __DIR__ . '/../BaseModel.php';
require_once __DIR__ . '/../EncyptPasswordModel.php';

    class UserModel extends BaseModel{
        protected $users = 'users';
        
        public function index(){
            try{
                $query = "SELECT user_id, full_name, email, role FROM users ORDER BY user_id ASC";
                $stmt = $this->con->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                //store users data into array
                $users = [];
                while($row = $result->fetch_assoc()){
                    $users[] = $row;
                }
                return $users;
            }catch(Exception $e){
                throw new Exception("Error " . $e->getMessage(), 500);
            }
        }
        public function findById($user_id){

        }

        //create a function where admin is able to create frontdesk users account
        public function create($data){
            try{
                $encryptPassword = new EncryptModel();
                $hashedPassword = $encryptPassword->hashPassword($data['password']);

                $query = "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)";
                $stmt = $this->con->prepare($query);
                $stmt->bind_param("ssss", $data['full_name'], $data['email'], $hashedPassword, $data['role']);
                $stmt->execute();

                return true;
            }catch(Exception $e){
                throw new Exception("Error " . $e->getMessage(), 500);
            }
        }
    }
?>
