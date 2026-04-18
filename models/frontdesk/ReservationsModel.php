<?php

require_once __DIR__ . '/../BaseModel.php';

class ReservationsModel extends BaseModel {

    protected $bookings    = 'bookings';
    protected $users       = 'users';
    protected $rooms       = 'rooms';
    protected $room_types  = 'room_types';

    // ─── READ ────────────────────────────────────────────────────────────────

    /**
     * Fetch all room bookings with guest, room, and room-type info.
     */
    public function getRoomBookings(): array {
        try {
            $query = "
                SELECT
                    b.id,
                    b.booking_id,
                    b.check_in_date,
                    b.check_out_date,
                    b.check_in_time,
                    b.check_out_time,
                    b.booking_type,
                    b.total_price,
                    b.status          AS booking_status,
                    b.payment_status,
                    b.special_requests,
                    b.is_read,
                    b.created_at,
                    u.full_name       AS guest_name,
                    u.email           AS guest_email,
                    r.room_number,
                    rt.title          AS room_type
                FROM   {$this->bookings}   b
                LEFT JOIN {$this->users}      u  ON b.user_id      = u.user_id
                LEFT JOIN {$this->rooms}      r  ON b.room_id      = r.id
                LEFT JOIN {$this->room_types} rt ON r.room_type_id = rt.id
                ORDER BY b.created_at DESC
            ";

            $stmt = $this->con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;

        } catch (Exception $e) {
            throw new Exception('Error fetching room bookings: ' . $e->getMessage());
        }
    }

    // ─── UPDATE ──────────────────────────────────────────────────────────────

    /**
     * Update the booking status.
     * @param string $booking_id   e.g. "BK-32A4VQ09OV0C"
     * @param string $status       pending | confirmed | cancelled | completed
     */
    public function updateStatus(string $booking_id, string $status): bool {
        $allowed = ['pending', 'confirmed', 'cancelled', 'completed'];
        if (!in_array($status, $allowed, true)) {
            throw new InvalidArgumentException("Invalid status value: {$status}");
        }

        try {
            $query = "UPDATE {$this->bookings} SET status = ? WHERE booking_id = ?";
            $stmt  = $this->con->prepare($query);
            $stmt->bind_param('ss', $status, $booking_id);
            $stmt->execute();

            return $stmt->affected_rows > 0;

        } catch (Exception $e) {
            throw new Exception('Error updating booking status: ' . $e->getMessage());
        }
    }

    /**
     * Update the payment status.
     * @param string $booking_id      e.g. "BK-32A4VQ09OV0C"
     * @param string $payment_status  unpaid | partially_paid | paid
     */
    public function updatePayment(string $booking_id, string $payment_status): bool {
        $allowed = ['unpaid', 'partially_paid', 'paid'];
        if (!in_array($payment_status, $allowed, true)) {
            throw new InvalidArgumentException("Invalid payment_status value: {$payment_status}");
        }

        try {
            $query = "UPDATE {$this->bookings} SET payment_status = ? WHERE booking_id = ?";
            $stmt  = $this->con->prepare($query);
            $stmt->bind_param('ss', $payment_status, $booking_id);
            $stmt->execute();

            return $stmt->affected_rows > 0;

        } catch (Exception $e) {
            throw new Exception('Error updating payment status: ' . $e->getMessage());
        }
    }

    /**
     * Mark a booking as read (is_read = 1).
     * @param string $booking_id
     */
    public function markAsRead(string $booking_id): bool {
        try {
            $query = "UPDATE {$this->bookings} SET is_read = 1 WHERE booking_id = ?";
            $stmt  = $this->con->prepare($query);
            $stmt->bind_param('s', $booking_id);
            $stmt->execute();

            return $stmt->affected_rows >= 0; // 0 just means it was already read — still OK
        } catch (Exception $e) {
            throw new Exception('Error marking booking as read: ' . $e->getMessage());
        }
    }
}