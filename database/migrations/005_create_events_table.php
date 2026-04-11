<?php
/**
 * Migration 005: Create events reservation table
 * 
 * This table stores event reservations for the hotel including:
 * - Weddings, corporate events, debuts, etc.
 * - Guest count, venue details
 * - Event-specific information and requirements
 * - Status tracking and timestamps
 */

require_once __DIR__ . '/../db/config/config.php';

$db = Database::getInstance();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed\n");
}

try {
    echo "Running Migration 005: Create events reservation table...\n\n";

    // Check if events table already exists
    $checkTable = "SHOW TABLES LIKE 'events'";
    $result = $conn->query($checkTable);
    
    if ($result && $result->num_rows > 0) {
        echo "! Events table already exists. Dropping and recreating...\n";
        $dropQuery = "DROP TABLE IF EXISTS events";
        if (!$conn->query($dropQuery)) {
            throw new Exception("Failed to drop events table: " . $conn->error);
        }
        echo "✓ Old events table dropped\n";
    }

    // Create new events table
    $createEvents = "CREATE TABLE `events` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `event_id` varchar(20) NOT NULL UNIQUE COMMENT 'Public event reference (EV-XXXXX)',
      `user_id` int(11) NOT NULL COMMENT 'Foreign key to users table',
      `event_name` varchar(255) NOT NULL COMMENT 'Name/title of the event',
      `event_type` enum('wedding','corporate','debut','baptism','anniversary','birthday','conference','seminar','other') NOT NULL COMMENT 'Type of event',
      `event_date` date NOT NULL COMMENT 'Date the event will be held',
      `event_time` time DEFAULT NULL COMMENT 'Time the event will start',
      `guest_count` int(11) NOT NULL COMMENT 'Expected number of guests',
      `venue` varchar(255) NOT NULL COMMENT 'Venue/location for the event',
      `contact_person` varchar(255) NOT NULL COMMENT 'Primary contact person for the event',
      `contact_phone` varchar(20) NOT NULL COMMENT 'Contact phone number',
      `contact_email` varchar(255) DEFAULT NULL COMMENT 'Contact email address',
      `special_requests` text DEFAULT NULL COMMENT 'Special requests or requirements for the event',
      `estimated_budget` decimal(12,2) DEFAULT NULL COMMENT 'Estimated budget for the event',
      `status` enum('inquiry','pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'inquiry' COMMENT 'Status of the event reservation',
      `deposit_paid` tinyint(1) DEFAULT 0 COMMENT 'Whether deposit has been paid',
      `deposit_amount` decimal(10,2) DEFAULT NULL COMMENT 'Amount of deposit paid',
      `is_read` tinyint(1) DEFAULT 0 COMMENT 'Notification flag for admin',
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      
      PRIMARY KEY (`id`),
      UNIQUE KEY `event_id` (`event_id`),
      KEY `user_id` (`user_id`),
      KEY `event_type` (`event_type`),
      KEY `event_date` (`event_date`),
      KEY `status` (`status`),
      KEY `created_at` (`created_at`),
      
      CONSTRAINT `events_ibfk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

    if (!$conn->query($createEvents)) {
        throw new Exception("Failed to create events table: " . $conn->error);
    }
    echo "✓ events table created successfully\n";

    echo "\n📋 Events Table Structure:\n";
    echo "================================\n";
    echo "id                 : INT AUTO_INCREMENT PRIMARY KEY\n";
    echo "event_id           : VARCHAR(20) UNIQUE (public reference)\n";
    echo "user_id            : INT FOREIGN KEY (to users)\n";
    echo "event_name         : VARCHAR(255)\n";
    echo "event_type         : ENUM(wedding, corporate, debut, etc.)\n";
    echo "event_date         : DATE\n";
    echo "event_time         : TIME\n";
    echo "guest_count        : INT\n";
    echo "venue              : VARCHAR(255)\n";
    echo "contact_person     : VARCHAR(255)\n";
    echo "contact_phone      : VARCHAR(20)\n";
    echo "contact_email      : VARCHAR(255)\n";
    echo "special_requests   : TEXT\n";
    echo "estimated_budget   : DECIMAL(12,2)\n";
    echo "status             : ENUM(inquiry, pending, confirmed, cancelled, completed)\n";
    echo "deposit_paid       : TINYINT (0 or 1)\n";
    echo "deposit_amount     : DECIMAL(10,2)\n";
    echo "is_read            : TINYINT (notification flag)\n";
    echo "created_at         : TIMESTAMP\n";
    echo "updated_at         : TIMESTAMP\n";

    echo "\n✅ Migration 005 completed successfully!\n";
    $db->closeConnection();

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $db->closeConnection();
    exit(1);
}
?>
