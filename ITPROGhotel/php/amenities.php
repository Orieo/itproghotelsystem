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
    <link href="amenities.css" rel="stylesheet" type="text/css">
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
                <a href="home.php"><img src="" alt="MotelEase Logo"></a>
            </div>
            <div class="nav-links">
                <a href="aboutus.html" class="nav-link">About us</a>
                <a href="profile.php" class="nav-link"><i class="fas fa-user-circle"></i> Profile</a>
                <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="content">
        <p><a href="home.php" class="back-btn"> ← Return to Main Menu</a></p>
        <h2>Available Amenities</h2>
        <table class="rooms-table">
            <thead>
                <tr>
                    <th>Amenity</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Number of Days/Pax</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($amenities as $amenity): ?>
                    <tr>
                        <td><?= htmlspecialchars($amenity['name']) ?></td>
                        <td><?= htmlspecialchars($amenity['description']) ?></td>
                        <td><?= htmlspecialchars($amenity['price']) ?> PHP</td>
                        <td>
                            <form action="add-to-cart.php" method="post">
                                <input type="hidden" name="type" value="amenity">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($amenity['id']) ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($amenity['name']) ?>">
                                <input type="hidden" name="price" value="<?= htmlspecialchars($amenity['price']) ?>">
                                <input type="number" name="quantity" min="1" max="10" required>                        </td>
                        <td>
                            <input type="submit" value="Add to Cart" class="button-item">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
