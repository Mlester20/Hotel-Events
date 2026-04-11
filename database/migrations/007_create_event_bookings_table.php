<?php

return [
    'up' => function ($con) {
        mysqli_query($con, "CREATE TABLE event_bookings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            event_booking_id VARCHAR(20) UNIQUE,
            user_id INT NOT NULL,
            event_id INT NULL,
            booking_date DATE,
            number_of_guests INT,
            total_price DECIMAL(10, 2),
            status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending' NOT NULL,
            payment_status ENUM('unpaid', 'partially_paid', 'paid') DEFAULT 'unpaid' NOT NULL,
            special_requests TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
        )");
    },

    'down' => function ($con) {
        mysqli_query($con, "DROP TABLE event_bookings");
    }
];
