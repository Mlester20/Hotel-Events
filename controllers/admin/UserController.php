<?php 
session_start();

require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../../models/admin/UserModel.php';
require_once __DIR__ . '/../../helpers/message.php';
require_once __DIR__ . '/../Controller.php';

    class UserController extends Controller {

        public function __construct($model) {
            parent::__construct($model);
        }

        public function index() {
            try {
                return $this->model->index();
            } catch (Exception $e) {
                throw new Exception("Error fetching users: " . $e->getMessage());
            }
        }

        public function create($data) {
            try {
                $this->model->create($data);
                setFlash("success", "User created successfully");
            } catch (Exception $e) {
                setFlash("danger", "Error: " . $e->getMessage());
            }
            header("Location: ../../../resources/views/admin/users.php");
            exit();
        }

        public function update($id, $data) {
            try {
                $this->model->update($id, $data);
                setFlash("success", "User updated successfully");
            } catch (Exception $e) {
                setFlash("danger", "Error updating user: " . $e->getMessage());
            }
            header("Location: ../../../resources/views/admin/users.php");
            exit();
        }

        public function delete($id) {
            try {
                $this->model->delete($id);
                setFlash("success", "User deleted successfully");
            } catch (Exception $e) {
                setFlash("danger", "Error deleting user: " . $e->getMessage());
            }
            header("Location: ../../../resources/views/admin/users.php");
            exit();
        }
    }
    
    // ─── Bootstrap ────────────────────────────────────────────────────────────────
    $userModel      = new UserModel($con);
    $userController = new UserController($userModel);

    // $users is exposed to the view
    $users = $userController->index();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['addUser'])) {
            $userController->create([
                'full_name' => $_POST['full_name'] ?? '',
                'email'     => $_POST['email']     ?? '',
                'password'  => $_POST['password']  ?? '',
                'role'      => 'front_desk',
            ]);
        }

        if (isset($_POST['updateUser'])) {
            $userController->update($_POST['user_id'], [   // <-- user_id, hindi id
                'full_name' => $_POST['full_name'] ?? '',
                'email'     => $_POST['email']     ?? '',
                'role'      => $_POST['role']      ?? '',
            ]);
        }

        if (isset($_POST['deleteUser'])) {
            $userController->delete($_POST['user_id']);    // <-- user_id, hindi id
        }
    }