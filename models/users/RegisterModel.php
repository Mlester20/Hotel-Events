<?php
require_once __DIR__ . '/../BaseModel.php'; 

class RegisterModel extends BaseModel {
    protected $table = 'users';

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    public function create($data) {
        // Prepare the SQL
        $sql = "INSERT INTO {$this->table} (full_name, email, password, role) VALUES (?, ?, ?, ?)";
        
        $stmt = $this->con->prepare($sql); 

        if ($stmt) {
            $stmt->bind_param("ssss", 
                $data['fullname'], 
                $data['email'], 
                $data['password'], 
                $data['role']
            );

            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }
}
?>