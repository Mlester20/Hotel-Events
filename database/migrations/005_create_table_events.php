<?php

return [
    'up' => function ($con) {
        mysqli_query($con, "CREATE TABLE events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            event_date DATE NOT NULL,
            event_time TIME,
            location VARCHAR(255),
            capacity INT,
            price DECIMAL(10, 2),
            status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming' NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
    },

    'down' => function ($con) {
        mysqli_query($con, "DROP TABLE events");
    }
];
