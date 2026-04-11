<?php

require_once __DIR__ . '/../../../middleware/auth.php';
allowOnly(['user']);
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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/reservations-booking.css">
</head>
<body>
    
    <?php require_once 'layout/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center text-dark fw-bold mb-2">Room Reservations</h2>
                <p class="text-center text-muted">Find and book the perfect room for your stay</p>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="filter-card">
                    <form id="filterForm" class="row g-3">
                        <!-- Check-in Date -->
                        <div class="col-md-3">
                            <label for="checkInDate" class="form-label fw-semibold">Check-in Date</label>
                            <input type="date" class="form-control" id="checkInDate" name="check_in_date" required>
                            <small class="text-muted">Select your check-in date</small>
                        </div>

                        <!-- Check-out Date -->
                        <div class="col-md-3">
                            <label for="checkOutDate" class="form-label fw-semibold">Check-out Date</label>
                            <input type="date" class="form-control" id="checkOutDate" name="check_out_date" required>
                            <small class="text-muted">Select your check-out date</small>
                        </div>

                        <!-- Booking Type -->
                        <div class="col-md-2">
                            <label for="bookingType" class="form-label fw-semibold">Stay Type</label>
                            <select class="form-select" id="bookingType" name="booking_type">
                                <option value="per_day">Per Night</option>
                                <option value="per_hour">Per Hour</option>
                                <option value="overnight">Overnight</option>
                            </select>
                            <small class="text-muted">Select booking type</small>
                        </div>

                        <!-- Room Type -->
                        <div class="col-md-2">
                            <label for="roomTypeSelect" class="form-label fw-semibold">Room Type</label>
                            <select class="form-select" id="roomTypeSelect" name="room_type_id">
                                <option value="">All Room Types</option>
                            </select>
                            <small class="text-muted">Filter by type</small>
                        </div>

                        <!-- Search Button -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Rooms Display Section -->
        <div class="row" id="roomsContainer">
            <div class="col-12 text-center py-5">
                <p class="text-muted">Select your dates and click search to view available rooms</p>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Confirm Your Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Selected Room Details -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Room Details</h6>
                        <div id="selectedRoomDetails"></div>
                    </div>

                    <!-- Booking Details -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Booking Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="booking-info-item">
                                    <label class="text-muted small">Check-in</label>
                                    <p class="fw-semibold" id="modalCheckIn">-</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="booking-info-item">
                                    <label class="text-muted small">Check-out</label>
                                    <p class="fw-semibold" id="modalCheckOut">-</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="booking-info-item">
                                    <label class="text-muted small">Total Hours</label>
                                    <p class="fw-semibold" id="modalTotalHours">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Type Selection -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Select Pricing Type</h6>
                        <div class="pricing-options">
                            <div class="form-check pricing-option">
                                <input class="form-check-input" type="radio" name="pricingType" id="pricingDaily" value="daily" checked>
                                <label class="form-check-label w-100" for="pricingDaily">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Per Night</span>
                                        <span class="price-badge" id="priceDaily">₱0.00</span>
                                    </div>
                                </label>
                            </div>

                            <div class="form-check pricing-option">
                                <input class="form-check-input" type="radio" name="pricingType" id="pricingOvernight" value="overnight">
                                <label class="form-check-label w-100" for="pricingOvernight">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Overnight (8 hours)</span>
                                        <span class="price-badge" id="priceOvernight">₱0.00</span>
                                    </div>
                                </label>
                            </div>

                            <div class="form-check pricing-option">
                                <input class="form-check-input" type="radio" name="pricingType" id="pricingHourly" value="hourly">
                                <label class="form-check-label w-100" for="pricingHourly">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Per Hour</span>
                                        <span class="price-badge" id="priceHourly">₱0.00</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Hourly Pricing Time Selection (Only shown for hourly) -->
                    <div class="mb-4" id="hourlyTimeSection" style="display: none;">
                        <h6 class="fw-bold mb-3">Select Check-in and Check-out Times</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="modalCheckInTime" class="form-label fw-semibold">Check-in Time</label>
                                <input type="time" class="form-control" id="modalCheckInTime" value="14:00">
                            </div>
                            <div class="col-md-6">
                                <label for="modalCheckOutTime" class="form-label fw-semibold">Check-out Time</label>
                                <input type="time" class="form-control" id="modalCheckOutTime" value="15:00">
                            </div>
                        </div>
                    </div>

                    <!-- Total Price -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Total Price:</span>
                            <span class="h5 text-primary mb-0" id="totalPrice">₱0.00</span>
                        </div>
                    </div>

                    <!-- Special Requests (Optional) -->
                    <div class="mb-4">
                        <label for="specialRequests" class="form-label fw-semibold">Special Requests (Optional)</label>
                        <textarea class="form-control" id="specialRequests" rows="3" placeholder="Any special requests for your stay?"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-lg" id="confirmBookingBtn">
                        <i class="bi bi-check-circle"></i> Confirm Booking
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner (Hidden by default) -->
    <div id="loadingSpinner" class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Loading available rooms...</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/home/reservations.js"></script>
</body>
</html>