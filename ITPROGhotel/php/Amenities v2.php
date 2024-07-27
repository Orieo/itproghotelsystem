<!DOCTYPE html>
<html>
<head>
    <title>Amenities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        .amenity {
            background-color: white;
            border: 1px solid orange;
            margin: 10px 0;
            padding: 10px;
        }
        .amenity p {
            margin: 5px 0;
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
        <h1>Amenities</h1>
    </div>
    <div class="container">
        <?php
        $conn = new mysqli('localhost', 'root', '', 'restaurant');
        $result = $conn->query("SELECT * FROM amenities");
        while($amenity = $result->fetch_assoc()):
        ?>
        <div class="amenity">
            <p><?php echo $amenity['amenity_name']; ?> - $<?php echo $amenity['price']; ?></p>
            <form method="post" action="checkout.php" style="display:inline;">
                <input type="hidden" name="amenity_id" value="<?php echo $amenity['id']; ?>">
                <label for="quantity_<?php echo $amenity['id']; ?>">Quantity:</label>
                <input type="number" name="quantity_<?php echo $amenity['id']; ?>" id="quantity_<?php echo $amenity['id']; ?>" min="1" value="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
