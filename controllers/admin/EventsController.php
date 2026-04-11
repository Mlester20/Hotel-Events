<?php 
session_start();

require_once __DIR__ . '/../../models/admin/EventsModel.php';
require_once __DIR__ . '/../../helpers/message.php';
require_once __DIR__ . '/../../db/config/config.php';

    try{
        $eventsModel = new EventsModel($con);
        $events = $eventsModel->index();
    }catch(Exception $e){
        setFlash('error', $e->getMessage());
        $events = [];
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createEvent'])){
        $title = $_POST['title'] ?? '';
        $description = $_POST['Description'] ?? '';
        $location = $_POST['location'] ?? '';
        $capacity = $_POST['capacity'] ?? '';
        $price = $_POST['price'] ?? '';

        //validation to avoid being passed an negative value for capacity and price
        if($capacity < 0){
            setFlash('error', 'Capacity cannot be negative.');
            header('Location: ../../../resources/views/admin/events.php');
            exit();
        }

        if($price < 0){
            setFlash('error', 'Price cannot be negative.');
            header('Location: ../../../resources/views/admin/events.php');
            exit();
        }

        $data = [
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'capacity' => $capacity,
            'price' => $price
        ];

        try{
            $eventsModel->create($data);
            setFlash('success', 'Event created successfully!');
            header('Location: ../../../resources/views/admin/events.php');
            exit();
        }catch(Exception $e){
            setFlash('error', 'Error creating event: ' . $e->getMessage());
        }
    }

    // handles delete
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])){
        $id = $_POST['id'];

        try{
            $eventsModel->delete($id);
            setFlash('success', 'Event deleted successfully!');
            header('Location: ../../../resources/views/admin/events.php');
            exit();
        }catch(Exception $e){
            setFlash('error', 'Error deleting event: ' . $e->getMessage());
        }
    }

?>