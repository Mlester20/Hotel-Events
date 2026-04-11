<?php
require_once __DIR__ . '/../helpers/message.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAuthenticated() {
    if (!isset($_SESSION['user_id'])) {
        setFlash("error", "Please log in to access this page.");
        header("Location: /index.php");
        exit();
    }
}

function allowOnly($allowed_roles = []) {
    isAuthenticated();

    if (!in_array($_SESSION['role'], $allowed_roles)) {
        setFlash("error", "You do not have permission to access this page.");
        header("Location: /index.php"); 
        exit();
    }
}