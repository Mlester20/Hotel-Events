<?php 

require_once __DIR__ . '/../../../controllers/admin/EventBookingsController.php';
require_once __DIR__ . '/../../../helpers/message.php';
require_once __DIR__ . '/../../../middleware/auth.php';
allowOnly(['admin']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>
    
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>
    <?php require_once __DIR__ . '/layout/topbar.php'; ?>

    <?php showFlash(); ?>

    <!-- view modal -->
    <div class="modal fade" id="viewReservationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Event Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <small class="text-muted">Booking ID</small>
                            <p id="view_booking_id" class="fw-bold"></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Status</small>
                            <p id="view_status"></p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <small class="text-muted">Event</small>
                            <p id="view_event_title" class="fw-bold"></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Location</small>
                            <p id="view_event_location"></p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <small class="text-muted">Customer Name</small>
                            <p id="view_user_name"></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Customer Email</small>
                            <p id="view_user_email"></p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <small class="text-muted">Number of Guests</small>
                            <p id="view_guests"></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Total Price</small>
                            <p id="view_total_price"></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Payment Status</small>
                            <p id="view_payment_status"></p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <small class="text-muted">Date Start</small>
                            <p id="view_date_start"></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Date End</small>
                            <p id="view_date_end"></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Start Time</small>
                            <p id="view_start_time"></p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <small class="text-muted">Special Requests</small>
                            <p id="view_special_requests"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">Manage Event Bookings</h5>
        <div class="table-responsive nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Customer Name</th>
                        <th>Number of Guests</th>
                        <th>Status</th>
                        <th>actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($event_bookings)) : ?>
                        <?php foreach ($event_bookings as $booking) : ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['event_title']) ?></td>
                                <td><?= htmlspecialchars($booking['user_name']) ?></td>
                                <td><?= htmlspecialchars($booking['number_of_guests']) ?></td>
                                <td>
                                    <span class="badge 
                                        <?php
                                            echo match($booking['status']) {
                                                'confirmed'  => 'bg-success',
                                                'pending'    => 'bg-warning text-dark',
                                                'cancelled'  => 'bg-danger',
                                                'completed'  => 'bg-secondary',
                                                default      => 'bg-light text-dark'
                                            };
                                        ?>">
                                        <?= ucfirst($booking['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" 
                                        onclick="viewReservation(
                                            '<?= $booking['id'] ?>',
                                            '<?= htmlspecialchars($booking['event_booking_id']) ?>',
                                            '<?= htmlspecialchars($booking['event_title']) ?>',
                                            '<?= htmlspecialchars($booking['event_location']) ?>',
                                            '<?= htmlspecialchars($booking['user_name']) ?>',
                                            '<?= htmlspecialchars($booking['user_email']) ?>',
                                            '<?= $booking['number_of_guests'] ?>',
                                            '<?= $booking['total_price'] ?>',
                                            '<?= $booking['booking_date_start'] ?>',
                                            '<?= $booking['booking_date_end'] ?>',
                                            '<?= $booking['start_time'] ?>',
                                            '<?= $booking['status'] ?>',
                                            '<?= $booking['payment_status'] ?>',
                                            '<?= htmlspecialchars($booking['special_requests'] ?? 'N/A') ?>'
                                        )"
                                        data-bs-toggle="modal" data-bs-target="#viewReservationModal">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No event bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

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
    <script src="../../../public/js/admin/event-reservation.js"></script>
</body>
</html>