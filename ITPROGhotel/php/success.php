<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Successful</title>
    <link href="../styles.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
            background-color: #FFECB3;
            color: #5D4037;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            background-color: #FF5722;
            color: white;
            padding: 20px;
            width: 100%;
            text-align: center;
            font-size: 2.5em;
            font-weight: 600;
        }

        .content {
            margin-top: 50px;
            text-align: center;
        }

        .success-heading {
            color: #FF5722;
            font-size: 2em;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .return-home {
            color: white;
            text-decoration: none;
            font-weight: 600;
            background-color: #FFB300; /* Dark yellow background color */
            padding: 15px 30px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
            font-size: 1.2em;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .return-home:hover {
            background-color: #FFA000; /* Darker hover color */
        }
    </style>
</head>
<body class="loggedin">
    <div class="header">
        Booking Successful
    </div>
    <div class="content">
        <h2 class="success-heading">Thank you for your booking!</h2>
        <p>Your booking has been successfully processed.</p>
        <a href="home.php" class="return-home">Return to Home</a>
    </div>
</body>
</html>
