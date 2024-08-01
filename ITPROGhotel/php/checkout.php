<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.html');
    exit;
}

// Debugging session variables
error_reporting(E_ALL);
ini_set('display_errors', 1);
var_dump($_SESSION);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Save booking details to the database
    $conn = new mysqli('localhost', 'root', '', 'itproghs');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userId = $_SESSION['id'];
    $customerName = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
    $contactDetails = $_SESSION['phoneNumber'];
    $bookingDate = date('Y-m-d');
    $bookingDetails = $_SESSION['booking'];

    // Insert booking into bookings table
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, user_name, contact, total_price, booked_at) VALUES (?, ?, ?, ?, NOW())");
    $totalPrice = 0;
    foreach ($bookingDetails as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }
    $stmt->bind_param("issd", $userId, $customerName, $contactDetails, $totalPrice);
    $stmt->execute();
    $bookingId = $stmt->insert_id;
    $stmt->close();

    // Insert booking details into booking_details table
    foreach ($bookingDetails as $item) {
        $stmt = $conn->prepare("INSERT INTO booking_details (booking_id, room_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $bookingId, $item['id'], $item['quantity'], $item['price']);
        $stmt->execute();
        $stmt->close();
    }

    // Clear booking session
    unset($_SESSION['booking']);

    $conn->close();

    header('Location: success.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">
</head>
<body class="loggedin">
    <div class="header">
        <h1>Checkout</h1>
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
        <h2>Confirm Your Booking</h2>
        <form action="checkout.php" method="post">
            <h3>Final Price: <?= htmlspecialchars($totalPrice) ?> PHP</h3>
            <input type="submit" value="Confirm Booking" class="grid-item button-item">
        </form>
    </div>
</body>
</html>