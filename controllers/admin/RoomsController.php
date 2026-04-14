<?php
session_start();

require_once __DIR__ . '/../../models/admin/RoomsModel.php';
require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../../helpers/message.php';
require_once __DIR__ . '/../Controller.php';

    class RoomsController extends Controller {

        public function __construct($model) {
            parent::__construct($model);
        }

        public function index() {
            try {
                return $this->model->index();
            } catch (Exception $e) {
                throw new Exception("Error fetching rooms: " . $e->getMessage());
            }
        }

        public function create($data) {
            // ── Validation ──────────────────────────────────────────
            if (empty($data['room_number']) || empty($data['room_type_id'])) {
                setFlash("danger", "Please fill in all required fields.");
                header("Location: ../../../resources/views/admin/rooms.php");
                exit();
            }

            // ── Image handling ───────────────────────────────────────
            $imageNames = [];
            $images = $data['images'] ?? null; // comes from $_FILES['images']

            if ($images && !empty($images['name'][0])) {
                foreach ($images['name'] as $key => $imageName) {
                    $imageData = [
                        'name'     => $images['name'][$key],
                        'tmp_name' => $images['tmp_name'][$key],
                    ];
                    $stored = $this->model->storeImage($imageData);
                    if ($stored) $imageNames[] = $stored;
                }
            }

            // ── Amenities parsing ─────────────────────────────────────
            $amenitiesArray = $this->parseAmenities($data['amenities'] ?? '');

            // ── Build payload ─────────────────────────────────────────
            $roomData = [
                'room_number'     => $data['room_number'],
                'room_type_id'    => $data['room_type_id'],
                'amenities'       => json_encode($amenitiesArray),
                'images'          => json_encode($imageNames),
                'price_hourly'    => $data['price_hourly']    ?? 0,
                'price_overnight' => $data['price_overnight'] ?? 0,
                'price_day'       => $data['price_day']       ?? 0,
            ];

            try {
                $this->model->create($roomData);
                setFlash("success", "Room created successfully.");
            } catch (Exception $e) {
                setFlash("danger", "Error: " . $e->getMessage());
            }

            header("Location: ../../../resources/views/admin/rooms.php");
            exit();
        }

        public function update($id, $data) {
            // ── Validation ────────────────────────────────────────────
            if (empty($id) || empty($data['room_number']) || empty($data['room_type_id'])) {
                setFlash("danger", "Please fill in all required fields.");
                header("Location: ../../../resources/views/admin/rooms.php");
                exit();
            }

            // ── Image handling ────────────────────────────────────────
            $images = $data['images'] ?? null; // comes from $_FILES['images']

            if (!empty($images['name'][0])) {
                $imagePaths = [];
                foreach ($images['tmp_name'] as $key => $tmp_name) {
                    $imageData = [
                        'name'     => $images['name'][$key],
                        'tmp_name' => $tmp_name,
                    ];
                    $stored = $this->model->storeImage($imageData);
                    if ($stored) $imagePaths[] = $stored;
                }
                $imagesJson = json_encode($imagePaths);
            } else {
                // Keep existing images if no new ones uploaded
                $existing   = $this->model->find($id);
                $imagesJson = $existing['images'] ?? json_encode([]);
            }

            // ── Amenities parsing ─────────────────────────────────────
            $amenitiesArray = $this->parseAmenities($data['amenities'] ?? '');

            // ── Build payload ─────────────────────────────────────────
            $roomData = [
                'room_number'     => $data['room_number'],
                'room_type_id'    => $data['room_type_id'],
                'amenities'       => json_encode($amenitiesArray),
                'images'          => $imagesJson,
                'price_hourly'    => $data['price_hourly']    ?? 0,
                'price_overnight' => $data['price_overnight'] ?? 0,
                'price_day'       => $data['price_day']       ?? 0,
            ];

            try {
                $this->model->update($id, $roomData);
                setFlash("success", "Room updated successfully.");
            } catch (Exception $e) {
                setFlash("danger", "Error updating room: " . $e->getMessage());
            }

            header("Location: ../../../resources/views/admin/rooms.php");
            exit();
        }

        public function delete($id) {
            try {
                $this->model->delete($id);
                setFlash("success", "Room deleted successfully.");
            } catch (Exception $e) {
                setFlash("danger", "Error deleting room: " . $e->getMessage());
            }

            header("Location: ../../../resources/views/admin/rooms.php");
            exit();
        }

        private function parseAmenities($raw): array {
            if (is_array($raw)) {
                $raw = implode(',', $raw);
            }
            return array_values(array_filter(array_map('trim', explode(',', $raw))));
        }
    }

    // ─── Bootstrap: instantiate and dispatch ──────────────────────────────────────
    $roomsModel      = new RoomsModel($con);
    $roomsController = new RoomsController($roomsModel);

    // Expose $rooms so the view can use it
    $rooms = $roomsController->index();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['createRoom'])) {
            $roomsController->create([
                ...$_POST,                          // room_number, room_type_id, prices, amenities
                'images' => $_FILES['images'] ?? null
            ]);
        }

        if (isset($_POST['updateRoom'])) {
            $roomsController->update($_POST['id'], [
                ...$_POST,
                'images' => $_FILES['images'] ?? null
            ]);
        }

        if (isset($_POST['deleteRoom'])) {
            $roomsController->delete($_POST['id']);
        }
    }