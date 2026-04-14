<?php

require_once __DIR__ . '/../../models/users/EventsModel.php';

    try{
        $eventsModel = new EventsModel($con);
        $events = $eventsModel->index();
    }catch(Exception $e){
        error_log("Error in Events controller: " . $e->getMessage());
        $events = [];
    }

?>