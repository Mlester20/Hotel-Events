<?php
session_start();
require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../../models/admin/HomeDescriptionModel.php';
require_once __DIR__ . '/../../helpers/message.php';

    //fetch home descriptions
    try{
        $homeDescriptionModel = new HomeDescriptionModel($con);
        $home_descriptions = $homeDescriptionModel->index();
    } catch(Exception $e){
        die('Error fetching home descriptions: ' . $e->getMessage());
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createHomeDescription'])){
        $title = $_POST['title'];
        $content = $_POST['content'];

        //validate inputs
        if(empty($title) || empty($content)){
            setFlash("'danger'" ,'All fields are required');
            header('Location: ../../../resources/views/admin/home-description.php');
            exit;
        }

        //create home description
        try{
            $homeDescriptionModel->create([
                'title' => $title,
                'content' => $content
            ]);
            setFlash('success', 'Home description created successfully');
            header('Location: ../../../resources/views/admin/home-description.php');
            exit;
        } catch(Exception $e){
            die('Error creating home description: ' . $e->getMessage());
        }
    }

    //update home description
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editHomeDescription'])){
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        //validate inputs
        if(empty($title) || empty($content)){
            setFlash("'danger'" ,'All fields are required');
            header('Location: ../../../resources/views/admin/home-description.php');
            exit;
        }

        //update home description
        try{
            $homeDescriptionModel->update($id, [
                'title' => $title,
                'content' => $content
            ]);
            setFlash('success', 'Home description updated successfully');
            header('Location: ../../../resources/views/admin/home-description.php');
            exit;
        } catch(Exception $e){
            die('Error updating home description: ' . $e->getMessage());
        }
    }

    //delete home description
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteHomeDescription'])){
        $id = $_POST['id'];

        try{
            $homeDescriptionModel->delete($id);
            setFlash('success', 'Home description deleted successfully');
            header('Location: ../../../resources/views/admin/home-description.php');
            exit;
        } catch(Exception $e){
            die('Error deleting home description: ' . $e->getMessage());
        }
    }

?>