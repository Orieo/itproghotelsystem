<!DOCTYPE html>
<html>
<head>
    <title>Double Bed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: orange;
            color: white;
            text-align: center;
            padding: 10px;
        }
        .container {
            margin: 20px;
        }
        .bed-image {
            width: 100%;
            height: auto;
        }
        .description {
            margin: 20px 0;
            background-color: orange;
            color: white;
            padding: 10px;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
        }
        .buttons button {
            background-color: orange;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Double Beds</h1>
    </div>
    <div class="container">
        <?php
        $conn = new mysqli('localhost', 'root', '', 'restaurant');
        $result = $conn->query("SELECT * FROM beds WHERE bed_type = 'Double Bed'");
        $bed = $result->fetch_assoc();
        ?>
        <img src="path_to_double_bed_image.jpg" class="bed-image" alt="Double Bed">
        <div class="description">
            <p>- Description</p>
            <p>- Price: $<?php echo $bed['price']; ?></p>
            <p>- Availability</p>
            <p>- Blah</p>
            <p>- Blah</p>
        </div>
        <div class="buttons">
            <button onclick="window.location.href='menu.php'">Go back</button>
            <form method="post" action="checkout.php" style="display:inline;">
                <input type="hidden" name="bed_id" value="<?php echo $bed['id']; ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" min="1" value="1">
                <button type="submit">Book</button>
            </form>
        </div>
    </div>
</body>
</html>
