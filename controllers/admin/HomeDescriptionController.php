<?php
session_start();
require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../../models/admin/HomeDescriptionModel.php';
require_once __DIR__ . '/../../helpers/message.php';
require_once __DIR__ . '/../Controller.php';

    class HomeDescriptionController extends Controller {
        public function __construct($model) {
            parent::__construct($model);
        }

        public function index(){
            try{
                return $this->model->index();
            }catch(Exception $e){
                throw new Exception("Error fetching home descriptions: " . $e->getMessage());
            }
        }

        public function create($data){
            try{
                $this->model->create($data);
                setFlash('success', 'Home description created successfully');
            }catch(Exception $e){
                throw new Exception("Error creating home description: " . $e->getMessage());
            }
            header('Location: ../../../resources/views/admin/home-description.php');
            exit();
        }

        public function update($id, $data){
            try{
                $this->model->update($id, $data);
                setFlash('success', 'Home description updated successfully');
            }catch(Exception $e){
                throw new Exception("Error updating home description: " . $e->getMessage());
            }
            header('Location: ../../../resources/views/admin/home-description.php');
            exit();
        }

        public function delete($id){
            try{
                $this->model->delete($id);
                setFlash('success', 'Home description deleted successfully');
            }catch(Exception $e){
                throw new Exception("Error deleting home description: " . $e->getMessage());
            }
            header('Location: ../../../resources/views/admin/home-description.php');
            exit();
        }
    }

    // initialize controller
    $homeDescriptionModel = new HomeDescriptionModel($con);
    $homeDescriptionController = new HomeDescriptionController($homeDescriptionModel);

    //fetch home descriptions
    try{
        $home_descriptions = $homeDescriptionController->index();
    } catch(Exception $e){
        die('Error fetching home descriptions: ' . $e->getMessage());
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['createHomeDescription'])){
            $homeDescriptionController->create([
                'title' => $_POST['title'] ?? '',
                'content' => $_POST['content'] ?? ''
            ]);
        }
        if(isset($_POST['editHomeDescription'])){
            $id = $_POST['id'] ?? '';
            $homeDescriptionController->update($id, [
                'title' => $_POST['title'] ?? '',
                'content' => $_POST['content'] ?? ''
            ]);
        }
        if(isset($_POST['deleteHomeDescription'])){
            $id = $_POST['id'] ?? '';
            $homeDescriptionController->delete($id);
        }
    }   

?>