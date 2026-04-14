<?php
require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../../models/users/RegisterModel.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {

        // Create an instance of the RegisterModel
        $registerModel = new RegisterModel($con); 
        $hashedPassword = $registerModel->hashPassword($_POST['password']);

        $data = [
            'fullname' => $_POST['fullname'],
            'email'    => $_POST['email'],
            'password' => $hashedPassword,
            'role'     => 'user'          
        ];

        if ($registerModel->create($data)) {
            echo "Registration successful!";
            header("Location: ../../index.php");
            exit();
        } else {
            echo "Registration failed.";
        }
    }
?>