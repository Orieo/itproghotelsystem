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
    <title>Room Selection</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="loggedin">
    <div class="header">
        <div>
            <h1>Double Bed</h1>
        </div>
    </div>

    <nav class="sidebar">
        <a href="home.php"> <img src="" alt="MotelEase Logo"> </a>
        <a href="aboutus.html" class="about-us">About us</a>
        <div class="sidebar-bottom">
            <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>

    <div class="content">
        <div class="grid-container">
            <div class="grid-element">
                <img src="path_to_double_bed_image1.png" alt="Room Picture">
            </div>
            <div class="grid-element">
                <img src="path_to_double_bed_image2.png" alt="Room Picture">
            </div>
            <div class="grid-element">
                <img src="path_to_double_bed_image3.png" alt="Room Picture">
            </div>
            <div class="grid-element">
                <img src="path_to_double_bed_image4.png" alt="Room Picture">
            </div>
        </div>

        <div class="text-container">
            <h3>Description</h3>
            <p>Text here</p><br>
            <h3>Description</h3>
            <p>Text here</p><br>
            <h3>Description</h3>
            <p>Text here</p><br>
            <h3>Description</h3>
            <p>Text here</p>
        </div>
    </div>
</body>
</html>
