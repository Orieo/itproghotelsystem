<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.html');
    exit;
}

// Fetch booking details from session
$bookingDetails = isset($_SESSION['booking']) ? $_SESSION['booking'] : [];

$totalPrice = 0;
foreach ($bookingDetails as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Apply promotions (dummy logic for demonstration)
$discount = 0;
if (count($bookingDetails) > 2) {
    $discount = 0.1 * $totalPrice; // 10% discount for more than 2 items
}

$finalPrice = $totalPrice - $discount;

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
            <?php foreach ($bookingDetails as $item): ?>
                <div class="grid-element">
                    <p>Item: <?= htmlspecialchars($item['name']) ?></p>
                    <p>Price: <?= htmlspecialchars($item['price']) ?> PHP</p>
                    <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <h3>Total Price: <?= htmlspecialchars($totalPrice) ?> PHP</h3>
        <h3>Discount: <?= htmlspecialchars($discount) ?> PHP</h3>
        <h3>Final Price: <?= htmlspecialchars($finalPrice) ?> PHP</h3>
        <a href="checkout.php" class="grid-item button-item">Proceed to Checkout</a>
    </div>
</body>
</html>
