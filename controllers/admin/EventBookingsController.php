<?php 
session_start();

require_once __DIR__ . '/../../models/admin/EventsBookingModel.php';
require_once __DIR__ . '/../../helpers/message.php';
require_once __DIR__ . '/../../db/config/config.php';

    class EventBookingsController{
        private $model;

        public function __construct($model) {
            $this->model = $model;
        }

        public function index() {
            try {
                return $this->model->index();
            } catch (Exception $e) {
                throw new Exception("Error fetching event bookings: " . $e->getMessage());
            }
        }
        
    }

    try{
        $controller = new EventBookingsController(new EventsBookingModel($con));
        $event_bookings = $controller->index();
    } catch (Exception $e) {
        setFlash('error', 'Failed to retrieve event bookings: ' . $e->getMessage());
        header('Location: ../../../views/admin/event-bookings.php');
        exit();
    }

?>