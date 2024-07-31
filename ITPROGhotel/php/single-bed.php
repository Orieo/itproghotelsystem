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
    <title>Single Bed</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="loggedin">
    <div class="header">
        <div>
            <h1>Single Beds</h1>
        </div>
    </div>

    <nav class="sidebar">
        <a href="home.php"><img src="path_to_logo.png" alt="MotelEase Logo"></a>
        <a href="aboutus.html" class="about-us">About us</a>
        <div class="sidebar-bottom">
            <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>

    <div class="content">
        <div class="grid-container">
            <div class="grid-element">
                <img src="uploads/Deluxe.png" alt="Room Picture" style="max-width: 100%; height: auto;">
                <h3>Deluxe single bedroom</h3>
                <p>Perfect for solo travelers seeking comfort and convenience. The room features a comfortable deluxe bed with memory foam and plush linens, a large work desk, a cable TV for your entertainment, and a bigger bathroom.</p>
                <p>Price: P3,490 per night</p>
                <p>Availability: Available</p>
                <button onclick="window.location.href='checkout.php?room=mid_tier_single'">Book</button>
            </div>
            <div class="grid-element">
                <img src="uploads/Luxury.png" alt="Room Picture" style="max-width: 100%; height: auto;">
                <h3>Luxury single bedroom</h3>
                <p>Our best comfortable luxurious single bed room. This room offers a spacious living room and a single bed with comfy premium memory foam and premium plush beddings, a mini-fridge, 4k Ultra HD internet TV, and big shower room with bathtub.</p>
                <p>Price: P6,350 per night</p>
                <p>Availability: Available</p>
                <button onclick="window.location.href='checkout.php?room=mid_tier_single'">Book</button>
            </div>
            <div class="grid-element">
                <img src="uploads/Mid-tier-window-view.png" alt="Room Picture" style="max-width: 100%; height: auto;">
                <h3>Mid-tier single bedroom with a view</h3>
                <p>Designed for comfort and style. It includes a single bed with cool foam mattress, a medium sized reading desk, a toilet with showers, and a large window view.</p>
                <p>Price: P1,950 per night</p>
                <p>Availability: Available</p>
                <button onclick="window.location.href='checkout.php?room=mid_tier_single'">Book</button>
            </div>
            <div class="grid-element">
                <img src="uploads/Budget-friendly.png   " alt="Room Picture" style="max-width: 100%; height: auto;">
                <h3>Budget-friendly single bedroom</h3>
                <p>Ideal for short stays. The room includes a standard single bed, a compact cozy workspace, and a toilet.</p>
                <p>Price: P999 per night</p>
                <p>Availability: Available</p>
                <button onclick="window.location.href='checkout.php?room=mid_tier_single'">Book</button>
            </div>
        </div>
        </div>
    </div>
</body>
</html>
