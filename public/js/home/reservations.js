document.addEventListener('DOMContentLoaded', () => {

    /* ───────────────────────────────────────────────
       DOM References
    ─────────────────────────────────────────────── */
    const filterForm       = document.getElementById('filterForm');
    const checkInDateInput = document.getElementById('checkInDate');
    const checkOutDateInput= document.getElementById('checkOutDate');
    const bookingTypeSelect= document.getElementById('bookingType');
    const roomTypeSelect   = document.getElementById('roomTypeSelect');
    const roomsContainer   = document.getElementById('roomsContainer');
    const loadingSpinner   = document.getElementById('loadingSpinner');

    // Modal elements
    const bookingModal      = new bootstrap.Modal(document.getElementById('bookingModal'));
    const selectedRoomDetails = document.getElementById('selectedRoomDetails');
    const modalCheckIn      = document.getElementById('modalCheckIn');
    const modalCheckOut     = document.getElementById('modalCheckOut');
    const modalTotalHours   = document.getElementById('modalTotalHours');
    const priceDaily        = document.getElementById('priceDaily');
    const priceOvernight    = document.getElementById('priceOvernight');
    const priceHourly       = document.getElementById('priceHourly');
    const hourlyTimeSection = document.getElementById('hourlyTimeSection');
    const modalCheckInTime  = document.getElementById('modalCheckInTime');
    const modalCheckOutTime = document.getElementById('modalCheckOutTime');
    const totalPriceEl      = document.getElementById('totalPrice');
    const specialRequests   = document.getElementById('specialRequests');
    const confirmBookingBtn = document.getElementById('confirmBookingBtn');
    const pricingRadios     = document.querySelectorAll('input[name="pricingType"]');

    /* ───────────────────────────────────────────────
       Toast Container — inject once into DOM
    ─────────────────────────────────────────────── */
    const toastContainer = document.createElement('div');
    toastContainer.id = 'toastContainer';
    toastContainer.style.cssText = `
        position: fixed;
        top: 1.25rem;
        right: 1.25rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: .5rem;
        min-width: 320px;
        pointer-events: none;
    `;
    document.body.appendChild(toastContainer);

    // Slide-in animation
    const toastStyle = document.createElement('style');
    toastStyle.textContent = `
        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(110%); }
            to   { opacity: 1; transform: translateX(0); }
        }
    `;
    document.head.appendChild(toastStyle);

    /* ───────────────────────────────────────────────
       Toast Notification Helper
    ─────────────────────────────────────────────── */
    function showToast(type, title, message, duration = 7000) {
        const colors = {
            success: '#198754',
            error:   '#dc3545',
            warning: '#ffc107',
            info:    '#0d6efd',
        };
        const icons = {
            success: 'bi-check-circle-fill text-success',
            error:   'bi-x-circle-fill text-danger',
            warning: 'bi-exclamation-triangle-fill text-warning',
            info:    'bi-info-circle-fill text-primary',
        };

        const color = colors[type] || colors.info;

        const toast = document.createElement('div');
        toast.style.cssText = `
            background: #fff;
            border-radius: .625rem;
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
            padding: 1rem 1.25rem 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            position: relative;
            overflow: hidden;
            border-left: 4px solid ${color};
            animation: toastSlideIn .25s ease;
            pointer-events: all;
            max-width: 380px;
        `;

        toast.innerHTML = `
            <i class="bi ${icons[type] || icons.info} fs-5 mt-1 flex-shrink-0"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold mb-1" style="font-size:.9rem;">${title}</div>
                <div class="text-muted" style="font-size:.82rem;line-height:1.4;">${message}</div>
            </div>
            <button type="button"
                    class="dismiss-toast-btn"
                    style="background:none;border:none;cursor:pointer;color:#6c757d;font-size:1.1rem;line-height:1;padding:0;margin-left:.25rem;">
                <i class="bi bi-x-lg"></i>
            </button>`;

        // Progress bar
        const bar = document.createElement('div');
        bar.style.cssText = `
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 .625rem .625rem;
            background: ${color};
            width: 100%;
            transition: width ${duration}ms linear;
        `;
        toast.appendChild(bar);
        toastContainer.appendChild(toast);

        // Shrink progress bar
        requestAnimationFrame(() => requestAnimationFrame(() => {
            bar.style.width = '0%';
        }));

        // Dismiss function
        const dismiss = () => {
            clearTimeout(timer);
            toast.style.transition = 'opacity .3s, transform .3s';
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(110%)';
            setTimeout(() => toast.remove(), 300);
        };

        // Auto-dismiss
        let timer = setTimeout(dismiss, duration);

        // Pause on hover
        toast.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            bar.style.transition = 'none';
        });
        toast.addEventListener('mouseleave', () => {
            bar.style.transition = `width 1500ms linear`;
            bar.style.width = '0%';
            timer = setTimeout(dismiss, 1500);
        });

        // Manual dismiss
        toast.querySelector('.dismiss-toast-btn').addEventListener('click', dismiss);
    }

    /* ───────────────────────────────────────────────
       State
    ─────────────────────────────────────────────── */
    let selectedRoom = null;

    /* ───────────────────────────────────────────────
       Helpers
    ─────────────────────────────────────────────── */
    const formatCurrency = (amount) =>
        '₱' + parseFloat(amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    const formatDate = (dateStr) => {
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });
    };

    const daysBetween = (start, end) => {
        const s = new Date(start + 'T00:00:00');
        const e = new Date(end   + 'T00:00:00');
        return Math.max(1, Math.round((e - s) / (1000 * 60 * 60 * 24)));
    };

    const hoursBetween = (timeIn, timeOut) => {
        const [h1, m1] = timeIn.split(':').map(Number);
        const [h2, m2] = timeOut.split(':').map(Number);
        const mins = (h2 * 60 + m2) - (h1 * 60 + m1);
        return mins > 0 ? mins / 60 : 0;
    };

    const showSpinner  = () => loadingSpinner.style.display = 'flex';
    const hideSpinner  = () => loadingSpinner.style.display = 'none';

    const showError = (msg) => {
        roomsContainer.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="bi bi-exclamation-circle text-danger fs-1"></i>
                <p class="text-danger mt-3">${msg}</p>
            </div>`;
    };

    /* ───────────────────────────────────────────────
       Set min dates to today
    ─────────────────────────────────────────────── */
    const today = new Date().toISOString().split('T')[0];
    checkInDateInput.min  = today;
    checkOutDateInput.min = today;

    checkInDateInput.addEventListener('change', () => {
        if (checkOutDateInput.value && checkOutDateInput.value <= checkInDateInput.value) {
            checkOutDateInput.value = '';
        }
        checkOutDateInput.min = checkInDateInput.value
            ? addDays(checkInDateInput.value, 1)
            : today;
    });

    function addDays(dateStr, days) {
        const d = new Date(dateStr + 'T00:00:00');
        d.setDate(d.getDate() + days);
        return d.toISOString().split('T')[0];
    }

    /* ───────────────────────────────────────────────
       1. Load Room Types on page load
    ─────────────────────────────────────────────── */
    async function loadRoomTypes() {
        try {
            const res  = await fetch('../../../api/room-types.php');
            const json = await res.json();
            if (json.success && Array.isArray(json.data)) {
                json.data.forEach(type => {
                    const opt = document.createElement('option');
                    opt.value       = type.id;
                    opt.textContent = type.title;
                    roomTypeSelect.appendChild(opt);
                });
            }
        } catch (err) {
            console.error('Failed to load room types:', err);
        }
    }

    loadRoomTypes();

    /* ───────────────────────────────────────────────
       2. Search Available Rooms
    ─────────────────────────────────────────────── */
    filterForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const checkIn  = checkInDateInput.value;
        const checkOut = checkOutDateInput.value;
        const roomType = roomTypeSelect.value;

        if (!checkIn || !checkOut) {
            showToast('warning', 'Missing Dates', 'Please select both check-in and check-out dates.');
            return;
        }

        const params = new URLSearchParams({
            check_in_date:  checkIn,
            check_out_date: checkOut,
        });
        if (roomType) params.append('room_type_id', roomType);

        showSpinner();
        roomsContainer.innerHTML = '';

        try {
            const res  = await fetch('../../../api/available-rooms.php?' + params.toString());
            const json = await res.json();

            if (!json.success) throw new Error(json.message || 'Search failed.');

            renderRooms(json.data, checkIn, checkOut);
        } catch (err) {
            showError(err.message);
            showToast('error', 'Search Failed', err.message);
        } finally {
            hideSpinner();
        }
    });

    /* ───────────────────────────────────────────────
       3. Render Room Cards
    ─────────────────────────────────────────────── */
    function renderRooms(rooms, checkIn, checkOut) {
        if (!rooms.length) {
            roomsContainer.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-door-closed fs-1 text-muted"></i>
                    <p class="text-muted mt-3">No rooms available for the selected dates.</p>
                </div>`;
            return;
        }

        const days = daysBetween(checkIn, checkOut);

        roomsContainer.innerHTML = rooms.map(room => {
            let images = [];
            try { images = JSON.parse(room.images || '[]'); } catch {}

            let amenities = [];
            try { amenities = JSON.parse(room.amenities || '[]'); } catch {}

            const imgTag = images.length
                ? `<img src="../../../storage/rooms/${images[0]}" class="card-img-top room-image" alt="Room ${room.room_number}" style="height:220px;object-fit:cover;">`
                : `<div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:220px;">
                       <i class="bi bi-image text-white fs-1"></i>
                   </div>`;

            const amenityBadges = amenities.map(a =>
                `<span class="badge bg-light text-dark border me-1"><i class="bi bi-check2"></i> ${a}</span>`
            ).join('');

            return `
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm room-card">
                    ${imgTag}
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">Room ${room.room_number}</h5>
                            <span class="badge bg-success">Available</span>
                        </div>
                        <p class="text-muted small mb-2">${room.room_type || 'Standard Room'}</p>
                        <div class="mb-3">${amenityBadges}</div>
                        <hr>
                        <div class="pricing-summary small text-muted mb-3">
                            <div><i class="bi bi-moon-stars"></i> Per Night: <strong class="text-dark">${formatCurrency(room.price_day)}</strong></div>
                            <div><i class="bi bi-moon"></i> Overnight: <strong class="text-dark">${formatCurrency(room.price_overnight)}</strong></div>
                            <div><i class="bi bi-clock"></i> Per Hour: <strong class="text-dark">${formatCurrency(room.price_hourly)}</strong>/hr</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold">${formatCurrency(room.price_day * days)} <small class="text-muted fw-normal">/ ${days} night${days > 1 ? 's' : ''}</small></span>
                            <button class="btn btn-primary btn-sm book-btn"
                                data-room='${JSON.stringify(room)}'
                                data-checkin="${checkIn}"
                                data-checkout="${checkOut}">
                                <i class="bi bi-calendar-check"></i> Book Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
        }).join('');

        // Attach book button listeners
        document.querySelectorAll('.book-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const room    = JSON.parse(btn.dataset.room);
                const checkIn = btn.dataset.checkin;
                const checkOut= btn.dataset.checkout;
                openBookingModal(room, checkIn, checkOut);
            });
        });
    }

    /* ───────────────────────────────────────────────
       4. Open Booking Modal
    ─────────────────────────────────────────────── */
    function openBookingModal(room, checkIn, checkOut) {
        selectedRoom = room;
        const days = daysBetween(checkIn, checkOut);

        let amenities = [];
        try { amenities = JSON.parse(room.amenities || '[]'); } catch {}

        selectedRoomDetails.innerHTML = `
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-0">Room ${room.room_number}</h6>
                    <small class="text-muted">${room.room_type || 'Standard Room'}</small>
                </div>
                <div class="text-end">
                    ${amenities.map(a => `<span class="badge bg-light text-dark border me-1">${a}</span>`).join('')}
                </div>
            </div>`;

        modalCheckIn.textContent  = formatDate(checkIn);
        modalCheckOut.textContent = formatDate(checkOut);
        modalTotalHours.textContent = `${days} night${days > 1 ? 's' : ''}`;

        priceDaily.textContent    = formatCurrency(room.price_day * days) + ` (${days} night${days > 1 ? 's' : ''})`;
        priceOvernight.textContent= formatCurrency(room.price_overnight);
        priceHourly.textContent   = formatCurrency(room.price_hourly) + '/hr';

        document.getElementById('bookingModal').dataset.checkin  = checkIn;
        document.getElementById('bookingModal').dataset.checkout = checkOut;

        document.getElementById('pricingDaily').checked = true;
        hourlyTimeSection.style.display = 'none';
        specialRequests.value = '';
        recalcTotal();

        bookingModal.show();
    }

    /* ───────────────────────────────────────────────
       5. Recalculate Total Price
    ─────────────────────────────────────────────── */
    function recalcTotal() {
        if (!selectedRoom) return;

        const modalEl  = document.getElementById('bookingModal');
        const checkIn  = modalEl.dataset.checkin;
        const checkOut = modalEl.dataset.checkout;
        const days     = daysBetween(checkIn, checkOut);
        const pricing  = document.querySelector('input[name="pricingType"]:checked')?.value;

        let total = 0;

        if (pricing === 'daily') {
            total = selectedRoom.price_day * days;
            modalTotalHours.textContent = `${days} night${days > 1 ? 's' : ''}`;
            hourlyTimeSection.style.display = 'none';
        } else if (pricing === 'overnight') {
            total = selectedRoom.price_overnight;
            modalTotalHours.textContent = '8 hours (overnight)';
            hourlyTimeSection.style.display = 'none';
        } else if (pricing === 'hourly') {
            hourlyTimeSection.style.display = 'block';
            const hrs = hoursBetween(modalCheckInTime.value, modalCheckOutTime.value);
            total = hrs > 0 ? selectedRoom.price_hourly * hrs : 0;
            modalTotalHours.textContent = hrs > 0 ? `${hrs} hour${hrs !== 1 ? 's' : ''}` : '—';
        }

        totalPriceEl.textContent = formatCurrency(total);
        totalPriceEl.dataset.amount = total;
    }

    pricingRadios.forEach(radio => radio.addEventListener('change', recalcTotal));
    modalCheckInTime.addEventListener('change', recalcTotal);
    modalCheckOutTime.addEventListener('change', recalcTotal);

    /* ───────────────────────────────────────────────
       6. Confirm Booking
    ─────────────────────────────────────────────── */
    confirmBookingBtn.addEventListener('click', async () => {
        if (!selectedRoom) return;

        const modalEl  = document.getElementById('bookingModal');
        const checkIn  = modalEl.dataset.checkin;
        const checkOut = modalEl.dataset.checkout;
        const pricing  = document.querySelector('input[name="pricingType"]:checked')?.value;
        const total    = parseFloat(totalPriceEl.dataset.amount || 0);

        if (total <= 0) {
            showToast('warning', 'Invalid Selection', 'Please ensure a valid pricing type and time are selected.');
            return;
        }

        const bookingTypeMap = {
            daily:     'per_day',
            overnight: 'overnight',
            hourly:    'per_hour',
        };
        const bookingType = bookingTypeMap[pricing];

        let checkInTime  = null;
        let checkOutTime = null;
        if (pricing === 'hourly') {
            checkInTime  = modalCheckInTime.value;
            checkOutTime = modalCheckOutTime.value;
            if (!checkInTime || !checkOutTime) {
                showToast('warning', 'Missing Times', 'Please set both check-in and check-out times for hourly booking.');
                return;
            }
            const hrs = hoursBetween(checkInTime, checkOutTime);
            if (hrs <= 0) {
                showToast('warning', 'Invalid Time', 'Check-out time must be after check-in time.');
                return;
            }
        }

        const payload = {
            room_id:          selectedRoom.id,
            check_in_date:    checkIn,
            check_out_date:   checkOut,
            booking_type:     bookingType,
            total_price:      total,
            check_in_time:    checkInTime,
            check_out_time:   checkOutTime,
            special_requests: specialRequests.value.trim() || null,
        };

        confirmBookingBtn.disabled = true;
        confirmBookingBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        try {
            const res  = await fetch('../../../api/book-room.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(payload),
            });
            const json = await res.json();

            if (!json.success) throw new Error(json.message || 'Booking failed.');

            bookingModal.hide();

            // Show success toast BEFORE re-render
            showToast(
                'success',
                '🎉 Booking Confirmed!',
                `Booking <strong>${json.booking_id}</strong> created successfully.<br>
                 ${formatDate(checkIn)} → ${formatDate(checkOut)}<br>
                 Total: <strong>${formatCurrency(total)}</strong>`,
                9000
            );

            // Re-run search to refresh availability
            filterForm.dispatchEvent(new Event('submit'));

        } catch (err) {
            showToast('error', 'Booking Failed', err.message);
        } finally {
            confirmBookingBtn.disabled = false;
            confirmBookingBtn.innerHTML = '<i class="bi bi-check-circle"></i> Confirm Booking';
        }
    });

});