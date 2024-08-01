<!--TODO
    - Consult with group on design
    - Minor adjustments with sidebar (if needed)
    - Adjustments to header (see styles.css)
-->

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
    <title>Home Page</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="loggedin">
    <div class="header">
        <div>
            <h1>MENU</h1>
            <!-- <a href="home.php"></i>Home</a>
            <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a> -->
        </div>
    </div>

    <nav class = "sidebar">
        <a href = "home.php"> <img src = "" alt = "MotelEase Logo"> </a>
        <a href="aboutus.html" class="about-us">About us</a>
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
                    <h3> Room Selection </h3>
                    <p> Book and check the list of available toom types </p>
                    <a href="room-selection.php" class="grid-item button-item">Click Me</a>
                </div>
                <div class = "grid-element">
                    <h3> Summary </h3>
                    <p> Booking Summary <br>
                        - Apply promotions <br>
                        - Summary of selected room/s, amenities and total price 
                    </p>
                    <a href="summary.php" class="grid-item button-item">Click Me</a>
                </div>
                <div class = "grid-element">
                    <h3> Amenities </h3>
                    <p> List of available amenities </p>
                    <a href="amenities.php" class="grid-item button-item">Click Me</a>
                </div>
                <div class = "grid-element">
                    <h3> Checkout </h3>
                    <p> - Payment Options <br>
                        - Confirm/Cancel your booking
                    </p>
                    <a href="checkout.php" class="grid-item button-item">Click Me</a>
                </div>
        </div>
    </div>
</body>
</html>
