function viewReservation(id, bookingId, eventTitle, eventLocation, userName, userEmail, guests, totalPrice, dateStart, dateEnd, startTime, status, paymentStatus, specialRequests) {

    const statusBadge = {
        confirmed: '<span class="badge bg-success">Confirmed</span>',
        pending:   '<span class="badge bg-warning text-dark">Pending</span>',
        cancelled: '<span class="badge bg-danger">Cancelled</span>',
        completed: '<span class="badge bg-secondary">Completed</span>',
    };

    const paymentBadge = {
        paid:            '<span class="badge bg-success">Paid</span>',
        unpaid:          '<span class="badge bg-danger">Unpaid</span>',
        partially_paid:  '<span class="badge bg-warning text-dark">Partially Paid</span>',
    };

    document.getElementById('view_booking_id').textContent     = bookingId;
    document.getElementById('view_event_title').textContent    = eventTitle;
    document.getElementById('view_event_location').textContent = eventLocation;
    document.getElementById('view_user_name').textContent      = userName;
    document.getElementById('view_user_email').textContent     = userEmail;
    document.getElementById('view_guests').textContent         = guests;
    document.getElementById('view_total_price').textContent    = '₱' + parseFloat(totalPrice).toLocaleString('en-PH', { minimumFractionDigits: 2 });
    document.getElementById('view_date_start').textContent     = dateStart;
    document.getElementById('view_date_end').textContent       = dateEnd;
    document.getElementById('view_start_time').textContent     = startTime;
    document.getElementById('view_special_requests').textContent = specialRequests;
    document.getElementById('view_status').innerHTML           = statusBadge[status]  ?? status;
    document.getElementById('view_payment_status').innerHTML   = paymentBadge[paymentStatus] ?? paymentStatus;
}