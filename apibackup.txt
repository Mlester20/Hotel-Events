<?php
/*
 * POST /api/book-room
 * Create a new booking
 * 
 * Required JSON body:
 * {
 *   "room_id": int,
 *   "check_in_date": "Y-m-d",
 *   "check_out_date": "Y-m-d",
 *   "booking_type": "per_hour|per_day|overnight",
 *   "total_price": float,
 *   "check_in_time": "H:i" (required for per_hour),
 *   "check_out_time": "H:i" (required for per_hour),
 *   "special_requests": string (optional)
 * }
 */

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    // Check if user is authenticated (optional - modify based on your auth system)
    require_once __DIR__ . '/../middleware/auth.php';
    
    // Get the logged-in user ID from session
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        throw new Exception('User not authenticated');
    }

    require_once __DIR__ . '/../db/config/config.php';
    require_once __DIR__ . '/../helpers/bookings_id.php';

    // Get request body
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input
    $roomId = $input['room_id'] ?? null;
    $checkInDate = $input['check_in_date'] ?? null;
    $checkOutDate = $input['check_out_date'] ?? null;
    $bookingType = $input['booking_type'] ?? 'per_day';
    $totalPrice = $input['total_price'] ?? 0;
    $specialRequests = $input['special_requests'] ?? null;
    $checkInTime = $input['check_in_time'] ?? null;
    $checkOutTime = $input['check_out_time'] ?? null;

    if (!$roomId || !$checkInDate || !$checkOutDate) {
        throw new Exception('Missing required fields');
    }

    if (!in_array($bookingType, ['per_hour', 'per_day', 'overnight'])) {
        throw new Exception('Invalid booking type');
    }

    // For hourly pricing, check_in_time and check_out_time are required
    if ($bookingType === 'per_hour' && (!$checkInTime || !$checkOutTime)) {
        throw new Exception('Check-in and check-out times are required for per_hour booking type');
    }

    if ($checkOutDate <= $checkInDate) {
        throw new Exception('Check-out date must be after check-in date');
    }

    if ($totalPrice <= 0) {
        throw new Exception('Invalid total price');
    }

    $db = Database::getInstance();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Verify room exists
    $roomQuery = "SELECT id, price_day, price_hourly, price_overnight FROM rooms WHERE id = " . (int)$roomId;
    $roomResult = $conn->query($roomQuery);
    if (!$roomResult || $roomResult->num_rows === 0) {
        throw new Exception('Room not found');
    }
    
    $roomData = $roomResult->fetch_assoc();

    // Generate booking ID
    $bookingId = GenerateBookingID::generate();

    // Check for duplicate booking ID (rare but possible)
    $checkDupQuery = "SELECT booking_id FROM bookings WHERE booking_id = '" . $conn->real_escape_string($bookingId) . "'";
    while ($conn->query($checkDupQuery)->num_rows > 0) {
        $bookingId = GenerateBookingID::generate();
    }

    // Verify room is still available
    $conflictQuery = "SELECT COUNT(*) as conflict_count
                     FROM bookings
                     WHERE room_id = " . (int)$roomId . "
                     AND status IN ('pending', 'confirmed')
                     AND check_in_date <= '" . $conn->real_escape_string($checkOutDate) . "'
                     AND check_out_date >= '" . $conn->real_escape_string($checkInDate) . "'";

    $conflictResult = $conn->query($conflictQuery);
    if (!$conflictResult) {
        throw new Exception('Conflict check failed');
    }

    $conflictRow = $conflictResult->fetch_assoc();
    if ($conflictRow['conflict_count'] > 0) {
        throw new Exception('Room is no longer available for selected dates');
    }

    // Insert booking
    $insertQuery = "INSERT INTO bookings 
                   (booking_id, user_id, room_id, check_in_date, check_out_date, check_in_time, check_out_time, total_price, booking_type, status, payment_status, special_requests, created_at)
                   VALUES 
                   ('" . $conn->real_escape_string($bookingId) . "', 
                    " . (int)$userId . ", 
                    " . (int)$roomId . ", 
                    '" . $conn->real_escape_string($checkInDate) . "', 
                    '" . $conn->real_escape_string($checkOutDate) . "', 
                    " . ($checkInTime ? "'" . $conn->real_escape_string($checkInTime) . "'" : "NULL") . ", 
                    " . ($checkOutTime ? "'" . $conn->real_escape_string($checkOutTime) . "'" : "NULL") . ", 
                    " . (float)$totalPrice . ", 
                    '" . $conn->real_escape_string($bookingType) . "', 
                    'pending', 
                    'unpaid', 
                    " . ($specialRequests ? "'" . $conn->real_escape_string($specialRequests) . "'" : "NULL") . ", 
                    NOW())";

    if (!$conn->query($insertQuery)) {
        throw new Exception('Failed to create booking: ' . $conn->error);
    }

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Booking created successfully',
        'booking_id' => $bookingId,
        'data' => [
            'booking_id' => $bookingId,
            'room_id' => (int)$roomId,
            'user_id' => (int)$userId,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'total_price' => (float)$totalPrice,
            'booking_type' => $bookingType,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'special_requests' => $specialRequests
        ]
    ]);

    $db->closeConnection();
} catch (Exception $e) {
    $statusCode = strpos($e->getMessage(), 'not authenticated') !== false ? 401 : 400;
    http_response_code($statusCode);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'data' => []
    ]);
    if (isset($db)) {
        $db->closeConnection();
    }
}
