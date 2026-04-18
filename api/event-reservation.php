<?php
// Start output buffering to catch any unexpected output
ob_start();

session_start();
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../db/config/config.php';
    require_once __DIR__ . '/../controllers/users/EventReservationController.php';
    require_once __DIR__ . '/../helpers/bookings_id.php';

    // Check if connection exists and is valid
    if (!$con || !($con instanceof mysqli)) {
        throw new Exception('Database connection is unavailable');
    }

    // Create controller instance
    $controller = new EventReservationController($con);

    // Check request method
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Clear any buffered output
        ob_clean();
        
        // Handle event reservation creation
        $response = $controller->create();
        echo json_encode($response);
        ob_end_flush();
        exit();
    } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Clear any buffered output
        ob_clean();
        
        // Handle GET requests
        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'check-conflict') {
                // Check for date conflicts
                $event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
                $booking_date_start = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                $booking_date_end = isset($_GET['end_date']) ? $_GET['end_date'] : '';

                if (empty($event_id) || empty($booking_date_start)) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Invalid parameters'
                    ]);
                } else {
                    $response = $controller->checkDateConflict($event_id, $booking_date_start, $booking_date_end);
                    echo json_encode($response);
                }
                ob_end_flush();
                exit();
            } else if ($_GET['action'] === 'all') {
                // Get all events
                $events = $controller->getAllEvents();
                echo json_encode([
                    'success' => true,
                    'events' => $events
                ]);
                ob_end_flush();
                exit();
            }
        } else if (isset($_SESSION['user_id'])) {
            // Get user's reservations
            $reservations = $controller->getAll($_SESSION['user_id']);
            echo json_encode([
                'success' => true,
                'reservations' => $reservations
            ]);
            ob_end_flush();
            exit();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
            ob_end_flush();
            exit();
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
        ob_end_flush();
        exit();
    }

} catch (Exception $e) {
    // Clear any buffered output
    ob_clean();
    
    // Log the error
    error_log("Event Reservation API Error: " . $e->getMessage());
    
    // Return JSON error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
    ob_end_flush();
    exit();
}

?>