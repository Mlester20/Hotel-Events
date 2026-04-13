<?php
require_once __DIR__ . '/helpers/bookings_id.php';

    $bookingId = generateBookingId::generate();
    echo "Generated Booking ID: " . $bookingId;

?>