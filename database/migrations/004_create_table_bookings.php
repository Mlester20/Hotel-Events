<?php

return [
    'up' => function ($con) {
        mysqli_query($con, "CREATE TABLE bookings (
            booking_id VARCHAR(20) UNIQUE,
            user_id INT,
            room_id INT,
            check_in_date DATE,
            check_out_date DATE,
            total_price DECIMAL(10, 2),
            status enum('pending', 'confirmed', 'cancelled', 'completed') DEFAULT('pending') NOT NULL,
            payment_status enum('unpaid', 'partially_paid', 'paid') DEFAULT('unpaid') NOT NULL,
            special_requests text,
            is_read boolean DEFAULT false,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
        )");
    },

    'down' => function ($con) {
        mysqli_query($con, "DROP TABLE bookings");
    }
];