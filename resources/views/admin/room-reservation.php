<?php
require_once __DIR__ . '/../../../controllers/admin/ReservationsController.php';
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



    <div class="card">
      <h5 class="card-header">Room Reservations</h5>
      <div class="table-responsive nowrap">
        <table class="table">
          <thead>
            <tr>
              <th>Guest Name</th>
              <th>Room</th>
              <th>Type</th>
              <th>Check-In</th>
              <th>Check-Out</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($reservations)): ?>
              <?php foreach ($reservations as $res): ?>
                <tr>
                  <td>
                    <strong><?php echo htmlspecialchars($res['guess_name']); ?></strong><br>
                    <small><?php echo htmlspecialchars($res['guest_email']); ?></small>
                  </td>
                  <td>Room <?php echo $res['room_number']; ?></td>
                  <td><?php echo $res['room_type']; ?></td>
                  <td><?php echo date('M d, Y', strtotime($res['check_in_date'])); ?></td>
                  <td><?php echo date('M d, Y', strtotime($res['check_out_date'])); ?></td>
                  <td>
                    <span class="badge <?php echo ($res['booking_status'] == 'confirmed') ? 'bg-success' : 'bg-warning'; ?>">
                      <?php echo ucfirst($res['booking_status']); ?>
                    </span>
                  </td>
                  <td>
                    <a href="view-details.php?id=<?php echo $res['booking_id']; ?>" class="btn btn-sm btn-info">
                      View Details
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center">No reservations found.</td>
              </tr>
            <?php endif; ?>
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
    <script src="../../../public/js/admin/room-types.js"></script>
</body>
</html>