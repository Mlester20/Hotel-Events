<?php
require_once __DIR__ . '/../../../controllers/admin/HomeDescriptionController.php';
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHomeDescriptionModal">
            Add Home Description
        </button>
    </div>

    <!-- modal -->
    <div class="modal fade" id="addHomeDescriptionModal" tabindex="-1" aria-labelledby="addHomeDescriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHomeDescriptionModalLabel">Add Home Description</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../../../controllers/admin/HomeDescriptionController.php">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter home description title">
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="3" placeholder="Enter home description content"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="createHomeDescription">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- edit modal -->
    <div class="modal fade" id="editHomeDescriptionModal" tabindex="-1" aria-labelledby="editHomeDescriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoomTypeModalLabel">Edit Room Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../../../controllers/admin/HomeDescriptionController.php">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-3">
                            <label for="editRoomType" class="form-label">Room Type Title</label>
                            <input type="text" class="form-control" id="editTitle" name="title" placeholder="Enter room type title">
                        </div>
                        <div class="mb-3">
                            <label for="editDetails" class="form-label">Details</label>
                            <textarea class="form-control" id="editContent" name="content" rows="3" placeholder="Enter room type details"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="editHomeDescription">Save Changes</button>
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
                        <th>Title</th>
                        <th>Content</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <?php foreach($home_descriptions as $home_description): ?>
                    <tr>
                        <td><?php echo $home_description['id']; ?></td>
                        <td><?php echo $home_description['title']; ?></td>
                        <td><?php echo $home_description['content']; ?></td>
                        <td>
                            <?php if($home_description['is_active'] == 1): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- edit -->
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editHomeDescriptionModal"
                                data-id="<?php echo $home_description['id']; ?>"
                                data-title="<?php echo htmlspecialchars($home_description['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-content="<?php echo htmlspecialchars($home_description['content'], ENT_QUOTES, 'UTF-8'); ?>">
                                Edit
                            </button>

                            <!-- delete -->
                            <form action="../../../controllers/admin/HomeDescriptionController.php" method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $home_description['id']; ?>">
                                
                                <button 
                                    type="submit" 
                                    name="deleteHomeDescription" 
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
    <script src="../../../public/js/admin/home-description.js"></script>
</body>
</html>