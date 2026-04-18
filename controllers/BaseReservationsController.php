<?php

require_once __DIR__ . '/../models/BaseModel.php'; //inherit instead of declaring it

    abstract class BaseReservationsController extends BaseModel {

        // ROOM BOOKINGS
        abstract public function getRoomBookings();
        abstract public function updateRoomStatus($booking_id, $status);
        abstract public function updateRoomPayment($booking_id, $payment_status);
        abstract public function markRoomAsRead($booking_id);

        // EVENT BOOKINGS
        // abstract public function getEventBookings();
        // abstract public function updateEventStatus($event_booking_id, $status);
        // abstract public function updateEventPayment($event_booking_id, $payment_status);
    }