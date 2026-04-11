<?php

require_once __DIR__ . '/../../../controllers/admin/UserController.php';
require_once __DIR__ . '/../../../helpers/message.php';
require_once __DIR__ . '/../../../middleware/auth.php';
allowOnly(['admin']);
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

    <?php showFlash(); ?>

    <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoom">
            Add Account(Front Desk)
        </button>
    </div>

    <!-- modal -->
    <div class="modal fade" id="addRoom" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Add Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../../controllers/admin/UserController.php" method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <input type="hidden" name="role" value="front_desk">
                        <button type="submit" name="addUser" class="btn btn-primary">Add Front Desk Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>     

    <div class="card mt-5">
        <h5 class="card-header">Users</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Users Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <?php foreach($users as $user): ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo $user['full_name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <!-- <td>
                        
                            <form action="../../../controllers/admin/UserController.php" method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                
                                <button 
                                    type="submit" 
                                    name="deleteUser" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td> -->
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
</body>
</html>