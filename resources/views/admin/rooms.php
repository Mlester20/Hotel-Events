<?php
require_once __DIR__ . '/../../../controllers/admin/RoomsController.php';
require_once __DIR__ . '/../../../models/admin/RoomsModel.php';
require_once __DIR__ . '/../../../helpers/message.php';
require_once __DIR__ . '/../../../middleware/auth.php';
allowOnly(['admin']); // allow only admin to access this page

?>

<!doctype html>
<html lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="../../../public/assets/"
  data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title><?php require_once __DIR__ . '/../../../helpers/title.php'; ?></title>
  <link rel="icon" type="image/x-icon" href="../../../public/assets/img/favicon/favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../public/assets/vendor/fonts/iconify-icons.css" />
  <link rel="stylesheet" href="../../../public/assets/vendor/libs/node-waves/node-waves.css" />
  <link rel="stylesheet" href="../../../public/assets/vendor/css/core.css" />
  <link rel="stylesheet" href="../../../public/assets/css/demo.css" />
  <link rel="stylesheet" href="../../../public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="../../../public/assets/vendor/libs/apex-charts/apex-charts.css" />
  <script src="../../../public/assets/vendor/js/helpers.js"></script>
  <script src="../../../public/assets/js/config.js"></script>
</head>
<body>

    <?php require_once 'layout/sidebar.php'; ?>
    <?php require_once 'layout/topbar.php'; ?>

    <!-- session messages -->
    <?php showFlash(); ?>

    <!-- button to triggered modal -->
    <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoom">
            Add Room
        </button>
    </div>

    <!-- modal -->
    <div class="modal fade" id="addRoom" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="addRoomModalLabel">Add Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <form method="POST" action="/controllers/admin/RoomsController.php" enctype="multipart/form-data">
                        <div class="row g-2">
                            <!-- Room Number -->
                            <div class="col-6">
                                <label for="roomNumber" class="form-label mb-1">Room Number</label>
                                <input type="text" class="form-control form-control-sm" id="roomNumber" name="room_number" placeholder="e.g. 101" required>
                            </div>
                            <!-- Room Type -->
                            <div class="col-6">
                                <label for="roomType" class="form-label mb-1">Room Type</label>
                                <select class="form-control form-control-sm" id="roomType" name="room_type_id" required>
                                    <option value="">Select Type</option>
                                    <?php
                                    $roomTypes = $roomsModel->roomTypes();
                                    foreach ($roomTypes as $type) {
                                        echo "<option value='{$type['id']}'>{$type['title']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- Amenities -->
                            <div class="col-12">
                                <label for="amenities" class="form-label mb-1">Amenities</label>
                                <textarea class="form-control form-control-sm" id="amenities" name="amenities" rows="2"
                                    placeholder="WiFi, TV, Air Conditioning" required></textarea>
                            </div>
                            <!-- Images -->
                            <div class="col-12">
                                <label for="images" class="form-label mb-1">Images</label>
                                <input type="file" class="form-control form-control-sm" id="images" name="images[]" multiple required>
                            </div>
                            <!-- Prices (3 columns) -->
                            <div class="col-4">
                                <label for="price_hourly" class="form-label mb-1">Hourly (₱)</label>
                                <input type="number" class="form-control form-control-sm" id="price_hourly" name="price_hourly" placeholder="0.00" min="0" step="0.01" required>
                            </div>
                            <div class="col-4">
                                <label for="price_overnight" class="form-label mb-1">Overnight (₱)</label>
                                <input type="number" class="form-control form-control-sm" id="price_overnight" name="price_overnight" placeholder="0.00" min="0" step="0.01" required>
                            </div>
                            <div class="col-4">
                                <label for="price_day" class="form-label mb-1">Day (₱)</label>
                                <input type="number" class="form-control form-control-sm" id="price_day" name="price_day" placeholder="0.00" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-primary" name="createRoom">Save Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- update modal -->
    <div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="editRoomModalLabel">Edit Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <form method="POST" action="/controllers/admin/RoomsController.php" enctype="multipart/form-data">
                        <input type="hidden" name="id"              id="editRoomId">
                        <input type="hidden" name="existing_images" id="editExistingImages">

                        <div class="row g-2">
                            <!-- Row 1: Room Number + Room Type -->
                            <div class="col-6">
                                <label for="editRoomNumber" class="form-label mb-1">Room Number</label>
                                <input type="text" class="form-control form-control-sm"
                                    id="editRoomNumber" name="room_number"
                                    placeholder="e.g. 101" required>
                            </div>
                            <div class="col-6">
                                <label for="editRoomType" class="form-label mb-1">Room Type</label>
                                <select class="form-control form-control-sm" id="editRoomType" name="room_type_id" required>
                                    <option value="">Select Type</option>
                                    <?php foreach($roomTypes as $type): ?>
                                        <option value="<?php echo $type['id']; ?>"><?php echo $type['title']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Row 2: Amenities — plain name, NOT amenities[] -->
                            <div class="col-12">
                                <label for="editAmenities" class="form-label mb-1">Amenities</label>
                                <textarea class="form-control form-control-sm" id="editAmenities"
                                        name="amenities" rows="2"
                                        placeholder="WiFi, TV, Air Conditioning"></textarea>
                            </div>

                            <!-- Row 3: Prices in 3 equal columns -->
                            <div class="col-4">
                                <label for="editPriceHourly" class="form-label mb-1">Hourly (₱)</label>
                                <input type="number" class="form-control form-control-sm"
                                    id="editPriceHourly" name="price_hourly"
                                    placeholder="0.00" min="0" step="0.01" required>
                            </div>
                            <div class="col-4">
                                <label for="editPriceOvernight" class="form-label mb-1">Overnight (₱)</label>
                                <input type="number" class="form-control form-control-sm"
                                    id="editPriceOvernight" name="price_overnight"
                                    placeholder="0.00" min="0" step="0.01" required>
                            </div>
                            <div class="col-4">
                                <label for="editPriceDay" class="form-label mb-1">Day (₱)</label>
                                <input type="number" class="form-control form-control-sm"
                                    id="editPriceDay" name="price_day"
                                    placeholder="0.00" min="0" step="0.01" required>
                            </div>

                            <!-- Row 4: Images -->
                            <div class="col-12">
                                <label class="form-label mb-1">Current Images</label>
                                <div id="editCurrentImages" class="d-flex flex-wrap gap-1 mb-1"></div>
                                <label for="editImages" class="form-label mb-1">
                                    Upload New <small class="text-muted">(leave blank to keep current)</small>
                                </label>
                                <input type="file" class="form-control form-control-sm"
                                    id="editImages" name="images[]" multiple>
                            </div>
                        </div>

                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-primary" name="updateRoom">Update Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewRoomModal" tabindex="-1" aria-labelledby="viewRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="viewRoomModalLabel">Room Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted d-block">Room Number</small>
                            <strong id="viewRoomNumber">—</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Room Title</small>
                            <strong id="viewRoomTitle">—</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Amenities</small>
                            <span id="viewAmenities">—</span>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Hourly</small>
                            <strong id="viewPriceHourly">—</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Overnight</small>
                            <strong id="viewPriceOvernight">—</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Day</small>
                            <strong id="viewPriceDay">—</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block mb-1">Images</small>
                            <div id="viewImages" class="d-flex flex-wrap gap-1"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- tables -->
    <div class="card mt-4">
        <h5 class="card-header">Room List</h5>
        <div class="table-responsive nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Amenities</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="roomsTableBody">
                    <?php foreach ($rooms as $room): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                            <td><?php echo htmlspecialchars($room['room_type']); ?></td>
                            <td><?php echo htmlspecialchars(implode(', ', json_decode($room['amenities']))); ?></td>
                            <td>
                                <!-- Edit button -->
                                <button
                                    class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editRoomModal"
                                    onclick='editRoom(
                                        <?php echo $room["id"]; ?>,
                                        <?php echo json_encode($room["room_number"]); ?>,
                                        <?php echo $room["room_type_id"]; ?>,
                                        <?php echo json_encode(json_decode($room["amenities"], true)); ?>,
                                        <?php echo json_encode(json_decode($room["images"], true)); ?>,
                                        <?php echo json_encode($room["price_hourly"]); ?>,
                                        <?php echo json_encode($room["price_overnight"]); ?>,
                                        <?php echo json_encode($room["price_day"]); ?>
                                    )'>
                                    Edit
                                </button>

                                <!-- View button -->
                                <button
                                    class="btn btn-sm btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewRoomModal"
                                    onclick='viewRoom(
                                        <?php echo $room["id"]; ?>,
                                        <?php echo json_encode($room["room_number"]); ?>,
                                        <?php echo json_encode($room["room_type"]); ?>,
                                        <?php echo json_encode(json_decode($room["amenities"], true)); ?>,
                                        <?php echo json_encode(json_decode($room["images"], true)); ?>,
                                        <?php echo json_encode($room["price_hourly"]); ?>,
                                        <?php echo json_encode($room["price_overnight"]); ?>,
                                        <?php echo json_encode($room["price_day"]); ?>
                                    )'>
                                    View
                                </button>

                                <!-- delete -->
                                <form action="../../../controllers/admin/RoomsController.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $room['id']; ?>">
                                    
                                    <button 
                                        name="deleteRoom" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


    <?php require_once 'layout/footer.php'; ?>

    <script src="../../../public/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../../public/assets/vendor/libs/popper/popper.js"></script>
    <script src="../../../public/assets/vendor/js/bootstrap.js"></script>
    <script src="../../../public/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="../../../public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../../public/assets/vendor/js/menu.js"></script>
    <script src="../../../public/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="../../../public/assets/js/main.js"></script>
    <script src="../../../public/assets/js/dashboards-analytics.js"></script>
    <script async="async" defer="defer" src="https://buttons.github.io/buttons.js"></script>
    <script src="../../../public/js/admin/rooms.js"></script>
</body>
</html>