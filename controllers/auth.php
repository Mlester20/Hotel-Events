<?php
session_start();

require_once __DIR__ . '/../db/config/config.php';
require_once __DIR__ . '/../models/AuthModel.php';
require_once __DIR__ . '/../helpers/message.php';
require_once __DIR__ . '/../helpers/ActivityLogger.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $authModel = new AuthModel($con);
    $logger = new ActivityLogger($con);

    $email = $_POST['email'];
    $password = $_POST['password'];

    $row = $authModel->getUserByEmail($email);

    if ($row && $authModel->verifyPassword($password, $row['password'])) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['full_name'] = $row['full_name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['profile'] = $row['profile'];
        $_SESSION['role'] = $row['role'];
        
        $logger->log(
            $row['user_id'],
            $row['role'],
            'LOGIN',
            'AUTH',
            null,
            null,
            $row['full_name'] . ' logged in',
            'success'
        );

        // ✅ REDIRECT
        if ($_SESSION['role'] === 'admin') {
            header("Location: ../resources/views/admin/dashboard.php");
        } 
        else if ($_SESSION['role'] === 'front_desk') {
            header("Location: ../resources/views/frontdesk/home.php");
        } 
        else {
            header("Location: ../resources/views/pages/home.php");
        }
        exit();

    } else {
        $logger->log(
            null,
            'user',
            'LOGIN',
            'AUTH',
            null,
            null,
            'Failed login attempt: ' . $email,
            'failed'
        );

        setFlash("error", "Invalid email or password.");
        header("Location: ../index.php");
        exit();
    }
}