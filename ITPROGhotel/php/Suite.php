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
    <link href="rooms.css" rel="stylesheet" type="text/css">
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
        <p><a href="room-selection.php" class="back-btn"> ← Return to Room Selection</a></p>
        <h2>Available Suite Rooms</h2>
        <h2></h2>
        <h2>Experience the ultimate in luxury with our spacious suite. This room features a king-size bed with premium linens, a separate living area, a mini-fridge, a 4k Ultra HD smart TV, and a luxurious bathroom with a jacuzzi and high-end complimentary toiletries.</h2>
        <table class="rooms-table">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Room Image</th>
                    <th>Price per Night</th>
                    <th>Number of Nights</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suiteRooms as $room): ?>
                    <tr>
                        <td><?= htmlspecialchars($room['room_number']) ?></td>
                        <td><img src="<?= $room['image'] ?>"width="300"></td>  
                        <td><?= htmlspecialchars($room['price_per_night']) ?> PHP</td>
                        <td>
                            <form action="add-to-cart.php" method="post">
                                <input type="hidden" name="type" value="room">
                                <input type="hidden" name="room_number" value="<?= htmlspecialchars($room['room_number']) ?>">
                                <input type="hidden" name="name" value="Suite Bed Room">
                                <input type="hidden" name="price" value="<?= htmlspecialchars($room['price_per_night']) ?>">
                                <input type="number" name="quantity" min="1" max="30" required>
                        </td>
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
