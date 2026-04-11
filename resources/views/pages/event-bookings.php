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
    <style>
        :root {
            --primary-color: #8b8c5f;
            --secondary-color: #d4af37;
            --light-bg: #f8f8f6;
            --border-color: #e0e0e0;
        }

        .events-container {
            padding: 40px 0;
        }

        .event-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .event-card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .event-header {
            padding: 25px;
            background: linear-gradient(135deg, var(--primary-color), #6b6c4f);
            color: white;
        }

        .event-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }

        .event-date-time {
            display: flex;
            gap: 15px;
            align-items: center;
            font-size: 14px;
        }

        .event-date-time i {
            color: var(--secondary-color);
            font-size: 18px;
        }

        .event-body {
            padding: 25px;
        }

        .event-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .detail-item {
            text-align: center;
            padding: 15px;
            background: var(--light-bg);
            border-radius: 6px;
        }

        .detail-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .detail-value {
            font-size: 18px;
            font-weight: bold;
            color: var(--primary-color);
        }

        .event-description {
            padding: 15px;
            background: var(--light-bg);
            border-left: 4px solid var(--secondary-color);
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.6;
            color: #555;
        }

        .event-location {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #666;
        }

        .event-location i {
            color: var(--primary-color);
            font-size: 18px;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 15px;
        }

        .price-tag {
            font-size: 24px;
            font-weight: bold;
            color: var(--secondary-color);
        }

        .btn-book {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-book:hover {
            background: #6b6c4f;
            transform: translateX(2px);
        }

        .btn-book:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-upcoming {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-ongoing {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .status-completed {
            background: #e8f5e9;
            color: #388e3c;
        }

        .status-cancelled {
            background: #ffebee;
            color: #d32f2f;
        }

        .capacity-warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 12px;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #666;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .event-details {
                grid-template-columns: 1fr;
            }

            .event-footer {
                flex-direction: column;
            }

            .event-title {
                font-size: 20px;
            }

            .price-tag {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    
    <?php require_once 'layout/navbar.php'; ?>

    <div class="container events-container">
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center text-dark fw-bold mb-2">Upcoming Events</h2>
                <p class="text-center text-muted">Discover and book exclusive events</p>
            </div>
        </div>

        <!-- Events List -->
        <div class="row" id="eventsContainer">
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-calendar-event"></i>
                    <h3>Loading events...</h3>
                    <p>Please wait while we fetch available events.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Booking Modal -->
    <div class="modal fade" id="eventBookingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="modalEventTitle">Book Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Event Details Summary -->
                    <div class="mb-4" id="eventSummary">
                        <!-- Dynamically populated -->
                    </div>

                    <!-- Alert for capacity issues -->
                    <div id="capacityAlert" class="capacity-warning d-none" role="alert">
                        <!-- Dynamically populated -->
                    </div>

                    <!-- Booking Form -->
                    <form id="eventBookingForm">
                        <input type="hidden" id="eventIdInput" name="event_id">

                        <!-- Number of Guests -->
                        <div class="mb-3">
                            <label for="guestCount" class="form-label fw-semibold">Number of Guests</label>
                            <input type="number" class="form-control" id="guestCount" name="number_of_guests" 
                                   min="1" required placeholder="Enter number of guests">
                            <small class="text-muted" id="capacityInfo"></small>
                        </div>

                        <!-- Special Requests -->
                        <div class="mb-3">
                            <label for="specialRequests" class="form-label fw-semibold">Special Requests (Optional)</label>
                            <textarea class="form-control" id="specialRequests" name="special_requests" 
                                      rows="3" placeholder="Any special requests or dietary restrictions?"></textarea>
                            <small class="text-muted">Let us know if you have any special needs</small>
                        </div>

                        <!-- Total Price Display -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Price per Guest</small>
                                    <h5 id="pricePerGuest">₱0.00</h5>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Total</small>
                                    <h5 id="totalPrice" class="text-primary">₱0.00</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-check-circle"></i> Confirm Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body py-5">
                    <i class="bi bi-check-circle text-success" style="font-size: 48px;"></i>
                    <h5 class="mt-3 fw-bold">Booking Confirmed!</h5>
                    <p class="text-muted mt-2" id="successMessage">Your event booking has been created successfully.</p>
                    <div class="mt-4" id="bookingDetails">
                        <!-- Dynamically populated -->
                    </div>
                    <button type="button" class="btn btn-primary w-100 mt-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/home/event-bookings.js"></script>
</body>
</html>
