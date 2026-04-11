<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../models/users/RoomReservationsModel.php';
require_once __DIR__ . '/../../db/config/config.php';

    try{
        $user_id = $_SESSION['user_id'];
        $model = new RoomReservationsModel($con);
        $reservations = $model->index($user_id);
    }catch(Exception $e){
        die("Error fetching reservations: " . $e->getMessage());
    }

?>