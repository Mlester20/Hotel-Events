<?php
/*
 * GET /api/available-rooms
 * Fetch available rooms based on check-in date, check-out date, and room type
 * 
 * Query Parameters:
 * - check_in_date (required): Y-m-d format
 * - check_out_date (required): Y-m-d format
 * - check_in_time (optional): H:i format
 * - room_type_id (optional): Filter by room type
 */

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    require_once __DIR__ . '/../db/config/config.php';

    // Validate required parameters
    $checkInDate = $_GET['check_in_date'] ?? null;
    $checkOutDate = $_GET['check_out_date'] ?? null;
    $checkInTime = $_GET['check_in_time'] ?? '14:00';
    $roomTypeId = $_GET['room_type_id'] ?? null;

    if (!$checkInDate || !$checkOutDate) {
        throw new Exception('Missing required parameters: check_in_date and check_out_date');
    }

    // Validate date format
    if (!validateDateFormat($checkInDate) || !validateDateFormat($checkOutDate)) {
        throw new Exception('Invalid date format. Use Y-m-d');
    }

    if ($checkOutDate <= $checkInDate) {
        throw new Exception('Check-out date must be after check-in date');
    }

    $db = Database::getInstance();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Build query to get all rooms with their types
    $query = "SELECT 
                r.id,
                r.room_number,
                r.room_type_id,
                rt.title as room_type,
                r.amenities,
                r.images,
                r.price_day,
                r.price_hourly,
                r.price_overnight
            FROM rooms r
            LEFT JOIN room_types rt ON r.room_type_id = rt.id
            WHERE 1=1";

    if ($roomTypeId) {
        $roomTypeId = (int)$roomTypeId;
        $query .= " AND r.room_type_id = $roomTypeId";
    }

    $query .= " ORDER BY r.room_number ASC";

    $result = $conn->query($query);

    if (!$result) {
        throw new Exception('Query failed: ' . $conn->error);
    }

    $availableRooms = [];

    while ($room = $result->fetch_assoc()) {
        $roomId = (int)$room['id'];

        // Check if room is available for the requested dates
        if (isRoomAvailable($conn, $roomId, $checkInDate, $checkOutDate)) {
            $availableRooms[] = [
                'id' => $roomId,
                'room_number' => $room['room_number'],
                'room_type_id' => (int)$room['room_type_id'],
                'room_type' => $room['room_type'],
                'amenities' => $room['amenities'],
                'images' => $room['images'],
                'price_day' => (float)$room['price_day'],
                'price_hourly' => (float)$room['price_hourly'],
                'price_overnight' => (float)$room['price_overnight']
            ];
        }
    }

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => count($availableRooms) . ' room(s) available',
        'data' => $availableRooms,
        'filters' => [
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'check_in_time' => $checkInTime,
            'room_type_id' => $roomTypeId
        ]
    ]);

    $db->closeConnection();
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'data' => []
    ]);
}

/**
 * Check if a room is available for the given date range
 * A room is NOT available if there's a booking with:
 * - status = 'pending' OR 'confirmed'
 * - AND dates overlap
 */
function isRoomAvailable($conn, $roomId, $checkInDate, $checkOutDate) {
    $roomId = (int)$roomId;
    
    // Query for conflicting bookings
    // A booking conflicts if:
    // (new_check_in_date <= existing_check_out_date) AND (new_check_out_date >= existing_check_in_date)
    $query = "SELECT COUNT(*) as conflict_count
              FROM bookings
              WHERE room_id = $roomId
              AND status IN ('pending', 'confirmed')
              AND check_in_date <= '" . $conn->real_escape_string($checkOutDate) . "'
              AND check_out_date >= '" . $conn->real_escape_string($checkInDate) . "'";

    $result = $conn->query($query);
    if (!$result) {
        return false;
    }

    $row = $result->fetch_assoc();
    return $row['conflict_count'] == 0;
}

/**
 * Validate date format (Y-m-d)
 */
function validateDateFormat($date) {
    $format = 'Y-m-d';
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
