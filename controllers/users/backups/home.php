<?php

require_once __DIR__ . '/../../models/users/HomeModel.php';
require_once __DIR__ . '/../../db/config/config.php';

    try{
        $homeModel = new HomeModel($con);
        $descriptions = $homeModel->index();
    }catch(Exception $e){
        throw new Exception('Error ' . $e->getMessage(), 500);
    }

?>