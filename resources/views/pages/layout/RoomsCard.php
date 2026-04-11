<?php
require_once __DIR__ . '/../../../../models/admin/RoomsModel.php';
require_once __DIR__ . '/../../../../db/config/config.php';

$roomsModel = new RoomsModel($con);
$allRooms = $roomsModel->index();
$rooms = array_slice($allRooms, 0, 3); // Get first 3 rooms
?>

<section class="events-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-8 mx-auto text-center">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-2 mb-2">Our Available Rooms</h6>
                <h2 class="display-5 fw-semibold text-dark mb-3">Choose Your Perfect Room</h2>
                <p class="text-muted">Experience luxury and comfort in our carefully curated selection of rooms, each designed to provide an unforgettable stay.</p>
                <div class="header-line mx-auto"></div>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach($rooms as $room): ?>
                <?php 
                    $images = json_decode($room['images'], true);
                    $firstImage = !empty($images) && is_array($images) ? $images[0] : 'default-room.jpg';
                    $imagePath = '../../../storage/rooms/' . $firstImage;
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="hotel-event-card">
                        <div class="event-img-wrapper">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($room['room_type']); ?>">
                            <div class="event-category"><?php echo htmlspecialchars($room['room_number']); ?></div>
                        </div>
                        <div class="event-details text-center">
                            <h3><?php echo htmlspecialchars($room['room_type']); ?></h3>
                            <p>Luxury accommodation with premium amenities and exceptional service for your comfortable stay.</p>
                            <div class="event-meta">
                                <span><i class="bi bi-tag"></i> ₱<?php echo htmlspecialchars($room['price_overnight']); ?>/night</span>
                            </div>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn-hotel-gold">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>