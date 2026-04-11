<?php

    class GenerateBookingID {
        public static function generate() {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $bookingID = '';
            for ($i = 0; $i < 12; $i++) {
                $bookingID .= $characters[rand(0, strlen($characters) - 1)];
            }
            return 'BK-' . $bookingID;
        }  
    }

?>