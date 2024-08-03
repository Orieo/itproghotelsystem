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
        $roomId = $item['type'] === 'room' ? $item['id'] : NULL;
        if ($roomId !== NULL) {
            // Check if the room is available
            $stmt = $conn->prepare("SELECT availability FROM rooms WHERE id = ?");
            $stmt->bind_param("i", $roomId);
            $stmt->execute();
            $stmt->bind_result($availability);
            $stmt->fetch();
            $stmt->close();

            if ($availability == 0) {
                // Room is available, proceed with booking
                $stmt = $conn->prepare("INSERT INTO booking_details (booking_id, room_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiid", $bookingId, $roomId, $item['quantity'], $item['price']);
                $stmt->execute();
                $stmt->close();

                // Update room availability to unavailable
                $stmt = $conn->prepare("UPDATE rooms SET availability = 1 WHERE id = ?");
                $stmt->bind_param("i", $roomId);
                $stmt->execute();
                $stmt->close();
            } else {
                // Room is unavailable, redirect to error page or display error message
                $conn->close();
                header('Location: error.php?message=Room+is+unavailable');
                exit;
            }
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
    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
            background-color: #FFEECC;
            color: #333;
        }

        .header {
            background-color: #FF5733;
            color: white;
            padding: 10px 0;
            text-align: center;
            font-size: 2em;
            font-family: 'Poppins', sans-serif; /* Use the Poppins font */
        }

        .navtop {
            background-color: #333;
            color: #FFD8A9;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navtop a {
            color: #FFD8A9;
            text-decoration: none;
            padding: 0 15px;
        }

        .navtop a:hover {
            color: #FFEECC;
        }

        .content {
            text-align: center;
            padding: 20px;
        }

        .content h2 {
            font-size: 2em;
            color: #FF5733;
        }

        .content p {
            font-size: 1.2em;
            color: #333;
        }

        .button-item {
            background-color: #333;
            color: #FFD8A9;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .button-item:hover {
            background-color: #575757;
        }
    </style>
</head>
<body class="loggedin">
    <div class="header">
        Checkout
    </div>
    <nav class="navtop">
        <div>
            <a href="aboutus.html" class="about-us">About us</a>
            <a href="profile.php"><i class="fas fa-user-circle"></i> Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
