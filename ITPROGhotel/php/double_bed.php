<!DOCTYPE html>
<html>
<head>
    <title>Double Bed</title>
</head>
<body>
    <h1>Double Bed</h1>
    <?php
    $conn = new mysqli('localhost', 'root', '', 'restaurant');
    $result = $conn->query("SELECT * FROM beds WHERE bed_type = 'Double Bed'");
    $bed = $result->fetch_assoc();
    ?>
    <p>Price: $<?php echo $bed['price']; ?></p>
    <form method="post" action="checkout.php">
        <input type="hidden" name="bed_id" value="<?php echo $bed['id']; ?>">
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" min="1" value="1">
        <button type="submit">Add to Cart</button>
    </form>
</body>
</html>
