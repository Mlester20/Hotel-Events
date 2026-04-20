<?php
session_start();

require_once __DIR__ . '/../../models/admin/BookingsModel.php';
require_once __DIR__ . '/../../db/config/config.php';

    class ReservationsController{
        private $model;

        public function __construct($model) {
            $this->model = $model;
        }

        public function index() {
            try {
                return $this->model->index();
            } catch (Exception $e) {
                throw new Exception("Error fetching reservations: " . $e->getMessage());
            }
        }
    }

    // Instantiate the controller and fetch reservations
    $bookingsModel = new BookingsModel($con);
    $reservationsController = new ReservationsController($bookingsModel);

    try{
        $reservations = $reservationsController->index();
    }catch(Exception $e){
        throw new Exception("Error " . $e->getMessage());
    }

?>