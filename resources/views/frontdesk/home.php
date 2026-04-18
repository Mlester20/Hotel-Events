<?php
/**
 * views/frontdesk/pages/reservations.php
 */
session_start();

require_once __DIR__ . '/../../../controllers/frontdesk/ReservationsController.php';
require_once __DIR__ . '/../../../db/config/config.php';
require_once __DIR__ . '/../../../middleware/auth.php';
allowOnly(['front_desk', 'admin']);

$controller   = new ReservationsController($con);
$reservations = $controller->getRoomBookings();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reservations — Front Desk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="../../../public/css/home.css">
  <link rel="stylesheet" href="../../../public/css/reservations.css">
</head>
<body>

  <?php require_once __DIR__ . '/layout/navbar.php'; ?>

  <div id="toast-box"></div>

  <div class="page-wrapper">

    <!-- Header -->
    <div class="page-header">
      <div>
        <h1>Reservations</h1>
        <p>Room bookings — manage status &amp; payment in real time</p>
      </div>
      <span class="badge-pill s-confirmed" style="font-size:.8rem;">
        <?= count($reservations) ?> booking<?= count($reservations) !== 1 ? 's' : '' ?>
      </span>
    </div>

    <!-- Filters & Search -->
    <div class="filters">
      <button class="filter-btn active" data-filter="all">All</button>
      <button class="filter-btn" data-filter="pending">Pending</button>
      <button class="filter-btn" data-filter="confirmed">Confirmed</button>
      <button class="filter-btn" data-filter="completed">Completed</button>
      <button class="filter-btn" data-filter="cancelled">Cancelled</button>

      <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input id="searchInput" type="text" placeholder="Search guest or booking ID…" />
      </div>
    </div>

    <!-- Table -->
    <div class="table-card">
      <table class="res-table" id="reservationsTable">
        <thead>
          <tr>
            <th>Booking ID</th>
            <th>Guest</th>
            <th>Room</th>
            <th>Check-in / Out</th>
            <th>Type</th>
            <th>Total</th>
            <th>Booking Status</th>
            <th>Payment</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($reservations)): ?>
          <tr>
            <td colspan="8">
              <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                No bookings found.
              </div>
            </td>
          </tr>
          <?php else: ?>
            <?php foreach ($reservations as $r):
              $bStatus  = htmlspecialchars($r['booking_status']);
              $pStatus  = htmlspecialchars($r['payment_status']);
              $bId      = htmlspecialchars($r['booking_id']);
            ?>
            <tr data-status="<?= $bStatus ?>" data-booking-id="<?= $bId ?>">

              <!-- Booking ID -->
              <td><span class="booking-id"><?= $bId ?></span></td>

              <!-- Guest -->
              <td>
                <div class="guest-name"><?= htmlspecialchars($r['guest_name'] ?? '—') ?></div>
                <div class="guest-email"><?= htmlspecialchars($r['guest_email'] ?? '') ?></div>
              </td>

              <!-- Room -->
              <td>
                <strong>Room <?= htmlspecialchars($r['room_number'] ?? '—') ?></strong>
                <div class="guest-email"><?= htmlspecialchars($r['room_type'] ?? '') ?></div>
              </td>

              <!-- Dates -->
              <td>
                <div class="date-range">
                  <span class="label">In</span>
                  <?= htmlspecialchars($r['check_in_date'] ?? '—') ?>
                  <?php if (!empty($r['check_in_time'])): ?>
                    <small style="color:var(--muted);"><?= substr($r['check_in_time'], 0, 5) ?></small>
                  <?php endif; ?>
                  <span class="label" style="margin-top:.3rem;">Out</span>
                  <?= htmlspecialchars($r['check_out_date'] ?? '—') ?>
                  <?php if (!empty($r['check_out_time'])): ?>
                    <small style="color:var(--muted);"><?= substr($r['check_out_time'], 0, 5) ?></small>
                  <?php endif; ?>
                </div>
              </td>

              <!-- Type -->
              <td>
                <?php
                  $typeLabels = [
                    'per_hour'    => 'Hourly',
                    'per_day'     => 'Day Use',
                    'overnight'   => 'Overnight',
                  ];
                  $typeLabel = $typeLabels[$r['booking_type']] ?? ucfirst($r['booking_type']);
                ?>
                <span class="badge-pill" style="background:var(--accent-dim);color:var(--accent);">
                  <?= htmlspecialchars($typeLabel) ?>
                </span>
              </td>

              <!-- Total -->
              <td class="price">
                ₱<?= number_format((float)($r['total_price'] ?? 0), 2) ?>
              </td>

              <!-- Booking Status dropdown -->
              <td>
                <select
                  class="inline-select status-select"
                  data-booking-id="<?= $bId ?>"
                  data-action="update_status"
                  data-original="<?= $bStatus ?>"
                >
                  <option value="pending"   <?= $bStatus === 'pending'   ? 'selected' : '' ?>>Pending</option>
                  <option value="confirmed" <?= $bStatus === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                  <option value="completed" <?= $bStatus === 'completed' ? 'selected' : '' ?>>Completed</option>
                  <option value="cancelled" <?= $bStatus === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
              </td>

              <!-- Payment Status dropdown -->
              <td>
                <select
                  class="inline-select payment-select"
                  data-booking-id="<?= $bId ?>"
                  data-action="update_payment"
                  data-original="<?= $pStatus ?>"
                >
                  <option value="unpaid"         <?= $pStatus === 'unpaid'         ? 'selected' : '' ?>>Unpaid</option>
                  <option value="partially_paid" <?= $pStatus === 'partially_paid' ? 'selected' : '' ?>>Partial</option>
                  <option value="paid"           <?= $pStatus === 'paid'           ? 'selected' : '' ?>>Paid</option>
                </select>
              </td>

            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div><!-- /table-card -->

  </div><!-- /page-wrapper -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../../public//js/front-desk/reservations.js"></script>

</body>
</html>