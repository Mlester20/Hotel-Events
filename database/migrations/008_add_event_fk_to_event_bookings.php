<?php

return [
    'up' => function ($con) {
        mysqli_query($con, "ALTER TABLE event_bookings 
            ADD CONSTRAINT fk_event_bookings_event_id 
            FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE");
    },

    'down' => function ($con) {
        mysqli_query($con, "ALTER TABLE event_bookings 
            DROP FOREIGN KEY fk_event_bookings_event_id");
    }
];
