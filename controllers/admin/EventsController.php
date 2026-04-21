<?php 
session_start();

require_once __DIR__ . '/../../models/admin/EventsModel.php';
require_once __DIR__ . '/../../helpers/message.php';
require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../Controller.php';

    class EventsController extends Controller {
        public function __construct($model) {
            parent::__construct($model);
        }

        public function index(){
            try{
                return $this->model->index();
            }catch(Exception $e){
                throw new Exception("Error fetching events: " . $e->getMessage());
            }
        }

        public function create($data){
            // Validation
            if (empty($data['title']) || empty($data['description'])) {
                setFlash("danger", "Please fill in all required fields.");
                header("Location: ../../../resources/views/admin/events.php");
                exit();
            }

            // Image handling
            $imageName = null;
            $image = $data['image'] ?? null;
            if ($image && !empty($image['name'])) {
                $imageName = $this->model->storeImage($image);
            }

            $data['image'] = $imageName;

            try{
                $this->model->create($data);
                setFlash("success", "Event created successfully");
            }catch(Exception $e){
                throw new Exception("Error creating event: " . $e->getMessage());
            }
            header("Location: ../../../resources/views/admin/events.php");
            exit();
        }

        public function update($id, $data){
            // Validation
            if (empty($id) || empty($data['title']) || empty($data['description'])) {
                setFlash("danger", "Please fill in all required fields.");
                header("Location: ../../../resources/views/admin/events.php");
                exit();
            }

            // Image handling
            $image = $data['image'] ?? null;
            if ($image && !empty($image['name'])) {
                $imageName = $this->model->storeImage($image);
                $data['image'] = $imageName;
            } else {
                // Keep existing image if no new one uploaded
                unset($data['image']);
            }

            try{
                $this->model->update($id, $data);
                setFlash("success", "Event updated successfully");
            }catch(Exception $e){
                throw new Exception("Error updating event: " . $e->getMessage());
            }
            header("Location: ../../../resources/views/admin/events.php");
            exit();
        }

        public function delete($id){
            try{
                $this->model->delete($id);
                setFlash("success", "Event deleted successfully");
            }catch(Exception $e){
                throw new Exception("Error deleting event: " . $e->getMessage());
            }
            header("Location: ../../../resources/views/admin/events.php");
            exit();
        }
    }


    // ─── Bootstrap: instantiate and dispatch ───────────────────────────────────
    $eventsModel      = new EventsModel($con);
    $eventsController = new EventsController($eventsModel);

    try{
        $events = $eventsController->index();
    }catch(Exception $e){
        setFlash('error', $e->getMessage());
        $events = [];
    }


    if(($_SERVER['REQUEST_METHOD'] === 'POST')){
        if(isset($_POST['createEvent'])){
            $eventsController->create([
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'location' => $_POST['location'] ?? '',
                'capacity' => $_POST['capacity'] ?? '',
                'price' => $_POST['price'] ?? '',
                'image' => $_FILES['image'] ?? null
            ]);
        }

        if(isset($_POST['updateEvent'])){
            $eventsController->update($_POST['id'], [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'location' => $_POST['location'] ?? '',
                'capacity' => $_POST['capacity'] ?? '',
                'price' => $_POST['price'] ?? '',
                'image' => $_FILES['image'] ?? null
             ]);
        }

        if(isset($_POST['deleteEvent'])){
            $eventsController->delete($_POST['id']);
        }
    }

?>