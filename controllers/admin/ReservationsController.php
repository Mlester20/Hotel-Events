<?php
session_start();

require_once __DIR__ . '/../../models/admin/BookingsModel.php';
require_once __DIR__ . '/../../db/config/config.php';

    try{
        $bookingsModel = new BookingsModel($con);
        $reservations = $bookingsModel->index();
    }catch(Exception $e){
        throw new Exception("Error " . $e->getMessage());
    }

?>