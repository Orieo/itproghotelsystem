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

// Fetch available amenities
$stmt = $conn->prepare("SELECT * FROM amenities");
$stmt->execute();
$result = $stmt->get_result();
$amenities = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Amenities</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">
</head>
<body class="loggedin">
    <div class="header">
        <h1>Amenities</h1>
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
        <h2>Available Amenities</h2>
        <div class="grid-container">
            <?php foreach ($amenities as $amenity): ?>
                <div class="grid-element">
                    <p>Amenity: <?= htmlspecialchars($amenity['name']) ?></p>
                    <p>Description: <?= htmlspecialchars($amenity['description']) ?></p>
                    <p>Price: <?= htmlspecialchars($amenity['price']) ?> PHP</p>
                    <form action="add-to-cart.php" method="post">
                        <input type="hidden" name="type" value="amenity">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($amenity['id']) ?>">
                        <input type="hidden" name="name" value="<?= htmlspecialchars($amenity['name']) ?>">
                        <input type="hidden" name="price" value="<?= htmlspecialchars($amenity['price']) ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" min="1" max="10" required>
                        <input type="submit" value="Add to Cart" class="grid-item button-item">
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
