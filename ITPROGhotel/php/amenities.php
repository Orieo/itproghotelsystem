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
    <meta charset="UTF-8">
    <title>Amenities</title>
    <link href="styles.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            background-color: #d46b08; /* Dark orange */
            color: white;
            width: 200px;
            padding: 20px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar img {
            width: 150px;
            margin-bottom: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            margin: 10px 0;
            font-size: 18px;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #d46b08; /* Dark orange */
            color: white;
            padding: 20px;
            text-align: center;
        }
        .amenities-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .amenity-item {
            background-color: #d46b08; /* Dark orange */
            color: white;
            padding: 20px;
            margin: 10px;
            border-radius: 10px;
            width: 45%;
            text-align: center;
        }
        .amenity-item h3 {
            margin-top: 0;
        }
        .amenity-item p {
            font-size: 16px;
        }
        .amenity-item img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="loggedin">
    <div class="sidebar">
        <a href="home.php"><img src="path_to_logo.png" alt="MotelEase Logo"></a>
        <a href="aboutus.html" class="about-us">About us</a>
        <div class="sidebar-bottom">
            <a href="profile.php"><i class="fas fa-user-circle"></i> Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
    <div class="content">
        <div class="header">
            <h1>Amenities</h1>
        </div>
        <div class="amenities-list">
            <div class="amenity-item">
                <img src="path_to_wifi_image.png" alt="Free Wi-Fi">
                <h3>Free Wi-Fi</h3>
                <p>Enjoy unlimited internet access during your stay.</p>
            </div>
            <div class="amenity-item">
                <img src="path_to_pool_image.png" alt="Swimming Pool">
                <h3>Swimming Pool</h3>
                <p>Relax and unwind in our outdoor swimming pool.</p>
            </div>
            <div class="amenity-item">
                <img src="path_to_gym_image.png" alt="Gym">
                <h3>Gym</h3>
                <p>Stay fit and healthy with access to our gym facilities.</p>
            </div>
            <div class="amenity-item">
                <img src="path_to_breakfast_image.png" alt="Breakfast">
                <h3>Breakfast</h3>
                <p>Complimentary breakfast served every morning.</p>
            </div>
        </div>
    </div>
</body>
</html>
