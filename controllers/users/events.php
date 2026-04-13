<?php

require_once __DIR__ . '/../../models/users/EventsModel.php';
require_once __DIR__ . '/../../db/config/config.php';

    try{
        $eventsModel = new EventsModel($con);
        $events = $eventsModel->get();
    }catch(Exception $e){
        error_log("Error in Events controller: " . $e->getMessage());
        $events = [];
    }

?>