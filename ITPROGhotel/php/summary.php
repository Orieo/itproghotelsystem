<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.html');
    exit;
}

// Fetch booking details from session
$bookingDetails = isset($_SESSION['booking']) ? $_SESSION['booking'] : [];

// Handle item deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $index = intval($_POST['index']);
    if (isset($bookingDetails[$index])) {
        unset($bookingDetails[$index]);
        $bookingDetails = array_values($bookingDetails); // Reindex the array
        $_SESSION['booking'] = $bookingDetails; // Update session
    }
}

// Handle cancel booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel'])) {
    unset($_SESSION['booking']);
    header('Location: home.php');
    exit;
}

$totalPrice = 0;
$discount = 0;
$promoApplied = '';

// Calculate total price and apply promotions
foreach ($bookingDetails as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Apply promotions based on booking details
$hasSingleRoom = false;
$hasDoubleRoom = false;
$hasSuite = false;
$hasBreakfastBuffet = false;
$hasHighSpeedWifi = false;
$hasSpaAccess = false;
$hasParking = false;

foreach ($bookingDetails as $item) {
    switch ($item['name']) {
        case 'Single Bed Room':
            $hasSingleRoom = true;
            break;
        case 'Double Bed Room':
            $hasDoubleRoom = true;
            break;
        case 'Suite':
            $hasSuite = true;
            break;
        case 'Breakfast Buffet':
            $hasBreakfastBuffet = true;
            break;
        case 'High Speed Wifi':
            $hasHighSpeedWifi = true;
            break;
        case 'Spa Access':
            $hasSpaAccess = true;
            break;
        case 'Parking':
            $hasParking = true;
            break;
    }
}

// Determine the applicable promotion
if ($hasSingleRoom && $hasBreakfastBuffet) {
    $discount = 0.05 * $totalPrice;
    $promoApplied = 'Single Room + Breakfast Buffet';
} elseif ($hasSingleRoom && $hasHighSpeedWifi) {
    $discount = 0.05 * $totalPrice;
    $promoApplied = 'Single Room + High Speed Wifi';
} elseif ($hasDoubleRoom && $hasSpaAccess) {
    $discount = 0.10 * $totalPrice;
    $promoApplied = 'Double Room + Spa Access';
} elseif ($hasDoubleRoom && $hasParking) {
    $discount = 0.10 * $totalPrice;
    $promoApplied = 'Double Room + Parking';
} elseif ($hasSuite && $hasBreakfastBuffet && $hasParking) {
    $discount = 0.15 * $totalPrice;
    $promoApplied = 'Suite + Breakfast Buffet + Parking';
} elseif ($hasSuite && $hasBreakfastBuffet && $hasSpaAccess && $hasParking) {
    $discount = 0.20 * $totalPrice;
    $promoApplied = 'Suite + Breakfast Buffet + Spa Access + Parking';
}

$finalPrice = $totalPrice - $discount;

// Store the final price in the session for use in checkout.php
$_SESSION['finalPrice'] = $finalPrice;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Summary</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">
</head>
<body class="loggedin">
    <div class="header">
        <h1>Booking Summary</h1>
    </div>
    <nav class="sidebar">
        <a href="home.php"><img src="" alt="MotelEase Logo"></a>
        <a href="aboutus.html" class="about-us">About us</a>
        <div class="sidebar-bottom">
            <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
    <div class="content">
        <h2>Your Booking Details</h2>
        <div class="grid-container">
            <?php foreach ($bookingDetails as $index => $item): ?>
                <div class="grid-element">
                    <p>Item: <?= htmlspecialchars($item['name']) ?></p>
                    <p>Price: <?= htmlspecialchars($item['price']) ?> PHP</p>
                    <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <input type="submit" name="delete" value="Delete" class="button-item">
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <h3>Total Price: <?= htmlspecialchars($totalPrice) ?> PHP</h3>
        <h3>Discount (<?= htmlspecialchars($promoApplied) ?>): <?= htmlspecialchars($discount) ?> PHP</h3>
        <h3>Final Price: <?= htmlspecialchars($finalPrice) ?> PHP</h3>
        <a href="amenities.php" class="grid-item button-item">Add Amenities</a>
        <a href="room-selection.php" class="grid-item button-item">Add more rooms</a>
        <a href="checkout.php" class="grid-item button-item">Proceed to Checkout</a>
        <form method="post" style="display: inline;">
            <input type="submit" name="cancel" value="Cancel Booking" class="button-item">
        </form>
    </div>
</body>
</html>
