<?php 

require_once __DIR__ . '/../../../controllers/admin/EventsController.php';
require_once __DIR__ . '/../../../helpers/message.php';
require_once __DIR__ . '/../../../middleware/auth.php';
allowOnly(['admin']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php require_once __DIR__ . '/../../../helpers/title.php'; ?></title>
    <link rel="icon" type="image/x-icon" href="../../../public/assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../public/assets/vendor/fonts/iconify-icons.css" />
    <link rel="stylesheet" href="../../../public/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="../../../public/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../../../public/assets/css/demo.css" />
    <link rel="stylesheet" href="../../../public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../../public/assets/vendor/libs/apex-charts/apex-charts.css" />
    <script src="../../../public/assets/vendor/js/helpers.js"></script>
    <script src="../../../public/assets/js/config.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>
    
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>
    <?php require_once __DIR__ . '/layout/topbar.php'; ?>

    <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
            Add Events
        </button>
    </div>

    <?php showFlash(); ?>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../../../controllers/admin/EventsController.php">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Event Title</label>
                            <input type="text" class="form-control" id="eventTitle" name="title" placeholder="Enter event title">
                        </div>
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="eventDescription" name="description" rows="3" placeholder="Enter event Description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="eventLocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="eventLocation" name="location" placeholder="Enter event location">
                        </div>
                        <div class="mb-3">
                            <label for="eventCapacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control" id="eventCapacity" name="capacity" placeholder="Enter event capacity">
                        </div>
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" name="createEvent">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventsModal" tabindex="-1" aria-labelledby="editEventsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventsModalLabel">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../../../controllers/admin/EventsController.php">
                        <input type="hidden" id="editEventId" name="id">
                        <div class="mb-3">
                            <label for="editEventTitle" class="form-label">Event Title</label>
                            <input type="text" class="form-control" id="Edittitle" name="title"
                                placeholder="Enter event title">
                        </div>
                        <div class="mb-3">
                            <label for="editEventDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="Editdescription" name="description" rows="3"
                                placeholder="Enter event Description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editEventLocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="Editlocation" name="location"
                                placeholder="Enter event location">
                        </div>
                        <div class="mb-3">
                            <label for="editEventCapacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control" id="Editcapacity" name="capacity"
                                placeholder="Enter event capacity">
                        </div>
                        <div class="mb-3">
                            <label for="editEventPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="Editprice" name="price" step="0.01">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" name="updateEvent">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Event Modal -->
    <div class="modal fade" id="viewEventModal" tabindex="-1" aria-labelledby="viewEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewEventModalLabel">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="text-muted fw-normal ps-0" style="width: 120px;">Title</th>
                                <td id="viewTitle"></td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal ps-0" style="width: 120px;">Description</th>
                                <td id="viewDescription"></td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal ps-0">Location</th>
                                <td id="viewLocation"></td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal ps-0">Capacity</th>
                                <td id="viewCapacity"></td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal ps-0">Price</th>
                                <td id="viewPrice"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
            

    <div class="card mt-4">
        <h5 class="card-header">Manage Events</h5>
        <div class="table-responsive nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Capacity</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($events)): ?>
                        <?php foreach($events as $event): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($event['title']); ?></td>
                                <td><?php echo htmlspecialchars($event['capacity']); ?></td>
                                <td><?php echo htmlspecialchars($event['price']); ?></td>
                                <td>
                                    <!-- Action buttons (Edit/Delete) can be added here -->
                                    <button
                                        class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editEventsModal"
                                        onclick='editEvent(
                                            <?php echo $event["id"]; ?>,
                                            <?php echo json_encode($event["title"]); ?>,
                                            <?php echo json_encode($event["description"]); ?>,
                                            <?php echo json_encode($event["location"]); ?>,
                                            <?php echo $event["capacity"]; ?>,
                                            <?php echo json_encode($event["price"]); ?>
                                        )'>
                                        <i class="fas fa-pencil"></i>
                                    </button>

                                    <!-- view modal -->
                                        <button
                                            class="btn btn-sm btn-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewEventModal"
                                            onclick='viewEvent(
                                                <?php echo $event["id"]; ?>,
                                                <?php echo json_encode($event["title"]); ?>,
                                                <?php echo json_encode($event["description"]); ?>,
                                                <?php echo json_encode($event["location"]); ?>,
                                                <?php echo $event["capacity"]; ?>,
                                                <?php echo json_encode($event["price"]); ?>
                                            )'>
                                            <i class="fas fa-eye"></i>
                                        </button>

                                    <!-- delete -->
                                    <form action="../../../controllers/admin/EventsController.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                        
                                        <button 
                                            name="deleteRoomType" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i> 
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No events found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="../../../public/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../../public/assets/vendor/libs/popper/popper.js"></script>
    <script src="../../../public/assets/vendor/js/bootstrap.js"></script>
    <script src="../../../public/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="../../../public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../../public/assets/vendor/js/menu.js"></script>
    <script src="../../../public/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="../../../public/assets/js/main.js"></script>
    <script src="../../../public/assets/js/dashboards-analytics.js"></script>
    <script async="async" defer="defer" src="https://buttons.github.io/buttons.js"></script>
    <script src="../../../public/js/admin/events.js"></script>
</body>
</html>