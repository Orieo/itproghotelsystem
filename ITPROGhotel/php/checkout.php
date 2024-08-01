<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.html');
    exit;
}

// Get the final price from the session
$finalPrice = isset($_SESSION['finalPrice']) ? $_SESSION['finalPrice'] : 0;

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
    $stmt->bind_param("issd", $userId, $customerName, $contactDetails, $finalPrice);
    $stmt->execute();
    $bookingId = $stmt->insert_id;
    $stmt->close();

    // Insert booking details into booking_details table and update room availability
    foreach ($bookingDetails as $item) {
        $roomId = $item['type'] === 'room' ? $item['id'] : NULL; // Use NULL or an appropriate placeholder for non-room items
        if ($roomId !== NULL) {
            $stmt = $conn->prepare("INSERT INTO booking_details (booking_id, room_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $bookingId, $roomId, $item['quantity'], $item['price']);
            $stmt->execute();
            $stmt->close();

            // Update room availability to unavailable
            $stmt = $conn->prepare("UPDATE rooms SET availability = 1 WHERE id = ?");
            $stmt->bind_param("i", $roomId);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Clear booking session
    unset($_SESSION['booking']);
    unset($_SESSION['finalPrice']);

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
        <form method="post" action="">
            <p>Total Price: <?= htmlspecialchars($finalPrice) ?> PHP</p>
            <input type="submit" value="Confirm Booking" class="button-item">
        </form>
    </div>
</body>
</html>
