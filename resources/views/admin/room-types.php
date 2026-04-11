<?php
require_once __DIR__ . '/../../../controllers/admin/RoomTypesController.php';
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomTypeModal">
            Add Room Type
        </button>
    </div>

    <!-- modal -->
    <div class="modal fade" id="addRoomTypeModal" tabindex="-1" aria-labelledby="addRoomTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoomTypeModalLabel">Add Room Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../../../controllers/admin/RoomTypesController.php">
                        <div class="mb-3">
                            <label for="roomType" class="form-label">Room Type Title</label>
                            <input type="text" class="form-control" id="roomType" name="title" placeholder="Enter room type title">
                        </div>
                        <div class="mb-3">
                            <label for="Details" class="form-label">Details</label>
                            <textarea class="form-control" id="Details" name="details" rows="3" placeholder="Enter room type details"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="createRoomType">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- edit modal -->
    <div class="modal fade" id="editRoomTypeModal" tabindex="-1" aria-labelledby="editRoomTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoomTypeModalLabel">Edit Room Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../../../controllers/admin/RoomTypesController.php">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-3">
                            <label for="editRoomType" class="form-label">Room Type Title</label>
                            <input type="text" class="form-control" id="editRoomType" name="title" placeholder="Enter room type title">
                        </div>
                        <div class="mb-3">
                            <label for="editDetails" class="form-label">Details</label>
                            <textarea class="form-control" id="editDetails" name="details" rows="3" placeholder="Enter room type details"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <h5 class="card-header">Room Types</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room Type</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <?php foreach($roomTypes as $roomType): ?>
                    <tr>
                        <td><?php echo $roomType['id']; ?></td>
                        <td><?php echo $roomType['title']; ?></td>
                        <td><?php echo $roomType['details']; ?></td>
                        <td>
                            <!-- edit -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editRoomTypeModal" onclick="editRoomType(<?php echo $roomType['id']; ?>, '<?php echo addslashes($roomType['title']); ?>', '<?php echo addslashes($roomType['details']); ?>')">Edit</button>

                            <!-- delete -->
                            <form action="../../../controllers/admin/RoomTypesController.php" method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $roomType['id']; ?>">
                                
                                <button 
                                    type="submit" 
                                    name="deleteRoomType" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
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
    <script src="../../../public/js/admin/room-types.js"></script>
</body>
</html>