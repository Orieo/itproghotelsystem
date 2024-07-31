<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.html');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Double Bed</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="loggedin">
    <div class="header">
        <div>
            <h1>Double Beds</h1>
        </div>
    </div>

    <nav class="sidebar">
        <a href="home.php"><img src="path_to_logo.png" alt="MotelEase Logo"></a>
        <a href="aboutus.php" class="about-us">About us</a>
        <div class="sidebar-bottom">
            <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>

    <div class="content">
        <div class="grid-container">
            <div class="grid-element">
                <img src="uploads/Deluxe-doublebed.png" alt="Room Picture" style="max-width: 100%; height: auto;">
                <h3>Deluxe double bedroom</h3>
                <p>Our deluxe double bedroom is perfect for couples or friends. It features two comfortable beds with memory foam and plush linens, a work desk, and a 1080p Full HD TV for your entertainment.</p>
                <p>Price: $100 per night</p>
                <p>Availability: Available</p>
                <button onclick="window.location.href='checkout.php?room=deluxe_double'">Book</button>
            </div>
            <div class="grid-element">
                <img src="uploads/Luxury-doublebed.png" alt="Room Picture" style="max-width: 100%; height: auto;">
                <h3>Luxury double bedroom</h3>
                <p>Enjoy a luxurious stay in our spacious double bed room. This room offers two premium memory foam beds, a mini-fridge, an 4k Ultra HD TV, and a private bathroom with complimentary toiletries.</p>
                <p>Price: $110 per night</p>
                <p>Availability: Limited Availability</p>
                <button onclick="window.location.href='checkout.php?room=luxury_double'">Book</button>
            </div>
            <div class="grid-element">
                <img src="uploads/Mid-tier-doublebed.png" alt="Room Picture" style="max-width: 100%; height: auto;">
                <h3>Mid-tier double bedroom with a view</h3>
                <p>Our mid-tier double bed room offers comfort and style. It includes two cool foam beds, a reading nook, and a large window with city views.</p>
                <p>Price: $120 per night</p>
                <p>Availability: Fully Booked</p>
                <button onclick="window.location.href='checkout.php?room=mid_tier_double'">Book</button>
            </div>
            <div class="grid-element">
                <img src="uploads/Budget-doublebed.png" alt="Room Picture" style="max-width: 100%; height: auto;">
                <h3>Budget-friendly double bedroom</h3>
                <p>Ideal for budget-conscious travelers, our budget-friendly double bed room includes two comfortable beds, a compact workspace, and an ensuite bathroom.</p>
                <p>Price: $90 per night</p>
                <p>Availability: Available</p>
                <button onclick="window.location.href='checkout.php?room=budget_double'">Book</button>
            </div>
        </div>
        </div>
    </div>
</body>
</html>
