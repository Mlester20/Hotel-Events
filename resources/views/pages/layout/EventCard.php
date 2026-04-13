<section class="events-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-8 mx-auto text-center">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-2 mb-2">Memorable Occasions</h6>
                <h2 class="display-5 fw-semibold text-dark mb-3">Plan Your Grand Events</h2>
                <p class="text-muted">From intimate gatherings to grand celebrations, we provide the perfect venue and world-class service to make your milestones truly unforgettable.</p>
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
                                        <i class="bi bi-eye"></i> View Full Details
                                    </button>
                                    <button 
                                        class="btn-hotel-gold"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#reserveModal<?= $event['id'] ?>"
                                    >
                                        <i class="bi bi-calendar-check"></i> Reserve Now
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
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-semibold" id="reserveModalLabel<?= $event['id'] ?>">
                                        <i class="bi bi-calendar-check text-gold me-2"></i> Reserve Event
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-4">
                                    <p class="text-muted small mb-3">
                                        Reserving: <strong><?= htmlspecialchars($event['title']) ?></strong>
                                    </p>
                                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">

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
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label fw-semibold small">Preferred Date</label>
                                            <input type="date" class="form-control">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold small">Number of Guests</label>
                                            <input type="number" class="form-control" placeholder="e.g. 100" min="1" max="<?= $event['capacity'] ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Special Requests <span class="text-muted fw-normal">(optional)</span></label>
                                        <textarea class="form-control" rows="3" placeholder="Any special arrangements..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn-hotel-gold w-100">
                                        <i class="bi bi-send me-1"></i> Submit Reservation
                                    </button>
                                </div>
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