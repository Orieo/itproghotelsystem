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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="loggedin">
    <nav class="navtop">
        <div class="nav-container">
            <div class="logo">
                <a href="home.php"><img src="uploads/motel-ease_logo.png"></a>
            </div>
            <div class="nav-links">
                <a href="aboutus.html" class="nav-link">About us</a>
                <a href="profile.php" class="nav-link"><i class="fas fa-user-circle"></i> Profile</a>
                <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="content">
        <div class="grid-container">
            <div class="grid-element">
            <a href="single-bed.php" class="grid-item button-item">
                <h3>Single Bed</h3>
                <p>Available Rooms: <?php echo count($singleRooms); ?></p>
            </a>
            </div>
            <div class="grid-element">
            <a href="double-bed.php" class="grid-item button-item"> 
                <h3>Double Bed</h3>
                <p>Available Rooms: <?php echo count($doubleRooms); ?></p>
            </a>
            </div>
            <div class="grid-element">
            <a href="suite.php" class="grid-item button-item">
                <h3>Suite</h3>
                <p>Available Rooms: <?php echo count($suiteRooms); ?></p>
            </a>
            </div>
        </div>
    </div>
</body>
</html>
