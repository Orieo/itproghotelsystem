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
    <title>Suite</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="loggedin">
    <div class="header">
        <div>
            <h1>Suite</h1>
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
                <img src="uploads/Suite.png" alt="Suite" style="max-width: 100%; height: auto;">
                <h3>Luxury Suite</h3>
                <p>Experience the ultimate in luxury with our spacious suite. This room features a king-size bed with premium linens, a separate living area, a mini-fridge, a 4k Ultra HD TV, and a luxurious bathroom with a jacuzzi and complimentary toiletries.</p>
                <p>Price: P20,599 per night</p>
                <p>Availability: Limited Availability</p>
                <button onclick="window.location.href='checkout.php?room=suite'">Book</button>
            </div>
        </div>
        <div class="buttons">
            <button onclick="window.location.href='menu.php'">Go back</button>
        </div>
    </div>
</body>
</html>
