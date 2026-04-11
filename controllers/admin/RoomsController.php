<?php
session_start();

require_once __DIR__ . '/../../models/admin/RoomsModel.php';
require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../../helpers/message.php';


    try{
        $roomsModel = new RoomsModel($con);
        $rooms = $roomsModel->index();
    } catch(Exception $e){
        echo "Error: " . $e->getMessage();
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createRoom'])){
        $room_number   = trim($_POST['number'] ?? '');
        $room_type_id  = $_POST['room_type_id'] ?? '';
        $amenities     = $_POST['amenities'] ?? '';
        $images        = $_FILES['images'] ?? null;
        $price_hourly  = $_POST['price_hourly'] ?? 0;
        $price_overnight = $_POST['price_overnight'] ?? 0;
        $price_day     = $_POST['price_day'] ?? 0;

        if(empty($room_number) || empty($room_type_id)){
            $_SESSION['error'] = "Please fill in all required fields.";
            header("Location: ../../../resources/views/admin/rooms.php");
            exit();
        }

        // Guard against missing/empty file upload
        $imageNames = [];
        if($images && !empty($images['name'][0])){
            foreach($images['name'] as $key => $imageName){
                $imageData = [
                    'name'     => $images['name'][$key],
                    'tmp_name' => $images['tmp_name'][$key]
                ];
                $storedImageName = $roomsModel->storeImage($imageData);
                if($storedImageName){
                    $imageNames[] = $storedImageName;
                }
            }
        }

        // amenities is now a plain string from the textarea (not an array)
        $amenitiesArray = array_filter(array_map('trim', explode(',', $amenities)));

        $roomData = [
            'room_number'     => $room_number,
            'room_type_id'    => $room_type_id,
            'amenities'       => json_encode(array_values($amenitiesArray)),
            'images'          => json_encode($imageNames),
            'price_hourly'    => $price_hourly,
            'price_overnight' => $price_overnight,
            'price_day'       => $price_day
        ];

        try{
            $create = $roomsModel->create($roomData);
            if($create){
                setFlash("success", "Room created successfully.");
                header("Location: ../../../resources/views/admin/rooms.php");
                exit();
            } else {
                setFlash("error", "Failed to create room.");
                header("Location: ../../../resources/views/admin/rooms.php");
                exit();
            }
        } catch(Exception $e){
            setFlash("error", "Error: " . $e->getMessage());
            header("Location: ../../../resources/views/admin/rooms.php");
            exit();
        }
    }

    // Handle update room
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateRoom'])){
        $roomId          = $_POST['id']               ?? '';
        $room_number     = trim($_POST['room_number'] ?? '');
        $room_type_id    = $_POST['room_type_id']     ?? '';
        $price_hourly    = $_POST['price_hourly']     ?? 0;
        $price_overnight = $_POST['price_overnight']  ?? 0;
        $price_day       = $_POST['price_day']        ?? 0;
        $rawAmenities = $_POST['amenities'] ?? '';
        if(is_array($rawAmenities)){
            $amenitiesFlat = implode(',', $rawAmenities);
        } else {
            $amenitiesFlat = $rawAmenities;
        }
        $amenitiesArray = array_values(array_filter(array_map('trim', explode(',', $amenitiesFlat))));
        $amenitiesJson  = json_encode($amenitiesArray);

        if(empty($roomId) || empty($room_number) || empty($room_type_id)){
            setFlash("error", "Please fill in all required fields.");
            header("Location: ../../../resources/views/admin/rooms.php");
            exit();
        }
        if(!empty($_FILES['images']['name'][0])){
            $imagePaths = [];
            foreach($_FILES['images']['tmp_name'] as $key => $tmp_name){
                $imageData = [
                    'name'     => $_FILES['images']['name'][$key],
                    'tmp_name' => $tmp_name
                ];
                $stored = $roomsModel->storeImage($imageData);
                if($stored) $imagePaths[] = $stored;
            }
            $imagesJson = json_encode($imagePaths);
        } else {
            $existing   = $roomsModel->find($roomId);
            $imagesJson = $existing['images'] ?? json_encode([]);
        }

        $roomData = [
            'room_number'     => $room_number,
            'room_type_id'    => $room_type_id,
            'amenities'       => $amenitiesJson,
            'images'          => $imagesJson,
            'price_hourly'    => $price_hourly,
            'price_overnight' => $price_overnight,
            'price_day'       => $price_day,
        ];

        try{
            $update = $roomsModel->update($roomId, $roomData);
            if($update){
                setFlash("success", "Room updated successfully!");
            } else {
                setFlash("error", "Something went wrong, try again!");
            }
        } catch(Exception $e){
            setFlash("error", "Error: " . $e->getMessage());
        }

        header("Location: ../../../resources/views/admin/rooms.php");
        exit();
    }

    // Handle delete room
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteRoom'])){
        $roomId = $_POST['id'];
        $delete = $roomsModel->delete($roomId);
        if($delete){
            setFlash("success", "Room deleted successfully.");
            header("Location: ../../../resources/views/admin/rooms.php");
            exit();
        } else {
            setFlash("error", "Failed to delete room.");
            header("Location: ../../../resources/views/admin/rooms.php");
            exit();
        }
    }

?>