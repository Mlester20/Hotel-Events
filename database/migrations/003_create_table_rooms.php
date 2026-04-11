<?php

return [
    'up' => function ($con) {
        mysqli_query($con, "CREATE TABLE rooms (
            id INT AUTO_INCREMENT PRIMARY KEY,
            room_number VARCHAR(10) UNIQUE,
            room_type_id INT,
            price DECIMAL(10, 2),
            amenities JSON,
            images JSON,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (room_type_id) REFERENCES room_types(id)
        )");
    },

    'down' => function ($con) {
        mysqli_query($con, "DROP TABLE rooms");
    }
];  