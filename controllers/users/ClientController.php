<?php
session_start();
require_once __DIR__ . '/../../models/users/HomeModel.php';
require_once __DIR__ . '/../../models/users/EventsModel.php';
require_once __DIR__ . '/../../models/admin/RoomsModel.php'; //getting rooms index from admin controller.
require_once __DIR__ . '/../../db/config/config.php';

    class ClientController{
        private $homeModel;
        private $eventsModel;
        private $roomsModel;

        //construct the models and passed $con as a parameter to the models to connect to the database.
        public function __construct($con){
            $this->homeModel = new HomeModel($con);
            $this->eventsModel = new EventsModel($con);
            $this->roomsModel = new RoomsModel($con);
        }

        public function getHomeData(){
            return $this->homeModel->index();
        }

        public function getEvents(){
            return $this->eventsModel->index();
        }

        public function getRoomsTypes(){
            return $this->roomsModel->index();
        }

        public function getByRoomId($id){
            return $this->roomsModel->find($id);
        }
    }

    $clientController = new ClientController($con);
    $rooms = $clientController->getRoomsTypes();
    $events = $clientController->getEvents();
    $descriptions = $clientController->getHomeData();

?>