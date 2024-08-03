<?php
session_start();
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'An unknown error occurred.';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Error</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
            background-color: #FFEECC;
            color: #333;
            text-align: center;
            padding: 50px;
        }

        .header {
            background-color: #FF5733;
            color: white;
            padding: 10px 0;
            text-align: center;
            font-size: 2em;
            font-family: 'Poppins', sans-serif; /* Use the Poppins font */
        }

        .content {
            padding: 20px;
        }

        .content h2 {
            font-size: 2em;
            color: #FF5733;
        }

        .content p {
            font-size: 1.2em;
            color: #333;
        }

        .button-item {
            background-color: #333;
            color: #FFD8A9;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .button-item:hover {
            background-color: #575757;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        Error
    </div>
    <div class="content">
        <h2>Booking Error</h2>
        <p><?= $message ?></p>
        <a href="home.php" class="button-item">Return to Home</a>
    </div>
</body>
</html>
