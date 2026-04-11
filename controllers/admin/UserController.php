<?php 
session_start();

require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../../models/admin/UserModel.php';
require_once __DIR__ . '/../../helpers/message.php';

    //fetch users
    try{
        $userModel = new UserModel($con);
        $users = $userModel->index();
    }catch(Exception $e){
        throw new Exception('Error ' . $e->getMessage());
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addUser'])){
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = 'front_desk';

        // set data to array
        $data = [
            'full_name' => $full_name,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ];

        try{
            $userModel = new UserModel($con);
            
            if($userModel->create($data)){
                setFlash("success", "User created successfully!");
                header("Location: ../../../resources/views/admin/users.php");
                exit();
            }else{
                setFlash("error", "Failed to create user");
                throw new Exception('Failed to create user');
            }
            
        }catch(Exception $e){
            throw new Exception('Error ' . $e->getMessage());
        }
    }
?>