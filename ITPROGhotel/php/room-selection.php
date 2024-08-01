<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.html');
    exit;
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'itproghs');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get rooms based on type
function getRoomsByType($type) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE type = ? AND availability = 0");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $rooms = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rooms;
}

// Get available rooms for each type
$singleRooms = getRoomsByType('Single');
$doubleRooms = getRoomsByType('Double');
$suiteRooms = getRoomsByType('Suite');

$conn->close();
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
                <h3>Single Bed</h3>
                <p>Available Rooms: <?php echo count($singleRooms); ?></p>
                <a href="single-bed.php" class="grid-item button-item">Select</a>
            </div>
            <div class="grid-element">
                <h3>Double Bed</h3>
                <p>Available Rooms: <?php echo count($doubleRooms); ?></p>
                <a href="double-bed.php" class="grid-item button-item">Select</a>
            </div>
            <div class="grid-element">
                <h3>Suite</h3>
                <p>Available Rooms: <?php echo count($suiteRooms); ?></p>
                <a href="suite.php" class="grid-item button-item">Select</a>
            </div>
        </div>
    </div>
</body>
</html>
