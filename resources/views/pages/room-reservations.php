<?php

require_once __DIR__ . '/../../../controllers/users/ReservationsController.php';
require_once __DIR__ . '/../../../helpers/message.php';
require_once __DIR__ . '/../../../middleware/auth.php';
allowOnly(['user']); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php require_once __DIR__ . '/../../../helpers/title.php'; ?> </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/home.css">
</head>
<body>
    
    <?php require_once __DIR__ . '/layout/navbar.php'; ?>

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h5 class="card-header text-center">My Reservations</h5>
                
                <?php if(empty($reservations)): ?>
                    <div class="alert alert-info text-center mt-4" role="alert">
                        <i class="bi bi-info-circle"></i> You have no reservations yet.
                    </div>
                <?php else: ?>
                    <div class="row mt-4 g-4">
                        <?php foreach($reservations as $reservation): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm cursor-pointer reservation-card" 
                                     data-bs-toggle="modal" 
                                     data-bs-target="#reservationModal"
                                     onclick="loadReservationDetails(<?php echo htmlspecialchars(json_encode($reservation)); ?>)">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h6 class="card-title mb-0">Booking #<?php echo htmlspecialchars($reservation['booking_id']); ?></h6>
                                            <span class="badge bg-<?php echo ($reservation['status'] === 'confirmed') ? 'success' : (($reservation['status'] === 'pending') ? 'warning' : 'danger'); ?>">
                                                <?php echo ucfirst(htmlspecialchars($reservation['status'])); ?>
                                            </span>
                                        </div>

                                        <div class="mb-3">
                                            <p class="text-muted mb-1"><small>Room</small></p>
                                            <h5 class="mb-0"><?php echo htmlspecialchars($reservation['room_number']); ?></h5>
                                            <small class="text-muted"><?php echo htmlspecialchars($reservation['room_type']); ?></small>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <p class="text-muted mb-1"><small>Check-In</small></p>
                                                <p class="mb-0"><?php echo htmlspecialchars($reservation['check_in_date']); ?></p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1"><small>Check-Out</small></p>
                                                <p class="mb-0"><?php echo htmlspecialchars($reservation['check_out_date']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light border-0">
                                        <small class="text-muted"><i class="bi bi-arrow-right"></i> Click for details</small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Reservation Details Modal -->
                <div class="modal fade" id="reservationModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-bottom">
                                <h5 class="modal-title">Reservation Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0">
                                <div class="row g-0">
                                    <div class="col-md-5">
                                        <img id="modal-image" src="" class="w-100 h-100" alt="Room Image" style="height: 300px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-7 p-4">
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <p class="text-muted mb-1"><small>Booking ID</small></p>
                                                <p id="modal-booking-id" class="fw-bold"></p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1"><small>Status</small></p>
                                                <p id="modal-status"></p>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <p class="text-muted mb-1"><small>Room Number</small></p>
                                                <p id="modal-room-number" class="fw-bold"></p>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <p class="text-muted mb-1"><small>Room Type</small></p>
                                                <p id="modal-room-type" class="fw-bold"></p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <p class="text-muted mb-1"><small>Check-In Date</small></p>
                                                <p id="modal-check-in" class="fw-bold"></p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1"><small>Check-Out Date</small></p>
                                                <p id="modal-check-out" class="fw-bold"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-top">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Download Receipt</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        .reservation-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .reservation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
        }
    </style>

    <script>
        function loadReservationDetails(reservation) {
            document.getElementById('modal-booking-id').textContent = reservation.booking_id;
            document.getElementById('modal-room-number').textContent = reservation.room_number;
            document.getElementById('modal-room-type').textContent = reservation.room_type;
            document.getElementById('modal-check-in').textContent = reservation.check_in_date;
            document.getElementById('modal-check-out').textContent = reservation.check_out_date;
            
            const statusBadgeClass = reservation.status === 'confirmed' ? 'success' : (reservation.status === 'pending' ? 'warning' : 'danger');
            document.getElementById('modal-status').innerHTML = `<span class="badge bg-${statusBadgeClass}">${reservation.status.charAt(0).toUpperCase() + reservation.status.slice(1)}</span>`;
            
            // Load image - get first image from JSON array with storage path
            const images = JSON.parse(reservation.images || '[]');
            const firstImage = images.length > 0 ? '../../../storage/rooms/' + images[0] : '../../../public/assets/img/elements/default-room.jpg';
            document.getElementById('modal-image').src = firstImage;
        }
    </script>
</body>
</html>