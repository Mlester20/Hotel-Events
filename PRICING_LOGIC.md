# Pricing Logic & Total Price Calculation

## Overview
The booking system supports **three independent pricing models** that users can choose from:
- **Hourly**: Charged per hour of stay
- **Overnight**: Fixed 8-hour rate (typically for late night/early morning stays)
- **Daily**: Charged per night (standard hotel pricing)

The `pricing_type` column tracks which model was used, and `total_price` stores the calculated result.

---

## Pricing Models Explained

### 1. **Daily Pricing** (Standard Hotel Rate)
**Use Case**: Regular overnight stay (e.g., 2+ nights)

**Formula**:
```
total_price = price_day × number_of_nights
```

**Calculation**:
```
number_of_nights = CEIL((check_out_date - check_in_date) / 1 day)
```

**Examples**:
```
Check-in:  2026-04-15
Check-out: 2026-04-17
Nights: 2
Price per night: ₱2,500
Total: 2 × ₱2,500 = ₱5,000
```

**When to Use**:
- Multi-night stays
- Standard bookings
- Guests staying full days/nights

---

### 2. **Overnight Pricing** (8-Hour Rate)
**Use Case**: Late night arrivals or early morning departures (fixed 8-hour window)

**Formula**:
```
total_price = price_overnight (FIXED RATE)
```

**Examples**:
```
Check-in:  2026-04-15 22:00 (10 PM)
Check-out: 2026-04-16 06:00 (6 AM)
Duration: ~8 hours
Price: ₱800 (flat rate)
Total: ₱800
```

**When to Use**:
- Late night check-ins
- Early morning departures
- Airport transfers
- Short sleeps (8 hours or less)

**Note**: Rate is fixed regardless of exact duration (up to 8 hours)

---

### 3. **Hourly Pricing** (Pay-Per-Hour)
**Use Case**: Flexible short stays, day-rooms, meetings

**Formula**:
```
total_price = price_hourly × number_of_hours
```

**Calculation**:
```
For same-day checkout:
  hours_remaining = 24 - check_in_hour
  OR user specifies exact hours

For multi-day hourly:
  hours = (check_out_date - check_in_date) × 24 + (checkout_hour - checkin_hour)
```

**Examples**:

**Same Day (Simple Hourly)**:
```
Check-in:  2026-04-15 14:00 (2 PM)
Check-out: 2026-04-15 20:00 (8 PM)
Hours: 6 hours
Price per hour: ₱250
Total: 6 × ₱250 = ₱1,500
```

**Multi-Day (Hourly, e.g., for extended events)**:
```
Check-in:  2026-04-15 10:00
Check-out: 2026-04-17 15:00
Hours: 2 full days + 5 hours = 53 hours
Price per hour: ₱250
Total: 53 × ₱250 = ₱13,250
```

**When to Use**:
- Day rooms
- Business meetings
- Flexible short stays
- Hourly leases

---

## Database Schema

```sql
CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id VARCHAR(20) UNIQUE,        -- Public reference (BK-XXXXX)
  user_id INT,
  room_id INT,
  check_in_date DATE,
  check_out_date DATE,
  check_in_time TIME,                   -- Important for hourly/overnight
  pricing_type ENUM('hourly', 'overnight', 'daily'),
  total_price DECIMAL(10,2),            -- CALCULATED & STORED
  status ENUM('pending', 'confirmed', 'cancelled', 'completed'),
  payment_status ENUM('unpaid', 'partially_paid', 'paid'),
  special_requests TEXT,
  created_at TIMESTAMP,
  ...
)
```

---

## Frontend Calculation (JavaScript)

### Daily Pricing
```javascript
function calculateDailyPrice(room, checkInDate, checkOutDate) {
    const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
    return room.price_day * nights;
}

// Example
// nights = 2, price_day = 2500
// total = 5000
```

### Overnight Pricing
```javascript
function calculateOvernightPrice(room, checkInTime) {
    // Fixed rate - doesn't matter what hours
    return room.price_overnight;
}

// Example
// price_overnight = 800
// total = 800 (always)
```

### Hourly Pricing
```javascript
function calculateHourlyPrice(room, checkInDate, checkOutDate, checkInTime) {
    // If same day checkout
    if (checkInDate === checkOutDate) {
        const [hours, minutes] = checkInTime.split(':').map(Number);
        const checkInHour = hours + (minutes / 60);
        const hoursRemaining = Math.ceil(24 - checkInHour);
        return room.price_hourly * hoursRemaining;
    }
    
    // If multi-day
    const days = (checkOutDate - checkInDate) / (1000 * 60 * 60 * 24);
    const totalHours = Math.ceil(days * 24);
    return room.price_hourly * totalHours;
}

// Example 1 (same day)
// checkInTime = 14:00, hoursRemaining = 10
// price_hourly = 250
// total = 2500

// Example 2 (multi-day)
// 2 days = 48 hours, price_hourly = 250
// total = 12000
```

---

## Backend Calculation (PHP)

When user submits booking, JavaScript sends:
```json
{
    "room_id": 6,
    "check_in_date": "2026-04-15",
    "check_out_date": "2026-04-17",
    "check_in_time": "14:00",
    "pricing_type": "daily",
    "total_price": 5000.00
}
```

The backend (PHP) should:
1. **Verify** the calculation matches expected formula
2. **Store** `pricing_type` + `total_price` exactly as sent
3. **Query** uses pricing_type if needed for refunds/adjustments

```php
// In book-room.php
$pricingType = $input['pricing_type'];
$totalPrice = $input['total_price'];

// Verify total_price is reasonable (optional server-side check)
$calculatedPrice = calculateExpectedPrice($conn, $roomId, ...);
if (abs($totalPrice - $calculatedPrice) > 1.00) {
    throw new Exception('Price calculation mismatch');
}

// Store exactly as received
$insertQuery = "INSERT INTO bookings 
    (booking_id, user_id, room_id, check_in_date, check_out_date, check_in_time, 
     pricing_type, total_price, status, payment_status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'unpaid', NOW())";
```

---

## Hourly-Only Booking Scenarios

### Scenario 1: Same-Day Hourly Rental
```
Room: #102 (₱250/hour, ₱700/overnight, ₱2000/day)
User: Books 14:00 - 18:00 (4 hours)
Pricing: Hourly
Hours Used: 4
Total Price: 4 × 250 = ₱1,000

Database Storage:
  check_in_date: 2026-04-15
  check_out_date: 2026-04-15  ← Same day!
  check_in_time: 14:00
  pricing_type: 'hourly'
  total_price: 1000.00
```

### Scenario 2: Full-Day Hourly
```
Room: #102
User: Checks in 10:00, checks out 22:00 (12 hours)
Pricing: Hourly
Hours: 12
Total Price: 12 × 250 = ₱3,000
```

### Scenario 3: Multi-Day Event (Hourly)
```
Room: #201 (Meeting room, ₱350/hour)
Event: 2026-04-15 08:00 to 2026-04-17 18:00 (60.5 hours)
Pricing: Hourly
Hours: 61 (rounded up)
Total Price: 61 × 350 = ₱21,350
```

---

## Query to Find All Bookings by Pricing Type

```sql
-- Count by pricing type
SELECT 
    pricing_type, 
    COUNT(*) as count,
    AVG(total_price) as avg_price,
    SUM(total_price) as total_revenue
FROM bookings
WHERE status IN ('pending', 'confirmed', 'completed')
GROUP BY pricing_type;

-- Example Output
--┌──────────────┬───────┬──────────────┬───────────────┐
--│ pricing_type │ count │  avg_price   │ total_revenue │
--├──────────────┼───────┼──────────────┼───────────────┤
--│ daily        │  45   │ 2833.33      │ 127,500       │
--│ hourly       │ 120   │ 1250.00      │ 150,000       │
--│ overnight    │ 35    │ 875.00       │ 30,625        │
--└──────────────┴───────┴──────────────┴───────────────┘
```

---

## Revenue Calculation Examples

### Hotel Revenue Report
```
Room #102 (₱250/hr, ₱700/night, ₱2000/day):
  Daily Bookings: 10 × ₱2000 = ₱20,000
  Overnight Bookings: 8 × ₱700 = ₱5,600
  Hourly Bookings: 200 hours × ₱250 = ₱50,000
  Total Revenue: ₱75,600 ✓
```

---

## Important Notes

1. **`check_in_time` is CRITICAL** for hourly pricing - always required when pricing_type = 'hourly'
2. **Same-day hourly** means both check_in_date and check_out_date are the same
3. **`pricing_type` determines calculation** - never mix pricing models
4. **Store calculated price** - don't recalculate on every query
5. **Track pricing_type** - essential for reports and refunds

---

## Testing Checklist

- [ ] Daily pricing: 2 nights × ₱2500 = ₱5000 ✓
- [ ] Overnight pricing: Always ₱800 ✓
- [ ] Hourly same-day: 4 hours × ₱250 = ₱1000 ✓
- [ ] Hourly multi-day: 48 hours × ₱250 = ₱12000 ✓
- [ ] Verify check_in_time stored for hourly bookings ✓
- [ ] Verify pricing_type column populated ✓
- [ ] Verify total_price stored and displayed correctly ✓