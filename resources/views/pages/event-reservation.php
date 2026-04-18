<?php
require_once __DIR__ . '/../../../middleware/auth.php';
allowOnly(['user']);

require_once __DIR__ . '/../../../db/config/config.php';
require_once __DIR__ . '/../../../models/users/EventReservationModel.php';

// Initialize model and get all events
$eventModel = new EventReservationModel($con);
$events = $eventModel->getAllEvents();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php require_once __DIR__ . '/../../../helpers/title.php'; ?></title>

    <link rel="stylesheet" href="../../../public/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

    <?php require_once 'layout/navbar.php'; ?>

    <!-- Event Reservation Section -->
    <main class="page-content">
        <section class="events-section py-5">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-md-8 mx-auto text-center">
                        <h6 class="text-gold text-uppercase fw-bold letter-spacing-2 mb-2">Memorable Occasions</h6>
                        <h2 class="display-5 fw-semibold text-dark mb-3">Reserve Your Perfect Event</h2>
                        <p class="text-muted">Browse our exclusive event packages and secure your date for an unforgettable celebration.</p>
                        <div class="header-line mx-auto"></div>
                    </div>
                </div>

                <div class="row g-4">
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $event): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="hotel-event-card">
                                    <div class="event-img-wrapper">
                                        <img 
                                            src="../../../storage/events/<?= htmlspecialchars($event['image']) ?>" 
                                            alt="<?= htmlspecialchars($event['title']) ?>"
                                        >
                                        <div class="event-category"><?= htmlspecialchars($event['location']) ?></div>
                                    </div>
                                    <div class="event-details text-center">
                                        <h3><?= htmlspecialchars($event['title']) ?></h3>
                                        <div class="event-meta mb-3">
                                            <span><i class="bi bi-people"></i> Up to <?= htmlspecialchars($event['capacity']) ?> Pax</span>
                                            <span><i class="bi bi-tag"></i> ₱<?= number_format($event['price'], 2) ?></span>
                                        </div>
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <button 
                                                class="btn-hotel-outline"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#eventModal<?= $event['id'] ?>"
                                            >
                                                <i class="bi bi-eye"></i> View Details
                                            </button>
                                            <button 
                                                class="btn-hotel-gold"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#reserveModal<?= $event['id'] ?>"
                                            >
                                                <i class="bi bi-calendar-check"></i> Reserve
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- View Full Details Modal -->
                            <div class="modal fade" id="eventModal<?= $event['id'] ?>" tabindex="-1" aria-labelledby="eventModalLabel<?= $event['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 pb-0">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-4 pb-4">
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <img 
                                                        src="../../../storage/events/<?= htmlspecialchars($event['image']) ?>" 
                                                        alt="<?= htmlspecialchars($event['title']) ?>"
                                                        class="img-fluid rounded-3 w-100"
                                                        style="object-fit: cover; height: 280px;"
                                                    >
                                                </div>
                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                    <span class="text-gold text-uppercase fw-bold small letter-spacing-2 mb-1">Event Package</span>
                                                    <h4 class="fw-semibold mb-3"><?= htmlspecialchars($event['title']) ?></h4>
                                                    <p class="text-muted mb-3"><?= htmlspecialchars($event['description']) ?></p>
                                                    <ul class="list-unstyled mb-4">
                                                        <li class="mb-2">
                                                            <i class="bi bi-geo-alt text-gold me-2"></i>
                                                            <strong>Venue:</strong> <?= htmlspecialchars($event['location']) ?>
                                                        </li>
                                                        <li class="mb-2">
                                                            <i class="bi bi-people text-gold me-2"></i>
                                                            <strong>Capacity:</strong> Up to <?= htmlspecialchars($event['capacity']) ?> Pax
                                                        </li>
                                                        <li class="mb-2">
                                                            <i class="bi bi-tag text-gold me-2"></i>
                                                            <strong>Starting at:</strong> ₱<?= number_format($event['price'], 2) ?>
                                                        </li>
                                                    </ul>
                                                    <button 
                                                        class="btn-hotel-gold w-100"
                                                        data-bs-dismiss="modal"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#reserveModal<?= $event['id'] ?>"
                                                    >
                                                        <i class="bi bi-calendar-check me-1"></i> Reserve This Event
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reserve Event Modal -->
                            <div class="modal fade" id="reserveModal<?= $event['id'] ?>" tabindex="-1" aria-labelledby="reserveModalLabel<?= $event['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title fw-semibold" id="reserveModalLabel<?= $event['id'] ?>">
                                                <i class="bi bi-calendar-check text-gold me-2"></i> Reserve Event
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form class="event-reservation-form" data-event-id="<?= $event['id'] ?>" data-event-price="<?= $event['price'] ?>">
                                            <div class="modal-body px-4">
                                                <p class="text-muted small mb-3">
                                                    Reserving: <strong><?= htmlspecialchars($event['title']) ?></strong>
                                                </p>
                                                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">

                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold small">Booking Start Date <span class="text-danger">*</span></label>
                                                        <input type="date" name="booking_date_start" class="form-control form-control-sm" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold small">Booking End Date</label>
                                                        <input type="date" name="booking_date_end" class="form-control form-control-sm">
                                                    </div>
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold small">Event Start Time <span class="text-danger">*</span></label>
                                                        <input type="time" name="start_time" class="form-control form-control-sm" value="14:00" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold small">Event End Time</label>
                                                        <input type="time" name="end_time" class="form-control form-control-sm">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small">Number of Guests <span class="text-danger">*</span></label>
                                                    <input type="number" name="number_of_guests" class="form-control form-control-sm" placeholder="e.g. 100" min="1" max="<?= $event['capacity'] ?>" required>
                                                    <small class="text-muted">Maximum: <?= $event['capacity'] ?> guests</small>
                                                </div>

                                                <div class="mb-3 p-2 bg-light rounded-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="fw-semibold small">Total Price:</span>
                                                        <span class="h6 mb-0 text-gold">₱<span class="total-price"><?= number_format($event['price'], 2) ?></span></span>
                                                    </div>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label fw-semibold small">Special Requests <span class="text-muted fw-normal">(optional)</span></label>
                                                    <textarea name="special_requests" class="form-control form-control-sm" rows="2" placeholder="Any special arrangements..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-2 px-4 pb-3">
                                                <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn-hotel-gold flex-grow-1" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                                    <i class="bi bi-send me-1"></i> Confirm
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">No events available at the moment. Please check back later.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../../public/js/home/event-reservation.js"></script>

</body>
</html>