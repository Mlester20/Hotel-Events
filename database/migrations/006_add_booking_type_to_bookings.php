<?php

return [
    'up' => function ($con) {
        mysqli_query($con, "ALTER TABLE bookings ADD COLUMN booking_type ENUM('per_hour', 'per_day', 'overnight') DEFAULT 'per_day' AFTER total_price");
    },

    'down' => function ($con) {
        mysqli_query($con, "ALTER TABLE bookings DROP COLUMN booking_type");
    }
];
