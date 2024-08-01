<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.html');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'itproghs');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM rooms WHERE type = 'Suite' AND availability = 0");
$stmt->execute();
$result = $stmt->get_result();
$suiteRooms = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Suite Rooms</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">
</head>
<body class="loggedin">
    <div class="header">
        <h1>Suite Rooms</h1>
    </div>
    <nav class="sidebar">
        <a href="home.php"><img src="" alt="MotelEase Logo"></a>
        <a href="aboutus.html" class="about-us">About us</a>
        <div class="sidebar-bottom">
            <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
    <div class="content">
        <h2>Available Suite Rooms</h2>
        <div class="grid-container">
            <?php foreach ($suiteRooms as $room): ?>
                <div class="grid-element">
                    <p>Room Number: <?= htmlspecialchars($room['id']) ?></p>
                    <p>Price per Night: <?= htmlspecialchars($room['price_per_night']) ?> PHP</p>
                    <form action="add-to-cart.php" method="post">
                        <input type="hidden" name="type" value="room">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($room['id']) ?>">
                        <input type="hidden" name="name" value="Single Bed Room">
                        <input type="hidden" name="price" value="<?= htmlspecialchars($room['price_per_night']) ?>">
                        <label for="nights">Number of Nights:</label>
                        <input type="number" name="quantity" min="1" max="30" required>
                        <input type="submit" value="Add to Cart" class="grid-item button-item">
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
