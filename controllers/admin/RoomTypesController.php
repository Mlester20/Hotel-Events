<?php
session_start();
require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../../models/admin/RoomTypesModel.php';
require_once __DIR__ . '/../../helpers/message.php';

    // fetch all room types
    try{
        $roomTypesModel = new RoomTypesModel($con);
        $roomTypes = $roomTypesModel->index();
    }catch(Exception $e){
        echo "Error fetching room types: " . $e->getMessage();
    }

    // create room type
    if(isset($_POST['createRoomType'])){ 
        try{
            $data = [
                'title' => $_POST['title'],
                'details' => $_POST['details']
            ];
            $roomTypesModel->create($data);
            setFlash("success", "Room type created successfully");
            header("Location: ../../../resources/views/admin/room-types.php");
            exit();
        }catch(Exception $e){
            setFlash("danger", "Error: " . $e->getMessage());
            header("Location: ../../../resources/views/admin/room-types.php");
            exit();
        }
    }

    //edit room type
    if(isset($_POST['id']) && isset($_POST['title']) && isset($_POST['details'])){
        try{
            $id = $_POST['id'];
            $data = [
                'title' => $_POST['title'],
                'details' => $_POST['details']
            ];
            $roomTypesModel->update($id, $data);
            setFlash("success", "Room type updated successfully");
            header("Location: ../../../resources/views/admin/room-types.php");
            exit();
        }catch(Exception $e){
            setFlash("danger", "Error updating room type: " . $e->getMessage());
            header("Location: ../../../resources/views/admin/room-types.php");
            exit();
        }
    }

    if(isset($_POST['deleteRoomType'])){
        try {
            $id = $_POST['id']; 
            
            $roomTypesModel->delete($id);
            setFlash("success", "Room type deleted successfully");
            header("Location: ../../../resources/views/admin/room-types.php");
            exit();
        } catch(Exception $e) {
            setFlash("danger", "Error deleting room type: " . $e->getMessage());
            header("Location: ../../../resources/views/admin/room-types.php");
            exit();
        }
    }

?>