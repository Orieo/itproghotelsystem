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
    <link href="summary.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="loggedin">
    <nav class="navtop">
        <div class="nav-container">
            <div class="logo">
                <a href="home.php"><img src="motel-ease_logo.png"></a>
            </div>
            <div class="nav-links">
                <a href="aboutus.html" class="nav-link">About us</a>
                <a href="profile.php" class="nav-link"><i class="fas fa-user-circle"></i> Profile</a>
                <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
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
        <div class="flex-container">
            <div class = "price-breakdown">
                <h3>Total Price: <?= htmlspecialchars($totalPrice) ?> PHP</h3>
                <h3>Discount (<?= htmlspecialchars($promoApplied) ?>): <?= htmlspecialchars($discount) ?> PHP</h3>
                <h3>Final Price: <?= htmlspecialchars($finalPrice) ?> PHP</h3>
            </div>
            <div class="button-container">
                <a href="amenities.php" class="button-item">Add Amenities</a>
                <a href="room-selection.php" class="button-item">Add more rooms</a>
            </div>
        </div>
            <div class="confirmation-buttons">
            <a href="checkout.php" class="button-item">Proceed to Checkout</a>
            <form method="post" >
                <input type="submit" name="cancel" value="Cancel Booking" class="button-item">
            </form>
            </div>
    </div>
</body>
</html>
