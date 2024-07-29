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
            <h1>Room Selection</h1>
        </div>
    </div>

    <nav class = "sidebar">
        <a href = "home.php"> <img src = "" alt = "MotelEase Logo"> </a>
        <a href="aboutus.php" class="about-us">About us</a>
        <div class = "sidebar-bottom">
            <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>

    <div class="content">
        <!-- <h2>Welcome to MotelEase</h2>
        <p>Container for content <33</p> -->
        <div class = "grid-container">
                <div class = "grid-element">
                    <h3> Single Bed </h3>
                </div>
                <div class = "grid-element">
                    <h3> 2 Single Beds </h3>
                </div>
                <div class = "grid-element">
                    <h3> Double </h3>
                </div>
                <div class = "grid-element">
                    <h3> Suite </h3>
                </div>
        </div>
    </div>
</body>
</html>
