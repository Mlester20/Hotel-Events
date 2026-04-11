<?php
/**
 * Migration 002: Create bookings table with proper schema
 * 
 * KEY CHANGES:
 * - `id` is PRIMARY KEY (auto-increment)
 * - `booking_id` is UNIQUE KEY (for business reference like BK-XXXXX)
 * - `pricing_type` tracks which price was used (daily, hourly, overnight)
 * - `total_price` is calculated based on pricing_type and duration
 * - Includes status and payment_status for workflow
 */

require_once __DIR__ . '/../db/config/config.php';

$db = Database::getInstance();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed\n");
}

try {
    echo "Running Migration 002: Create bookings table with proper schema...\n\n";

    // Check if bookings table already exists
    $checkTable = "SHOW TABLES LIKE 'bookings'";
    $result = $conn->query($checkTable);
    
    if ($result && $result->num_rows > 0) {
        echo "! Bookings table already exists. Dropping and recreating...\n";
        $dropQuery = "DROP TABLE IF EXISTS bookings";
        if (!$conn->query($dropQuery)) {
            throw new Exception("Failed to drop bookings table: " . $conn->error);
        }
        echo "✓ Old bookings table dropped\n";
    }

    // Create new bookings table with proper schema
    $createBookings = "CREATE TABLE `bookings` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `booking_id` varchar(20) NOT NULL UNIQUE COMMENT 'Public booking reference (BK-XXXXX)',
      `user_id` int(11) NOT NULL,
      `room_id` int(11) NOT NULL,
      `check_in_date` date NOT NULL,
      `check_out_date` date NOT NULL,
      `check_in_time` time DEFAULT NULL COMMENT 'Time of check-in (for hourly/overnight calculations)',
      `pricing_type` enum('hourly', 'overnight', 'daily') NOT NULL COMMENT 'Which pricing model was used',
      `total_price` decimal(10,2) NOT NULL COMMENT 'Calculated total price based on pricing_type and duration',
      `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
      `payment_status` enum('unpaid','partially_paid','paid') NOT NULL DEFAULT 'unpaid',
      `special_requests` text DEFAULT NULL,
      `is_read` tinyint(1) DEFAULT 0,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      
      PRIMARY KEY (`id`),
      UNIQUE KEY `booking_id` (`booking_id`),
      KEY `user_id` (`user_id`),
      KEY `room_id` (`room_id`),
      KEY `status` (`status`),
      KEY `created_at` (`created_at`),
      
      CONSTRAINT `bookings_ibfk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
      CONSTRAINT `bookings_ibfk_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

    if (!$conn->query($createBookings)) {
        throw new Exception("Failed to create bookings table: " . $conn->error);
    }
    echo "✓ bookings table created with new schema\n";

    echo "\n📋 Bookings Table Structure:\n";
    echo "================================\n";
    echo "id                 : INT AUTO_INCREMENT PRIMARY KEY\n";
    echo "booking_id         : VARCHAR(20) UNIQUE (public reference)\n";
    echo "user_id            : INT FOREIGN KEY\n";
    echo "room_id            : INT FOREIGN KEY\n";
    echo "check_in_date      : DATE\n";
    echo "check_out_date     : DATE\n";
    echo "check_in_time      : TIME (for hourly/overnight calculations)\n";
    echo "pricing_type       : ENUM(hourly, overnight, daily)\n";
    echo "total_price        : DECIMAL - stored result\n";
    echo "status             : ENUM(pending, confirmed, cancelled, completed)\n";
    echo "payment_status     : ENUM(unpaid, partially_paid, paid)\n";
    echo "special_requests   : TEXT\n";
    echo "is_read            : TINYINT (notification flag)\n";
    echo "created_at         : TIMESTAMP\n";
    echo "updated_at         : TIMESTAMP\n";

    echo "\n✅ Migration 002 completed successfully!\n";
    $db->closeConnection();

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $db->closeConnection();
    exit(1);
}
?>
