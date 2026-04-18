document.addEventListener('DOMContentLoaded', function() {
    // Handle event reservation form submission
    const reservationForms = document.querySelectorAll('.event-reservation-form');
    
    reservationForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const eventId = this.getAttribute('data-event-id');
            const eventPrice = parseFloat(this.getAttribute('data-event-price'));
            
            // Check if there's a visible conflict warning
            const conflictWarning = this.querySelector('.conflict-warning');
            if (conflictWarning && !conflictWarning.classList.contains('d-none')) {
                showAlert('error', 'Booking Conflict!', 'Please select dates that don\'t have existing reservations.');
                return;
            }
            
            // Get form data
            const formData = new FormData(this);
            
            // Add event price to the request
            formData.append('total_price', eventPrice);
            
            // Disable submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Processing...';
            
            // Send AJAX request
            fetch('../../../api/event-reservation.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Log the response for debugging
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                // Check if response is OK
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Try to parse as JSON
                return response.text().then(text => {
                    console.log('Raw response:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        console.error('Response text:', text);
                        throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    showAlert('success', 'Success!', data.message + '\n\nBooking ID: ' + data.booking_id + '\nTotal: ₱' + parseFloat(data.total_price).toLocaleString('en-PH', { minimumFractionDigits: 2 }));
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(this.closest('.modal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Reset form
                    this.reset();
                    
                    // Redirect to reservations page after 2 seconds
                    setTimeout(() => {
                        window.location.href = 'event-reservation.php';
                    }, 2000);
                } else {
                    showAlert('error', 'Error!', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Error!', 'An error occurred while processing your request.');
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    });
    
    // Handle date and guest changes to update total price
    reservationForms.forEach(form => {
        const guestInput = form.querySelector('input[name="number_of_guests"]');
        const eventPrice = parseFloat(form.getAttribute('data-event-price'));
        const totalPriceDisplay = form.querySelector('.total-price');
        
        if (guestInput && totalPriceDisplay) {
            guestInput.addEventListener('change', function() {
                // For now, total price is fixed per event regardless of guest count
                // Modify this if you want dynamic pricing based on guests
                totalPriceDisplay.textContent = eventPrice.toLocaleString('en-PH', { minimumFractionDigits: 2 });
            });
        }
    });
    
    // Validate date inputs (end date should be >= start date) and check for conflicts
    reservationForms.forEach(form => {
        const startDateInput = form.querySelector('input[name="booking_date_start"]');
        const endDateInput = form.querySelector('input[name="booking_date_end"]');
        const eventId = form.getAttribute('data-event-id');
        
        // Create conflict warning element if it doesn't exist
        let conflictWarning = form.querySelector('.conflict-warning');
        if (!conflictWarning) {
            conflictWarning = document.createElement('div');
            conflictWarning.className = 'alert alert-warning d-none conflict-warning mt-3';
            conflictWarning.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i><strong>Oops!</strong> <span class="conflict-message"></span>';
            form.querySelector('.modal-body').appendChild(conflictWarning);
        }
        
        // Function to check date conflicts
        function checkConflicts() {
            if (!startDateInput.value) return;
            
            const startDate = startDateInput.value;
            const endDate = endDateInput.value || startDate;
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const wasDisabled = submitBtn.disabled;
            submitBtn.disabled = true;
            
            // Fetch conflict check
            fetch(`../../../api/event-reservation.php?action=check-conflict&event_id=${eventId}&start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    if (data.hasConflict) {
                        // Show conflict warning
                        const conflictDates = data.conflictDates.map(d => {
                            const dateObj = new Date(d);
                            return dateObj.toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' });
                        });
                        
                        const conflictMsg = `This event is already booked on: <strong>${conflictDates.join(', ')}</strong>. Please select different dates.`;
                        conflictWarning.querySelector('.conflict-message').innerHTML = conflictMsg;
                        conflictWarning.classList.remove('d-none');
                        submitBtn.disabled = true;
                    } else {
                        // No conflict - hide warning
                        conflictWarning.classList.add('d-none');
                        submitBtn.disabled = wasDisabled;
                    }
                })
                .catch(error => {
                    console.error('Conflict check error:', error);
                    submitBtn.disabled = wasDisabled;
                });
        }
        
        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', function() {
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = this.value;
                }
                endDateInput.setAttribute('min', this.value);
                
                // Check for conflicts
                checkConflicts();
            });
            
            endDateInput.addEventListener('change', function() {
                // Check for conflicts
                checkConflicts();
            });
        }

        // Validate end_time >= start_time
        const startTimeInput = form.querySelector('input[name="start_time"]');
        const endTimeInput = form.querySelector('input[name="end_time"]');
        
        if (startTimeInput && endTimeInput) {
            startTimeInput.addEventListener('change', function() {
                if (endTimeInput.value && endTimeInput.value < this.value) {
                    endTimeInput.value = this.value;
                }
            });
            
            endTimeInput.addEventListener('change', function() {
                if (this.value && this.value < startTimeInput.value) {
                    showAlert('warning', 'Invalid Time!', 'End time must be after start time.');
                    this.value = startTimeInput.value;
                }
            });
        }
    });
});

// Alert/Notification function
function showAlert(type, title, message) {
    let alertClass = 'danger';
    if (type === 'success') alertClass = 'success';
    else if (type === 'warning') alertClass = 'warning';
    
    const alertHTML = `
        <div class="alert alert-${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
            <strong>${title}</strong><br>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    const alertContainer = document.createElement('div');
    alertContainer.innerHTML = alertHTML;
    document.body.appendChild(alertContainer.firstElementChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert-dismissible');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}
