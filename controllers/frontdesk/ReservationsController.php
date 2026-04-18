<?php

require_once __DIR__ . '/../../models/frontdesk/ReservationsModel.php';
require_once __DIR__ . '/../BaseReservationsController.php';

    class ReservationsController extends BaseReservationsController {

        private ReservationsModel $model;

        public function __construct($con) {
            parent::__construct($con); 
            
            $this->model = new ReservationsModel($con);
        }

        // ─── ROOM BOOKINGS ───────────────────────────────────────────────────────

        /**
         * Return all room bookings as an array.
         * Automatically marks each unread booking as read.
         */
        public function getRoomBookings(): array {
            $bookings = $this->model->getRoomBookings();

            foreach ($bookings as $booking) {
                if (empty($booking['is_read'])) {
                    $this->model->markAsRead($booking['booking_id']);
                }
            }

            return $bookings;
        }

        /**
         * Update booking status via POST (AJAX-friendly).
         * Expects: booking_id, status
         */
        public function updateRoomStatus($booking_id, $status): array {
            try {
                $updated = $this->model->updateStatus($booking_id, $status);

                return [
                    'success' => $updated,
                    'message' => $updated
                        ? 'Booking status updated successfully.'
                        : 'No booking found with that ID.',
                ];
            } catch (InvalidArgumentException $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            } catch (Exception $e) {
                return ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
            }
        }

        /**
         * Update payment status via POST (AJAX-friendly).
         * Expects: booking_id, payment_status
         */
        public function updateRoomPayment($booking_id, $payment_status): array {
            try {
                $updated = $this->model->updatePayment($booking_id, $payment_status);
                return [
                    'success' => $updated,
                    'message' => $updated
                        ? 'Payment status updated successfully.'
                        : 'No booking found with that ID.',
                ];
            } catch (InvalidArgumentException $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            } catch (Exception $e) {
                return ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
            }
        }

        /**
         * Mark a single booking as read.
         */
        public function markRoomAsRead($booking_id): array {
            try {
                $this->model->markAsRead($booking_id);
                return ['success' => true, 'message' => 'Marked as read.'];
            } catch (Exception $e) {
                return ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
            }
        }
    }