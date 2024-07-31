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
    <title>Suite Room</title>
    <link href="rooms.css" rel="stylesheet" type="text/css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="loggedin">
    <div class="header">
        <div>
            <h1>Suite Bedroom</h1>
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
        <div class = "grid-container">
                <div class = "grid-element">
                <img src = "" alt = "Room Picture"> </a>
                </div>
                <div class = "grid-element">
                <img src = "" alt = "Room Picture"> </a>
                </div>
                <div class = "grid-element">
                <img src = "" alt = "Room Picture"> </a>
                </div>
                <div class = "grid-element">
                <img src = "" alt = "Room Picture"> </a>
                </div>
        </div>

        <div class = "text-container">
            <h3> Description </h3>
            <p> Text here</p> <br>
            <h3> Price </h3>
            <p> Text here</p> <br>
            <h3> Availability </h3>
            <p> Text here</p> <br>
            <h3> Text here </h3>
            <p> Text here</p>
        </div>
    </div>
</body>
</html>
