<?php
/*
 * GET /api/room-types
 * Fetch all room types
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

    $db = Database::getInstance();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    $query = "SELECT id, title, details FROM room_types ORDER BY title ASC";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception('Query failed: ' . $conn->error);
    }

    $roomTypes = [];
    while ($row = $result->fetch_assoc()) {
        $roomTypes[] = [
            'id' => (int)$row['id'],
            'title' => $row['title'],
            'details' => $row['details']
        ];
    }

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Room types retrieved successfully',
        'data' => $roomTypes
    ]);

    $db->closeConnection();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'data' => []
    ]);
}
