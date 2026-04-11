<?php
require_once __DIR__ . '/../../../controllers/users/home.php';
require_once __DIR__ . '/../../../middleware/auth.php';
require_once __DIR__ . '/../../../controllers/users/ReservationsController.php';
allowOnly(['user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Home | <?php require_once __DIR__ . '../../../../helpers/title.php'; ?> </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../public/css/home.css">
</head>
<body>

    <?php require_once __DIR__ . '/../pages/layout/navbar.php'; ?>

    <!-- hero sections -->
    <?php require_once 'layout/hero.php'; ?>
    <?php require_once 'layout/check_availability.php'; ?>
    <?php require_once 'layout/EventCard.php' ?>
    <?php require_once 'layout/RoomsCard.php'; ?>
    

  <script src="../../../public/js/home/hero.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>