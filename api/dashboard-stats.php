<?php
/*
 * GET /api/dashboard-stats
 * Fetch all dashboard statistics
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

    $period = $_GET['period'] ?? 'today';
    $dateFilter = getDateFilter($period);

    // 1. Room Occupancy
    $totalRoomsQuery = "SELECT COUNT(*) as total FROM rooms";
    $occupiedRoomsQuery = "SELECT COUNT(DISTINCT room_id) as occupied FROM bookings 
                          WHERE status IN ('pending', 'confirmed') 
                          AND check_in_date <= CURDATE() 
                          AND check_out_date >= CURDATE()";

    $totalResult = $conn->query($totalRoomsQuery);
    $occupiedResult = $conn->query($occupiedRoomsQuery);

    $totalRooms = $totalResult->fetch_assoc()['total'] ?? 0;
    $occupiedRooms = $occupiedResult->fetch_assoc()['occupied'] ?? 0;
    $occupancyPercent = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;

    // 2. Revenue Breakdown (last 7 days)
    // Rooms revenue
    $roomRevenueQuery = "SELECT 
                            DATE(b.created_at) as date,
                            SUM(b.total_price) as rooms_revenue
                        FROM bookings b
                        WHERE b.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                        GROUP BY DATE(b.created_at)";

    // Events revenue
    $eventRevenueQuery = "SELECT 
                            DATE(eb.created_at) as date,
                            SUM(eb.total_price) as events_revenue
                        FROM event_bookings eb
                        WHERE eb.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                        GROUP BY DATE(eb.created_at)";

    $roomRevResult  = $conn->query($roomRevenueQuery);
    $eventRevResult = $conn->query($eventRevenueQuery);

    $roomRevMap  = [];
    $eventRevMap = [];

    while ($row = $roomRevResult->fetch_assoc()) {
        $roomRevMap[$row['date']] = (float) $row['rooms_revenue'];
    }
    while ($row = $eventRevResult->fetch_assoc()) {
        $eventRevMap[$row['date']] = (float) $row['events_revenue'];
    }

    // Build last-7-days array
    $revenueData = [];
    $totalRevenue = 0;
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $rooms  = $roomRevMap[$date]  ?? 0;
        $events = $eventRevMap[$date] ?? 0;
        $revenueData[] = [
            'date'           => $date,
            'rooms_revenue'  => $rooms,
            'events_revenue' => $events,
            'fnb_revenue'    => 0
        ];
        $totalRevenue += $rooms + $events;
    }

    // 3. Recent Bookings
    $bookingsQuery = "SELECT 
                        b.booking_id,
                        u.full_name,
                        r.room_number,
                        b.status,
                        b.total_price,
                        b.check_in_date,
                        b.check_out_date
                    FROM bookings b
                    JOIN users u ON b.user_id = u.user_id
                    JOIN rooms r ON b.room_id = r.id
                    ORDER BY b.created_at DESC
                    LIMIT 10";

    $bookingsResult = $conn->query($bookingsQuery);
    $recentBookings = [];
    while ($row = $bookingsResult->fetch_assoc()) {
        $recentBookings[] = $row;
    }

    // 4. Room Type Performance
    $roomTypeQuery = "SELECT 
                        rt.title,
                        COUNT(r.id) as total_rooms,
                        COUNT(CASE WHEN b.room_id IS NOT NULL THEN 1 END) as occupied_rooms
                    FROM room_types rt
                    LEFT JOIN rooms r ON rt.id = r.room_type_id
                    LEFT JOIN bookings b ON r.id = b.room_id 
                        AND b.status IN ('pending', 'confirmed')
                        AND b.check_in_date <= CURDATE()
                        AND b.check_out_date >= CURDATE()
                    GROUP BY rt.id, rt.title";

    $roomTypeResult = $conn->query($roomTypeQuery);
    $roomTypePerf   = [];
    while ($row = $roomTypeResult->fetch_assoc()) {
        $row['occupancy_percent'] = $row['total_rooms'] > 0
            ? round(($row['occupied_rooms'] / $row['total_rooms']) * 100)
            : 0;
        $roomTypePerf[] = $row;
    }

    // 5. Upcoming Events
    // event_bookings holds the actual booking_date; join with events for details
    $eventsQuery = "SELECT 
                        e.id,
                        e.title,
                        e.location,
                        e.capacity,
                        e.price,
                        eb.booking_date  AS event_date,
                        eb.start_time    AS event_time,
                        eb.status,
                        eb.number_of_guests
                    FROM event_bookings eb
                    JOIN events e ON eb.event_id = e.id
                    WHERE eb.booking_date >= CURDATE()
                    AND eb.status NOT IN ('cancelled')
                    ORDER BY eb.booking_date ASC
                    LIMIT 5";

    $eventsResult  = $conn->query($eventsQuery);
    $upcomingEvents = [];
    while ($row = $eventsResult->fetch_assoc()) {
        $upcomingEvents[] = $row;
    }

    // If no event_bookings yet, fall back to showing all events (as placeholders)
    if (empty($upcomingEvents)) {
        $fallbackQuery = "SELECT 
                            id,
                            title,
                            location,
                            capacity,
                            price,
                            NULL  AS event_date,
                            NULL  AS event_time,
                            'available' AS status,
                            0     AS number_of_guests
                          FROM events
                          ORDER BY id ASC
                          LIMIT 5";
        $fbResult = $conn->query($fallbackQuery);
        while ($row = $fbResult->fetch_assoc()) {
            $upcomingEvents[] = $row;
        }
    }

    // 6. Check-in / Check-out Trend (last 7 days)
    $trendQuery = "SELECT 
                        DATE(check_in_date) as date,
                        COUNT(*) as check_ins
                    FROM bookings
                    WHERE check_in_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    GROUP BY DATE(check_in_date)
                    ORDER BY date ASC";

    $checkoutQuery = "SELECT 
                        DATE(check_out_date) as date,
                        COUNT(*) as check_outs
                    FROM bookings
                    WHERE check_out_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    GROUP BY DATE(check_out_date)
                    ORDER BY date ASC";

    $trendResult   = $conn->query($trendQuery);
    $checkoutResult = $conn->query($checkoutQuery);

    $checkInData  = [];
    $checkOutData = [];
    while ($row = $trendResult->fetch_assoc())   { $checkInData[]  = $row; }
    while ($row = $checkoutResult->fetch_assoc()) { $checkOutData[] = $row; }

    // 7. Today's Check-ins
    $todayCheckinsQuery = "SELECT COUNT(*) as count FROM bookings 
                           WHERE check_in_date = CURDATE() 
                           AND status IN ('pending', 'confirmed')";

    $todayCheckinsResult = $conn->query($todayCheckinsQuery);
    $todayCheckins = $todayCheckinsResult->fetch_assoc()['count'] ?? 0;

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data'    => [
            'occupancy' => [
                'percent'  => $occupancyPercent,
                'occupied' => $occupiedRooms,
                'vacant'   => $totalRooms - $occupiedRooms,
                'total'    => $totalRooms
            ],
            'revenue' => [
                'total' => $totalRevenue,
                'daily' => $revenueData
            ],
            'bookings'       => $recentBookings,
            'room_types'     => $roomTypePerf,
            'events'         => $upcomingEvents,
            'check_in_trend' => $checkInData,
            'check_out_trend' => $checkOutData,
            'today_checkins' => $todayCheckins
        ]
    ]);

    $db->closeConnection();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function getDateFilter($period) {
    switch ($period) {
        case '7d':
            return "DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        case '30d':
            return "DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        case 'today':
        default:
            return "DATE(created_at) = CURDATE()";
    }
}