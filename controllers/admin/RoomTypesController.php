<?php
session_start();
require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../../models/admin/RoomTypesModel.php';
require_once __DIR__ . '/../../helpers/message.php';
require_once __DIR__ . '/../Controller.php';

    class RoomTypesController extends Controller {

        public function __construct($model) {
            parent::__construct($model);
        }

        public function index() {
            try {
                return $this->model->index();
            } catch (Exception $e) {
                throw new Exception("Error fetching room types: " . $e->getMessage());
            }
        }

        public function create($data) {
            try {
                $this->model->create($data);
                setFlash("success", "Room type created successfully");
            } catch (Exception $e) {
                setFlash("danger", "Error: " . $e->getMessage());
            }
            header("Location: ../../../resources/views/admin/room-types.php");
            exit();
        }

        public function update($id, $data) {
            try {
                $this->model->update($id, $data);
                setFlash("success", "Room type updated successfully");
            } catch (Exception $e) {
                setFlash("danger", "Error updating room type: " . $e->getMessage());
            }
            header("Location: ../../../resources/views/admin/room-types.php");
            exit();
        }

        public function delete($id) {
            try {
                $this->model->delete($id);
                setFlash("success", "Room type deleted successfully");
            } catch (Exception $e) {
                setFlash("danger", "Error deleting room type: " . $e->getMessage());
            }
            header("Location: ../../../resources/views/admin/room-types.php");
            exit();
        }
    }

    // ─── Bootstrap: instantiate and dispatch ───────────────────────────────────
    $roomTypesModel      = new RoomTypesModel($con);
    $roomTypesController = new RoomTypesController($roomTypesModel);

    // Expose $roomTypes so the view can use it
    $roomTypes = $roomTypesController->index();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['createRoomType'])) {
            $roomTypesController->create([
                'title'   => $_POST['title'],
                'details' => $_POST['details'],
            ]);
        }

        // Edit: all three fields present but no dedicated flag
        if (isset($_POST['id'], $_POST['title'], $_POST['details']) && !isset($_POST['deleteRoomType'])) {
            $roomTypesController->update($_POST['id'], [
                'title'   => $_POST['title'],
                'details' => $_POST['details'],
            ]);
        }

        if (isset($_POST['deleteRoomType'])) {
            $roomTypesController->delete($_POST['id']);
        }
    }