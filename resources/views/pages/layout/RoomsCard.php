<?php
require_once __DIR__ . '/../../../../models/admin/RoomsModel.php';
require_once __DIR__ . '/../../../../db/config/config.php';

$roomsModel = new RoomsModel($con);
$allRooms = $roomsModel->index();
$rooms = array_slice($allRooms, 0, 3);
?>

    <section class="events-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8 mx-auto text-center">
                    <h6 class="text-gold text-uppercase fw-bold letter-spacing-2 mb-2">Our Available Rooms</h6>
                    <h2 class="display-5 fw-semibold text-dark mb-3">Choose Your Perfect Room</h2>
                    <p class="text-muted">Experience luxury and comfort in our carefully curated selection of rooms, each designed to provide an unforgettable stay.</p>
                    <div class="header-line mx-auto"></div>
                </div>
            </div>

            <div class="row g-4">
                <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $room): ?>
                        <?php
                            $images    = json_decode($room['images'], true);
                            $firstImage = (!empty($images) && is_array($images)) ? $images[0] : 'default-room.jpg';
                            $imagePath  = '../../../storage/rooms/' . $firstImage;

                            $amenities = json_decode($room['amenities'], true);
                            $amenities = (!empty($amenities) && is_array($amenities)) ? $amenities : [];
                        ?>

                        <!-- Room Card -->
                        <div class="col-md-6 col-lg-4">
                            <div class="hotel-event-card">
                                <div class="event-img-wrapper">
                                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($room['room_type']) ?>">
                                    <div class="event-category">Room <?= htmlspecialchars($room['room_number']) ?></div>
                                </div>
                                <div class="event-details text-center">
                                    <h3><?= htmlspecialchars($room['room_type']) ?></h3>
                                    <div class="event-meta mb-3">
                                        <span><i class="bi bi-moon-stars"></i> ₱<?= number_format($room['price_overnight'], 2) ?>/night</span>
                                        <span><i class="bi bi-sun"></i> ₱<?= number_format($room['price_day'], 2) ?>/day</span>
                                    </div>
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <button
                                            class="btn-hotel-outline"
                                            data-bs-toggle="modal"
                                            data-bs-target="#roomModal<?= $room['id'] ?>"
                                        >
                                            <i class="bi bi-eye"></i> View Full Details
                                        </button>
                                        <button
                                            class="btn-hotel-gold"
                                            data-bs-toggle="modal"
                                            data-bs-target="#bookModal<?= $room['id'] ?>"
                                        >
                                            <i class="bi bi-calendar-check"></i> Book Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- View Full Details Modal -->
                        <div class="modal fade" id="roomModal<?= $room['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body px-4 pb-4">
                                        <div class="row g-4">

                                            <!-- Image -->
                                            <div class="col-md-6">
                                                <img
                                                    src="<?= htmlspecialchars($imagePath) ?>"
                                                    alt="<?= htmlspecialchars($room['room_type']) ?>"
                                                    class="img-fluid rounded-3 w-100"
                                                    style="object-fit: cover; height: 280px;"
                                                >
                                                <!-- Thumbnail strip if multiple images -->
                                                <?php if (count($images) > 1): ?>
                                                    <div class="d-flex gap-2 mt-2 flex-wrap">
                                                        <?php foreach (array_slice($images, 1, 4) as $img): ?>
                                                            <img
                                                                src="../../../storage/rooms/<?= htmlspecialchars($img) ?>"
                                                                class="rounded-2"
                                                                style="width: 60px; height: 50px; object-fit: cover; cursor: pointer; border: 2px solid transparent;"
                                                                alt="Room image"
                                                            >
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Details -->
                                            <div class="col-md-6 d-flex flex-column justify-content-start">
                                                <span class="text-gold text-uppercase fw-bold small letter-spacing-2 mb-1">Room Details</span>
                                                <h4 class="fw-semibold mb-1"><?= htmlspecialchars($room['room_type']) ?></h4>
                                                <p class="text-muted small mb-3">Room No. <?= htmlspecialchars($room['room_number']) ?></p>

                                                <!-- Pricing -->
                                                <div class="mb-3 p-3 rounded-3" style="background: #f9f5ec;">
                                                    <p class="fw-bold small text-uppercase mb-2" style="color: #b89a4e;">Pricing</p>
                                                    <div class="d-flex justify-content-between small mb-1">
                                                        <span class="text-muted"><i class="bi bi-moon-stars me-1"></i> Overnight</span>
                                                        <strong>₱<?= number_format($room['price_overnight'], 2) ?></strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between small mb-1">
                                                        <span class="text-muted"><i class="bi bi-sun me-1"></i> Day Use</span>
                                                        <strong>₱<?= number_format($room['price_day'], 2) ?></strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between small">
                                                        <span class="text-muted"><i class="bi bi-clock me-1"></i> Hourly</span>
                                                        <strong>₱<?= number_format($room['price_hourly'], 2) ?></strong>
                                                    </div>
                                                </div>

                                                <!-- Amenities -->
                                                <?php if (!empty($amenities)): ?>
                                                    <p class="fw-bold small text-uppercase mb-2" style="color: #b89a4e;">Amenities</p>
                                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                                        <?php foreach ($amenities as $amenity): ?>
                                                            <span class="badge rounded-pill" style="background: #f0e8d0; color: #7a6030; font-size: 11px;">
                                                                <i class="bi bi-check-circle me-1"></i><?= htmlspecialchars($amenity) ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <button
                                                    class="btn-hotel-gold w-100 mt-auto"
                                                    data-bs-dismiss="modal"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#bookModal<?= $room['id'] ?>"
                                                >
                                                    <i class="bi bi-calendar-check me-1"></i> Book This Room
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Book Now Modal -->
                        <div class="modal fade" id="bookModal<?= $room['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title fw-semibold">
                                            <i class="bi bi-calendar-check text-gold me-2"></i> Book a Room
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body px-4">
                                        <p class="text-muted small mb-3">
                                            Booking: <strong><?= htmlspecialchars($room['room_type']) ?></strong> &mdash; Room <?= htmlspecialchars($room['room_number']) ?>
                                        </p>

                                        <input type="hidden" name="room_id" value="<?= $room['id'] ?>">

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Full Name</label>
                                            <input type="text" class="form-control" placeholder="Juan dela Cruz">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Contact Number</label>
                                            <input type="tel" class="form-control" placeholder="+63 9XX XXX XXXX">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Email Address</label>
                                            <input type="email" class="form-control" placeholder="you@email.com">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Booking Type</label>
                                            <select class="form-select" id="bookingType<?= $room['id'] ?>" onchange="updatePrice<?= $room['id'] ?>(this.value)">
                                                <option value="overnight">Overnight — ₱<?= number_format($room['price_overnight'], 2) ?></option>
                                                <option value="day">Day Use — ₱<?= number_format($room['price_day'], 2) ?></option>
                                                <option value="hourly">Hourly — ₱<?= number_format($room['price_hourly'], 2) ?>/hr</option>
                                            </select>
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label fw-semibold small">Check-in Date</label>
                                                <input type="date" class="form-control" min="<?= date('Y-m-d') ?>">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-semibold small">Check-out Date</label>
                                                <input type="date" class="form-control" min="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Special Requests <span class="text-muted fw-normal">(optional)</span></label>
                                            <textarea class="form-control" rows="3" placeholder="Any special arrangements..."></textarea>
                                        </div>

                                        <!-- Price Summary -->
                                        <div class="p-3 rounded-3 text-center" style="background: #f9f5ec;">
                                            <p class="small text-muted mb-1">Selected Rate</p>
                                            <p class="fw-bold mb-0" id="selectedPrice<?= $room['id'] ?>" style="color: #b89a4e; font-size: 1.1rem;">
                                                ₱<?= number_format($room['price_overnight'], 2) ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                                        <button type="button" class="btn btn-outline-secondary w-100 mb-2" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn-hotel-gold w-100">
                                            <i class="bi bi-send me-1"></i> Submit Booking
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Type Price Switcher -->
                        <script>
                            function updatePrice<?= $room['id'] ?>(type) {
                                const prices = {
                                    overnight: '₱<?= number_format($room['price_overnight'], 2) ?>',
                                    day:       '₱<?= number_format($room['price_day'], 2) ?>',
                                    hourly:    '₱<?= number_format($room['price_hourly'], 2) ?>/hr'
                                };
                                document.getElementById('selectedPrice<?= $room['id'] ?>').textContent = prices[type] || '';
                            }
                        </script>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No rooms available at the moment. Please check back later.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>