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
        <title>Admin Home</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
        <link href="../admin_styles.css" rel="stylesheet" type="text/css">
    </head>
    <body class="loggedin">
        <nav class="navtop">
            <div>
                <h1>MotelEase Admin</h1>
                <a href="admin_home.php">Home</a>
                <a href="admin_profile.php"><i class="fas fa-user-circle"></i>Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </nav>
        <div class="content">
            <div class="button-container">
                <a href="room_management.php" class="button-large"><i class="fas fa-cogs"></i> Room Management</a>
            </div>
            <div class="button-container">
                <a href="user_management.php" class="button-large"><i class="fas fa-cogs"></i> User Management</a>
            </div>
            <div class="button-container">
                <a href="amenities_management.php" class="button-large"><i class="fas fa-cogs"></i> Amenities Management</a>
            </div>
        </div>
    </body>
</html>
