<!DOCTYPE html>
<html>
<head>
    <title>Amenities</title>
</head>
<body>
    <h1>Amenities</h1>
    <?php
    $conn = new mysqli('localhost', 'root', '', 'restaurant');
    $result = $conn->query("SELECT * FROM amenities");
    while($amenity = $result->fetch_assoc()):
    ?>
        <p><?php echo $amenity['amenity_name']; ?> - $<?php echo $amenity['price']; ?></p>
        <form method="post" action="checkout.php">
            <input type="hidden" name="amenity_id" value="<?php echo $amenity['id']; ?>">
            <label for="quantity_<?php echo $amenity['id']; ?>">Quantity:</label>
            <input type="number" name="quantity_<?php echo $amenity['id']; ?>" id="quantity_<?php echo $amenity['id']; ?>" min="1" value="1">
            <button type="submit">Add to Cart</button>
        </form>
    <?php endwhile; ?>
</body>
</html>
