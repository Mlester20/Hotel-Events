<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../middleware/auth.php';
allowOnly(['front_desk', 'admin']);
require_once __DIR__ . '/../controllers/frontdesk/ReservationsController.php';
require_once __DIR__ . '/../db/config/config.php';

// ── Parse input ───────────────────────────────────────────────────────────────
$raw    = file_get_contents('php://input');
$data   = json_decode($raw, true) ?? $_POST;

$action     = trim($data['action']     ?? '');
$booking_id = trim($data['booking_id'] ?? '');
$value      = trim($data['value']      ?? '');

if (empty($action) || empty($booking_id)) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters.']);
    exit;
}

// ── Dispatch ──────────────────────────────────────────────────────────────────
$controller = new ReservationsController($con);

switch ($action) {
    case 'update_status':
        $response = $controller->updateRoomStatus($booking_id, $value);
        break;

    case 'update_payment':
        $response = $controller->updateRoomPayment($booking_id, $value);
        break;

    case 'mark_read':
        $response = $controller->markRoomAsRead($booking_id);
        break;

    default:
        $response = ['success' => false, 'message' => "Unknown action: {$action}"];
}

echo json_encode($response);
exit;