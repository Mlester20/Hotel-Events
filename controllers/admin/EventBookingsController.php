<?php 
session_start();

require_once __DIR__ . '/../../models/admin/EventsBookingModel.php';
require_once __DIR__ . '/../../helpers/message.php';
require_once __DIR__ . '/../../db/config/config.php';

    try{
        $model = new EventsBookingModel($con);
        $event_bookings = $model->get();
    } catch (Exception $e) {
        setFlash('error', 'Failed to retrieve event bookings: ' . $e->getMessage());
        header('Location: ../../../views/admin/event-bookings.php');
        exit();
    }

?>