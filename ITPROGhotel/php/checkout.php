<?php
$conn = new mysqli('localhost', 'root', '', 'restaurant');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bed_id = $_POST['bed_id'] ?? null;
    $amenity_id = $_POST['amenity_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    session_start();
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($bed_id) {
        $_SESSION['cart'][] = ['type' => 'bed', 'id' => $bed_id, 'quantity' => $quantity];
    } elseif ($amenity_id) {
        $_SESSION['cart'][] = ['type' => 'amenity', 'id' => $amenity_id, 'quantity' => $quantity];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    <?php
    session_start();
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "<p>No items in cart.</p>";
    } else {
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) {
            if ($item['type'] == 'bed') {
                $result = $conn->query("SELECT * FROM beds WHERE id = " . $item['id']);
                $bed = $result->fetch_assoc();
                echo "<p>{$bed['bed_type']} - Quantity: {$item['quantity']} - Price: $" . ($bed['price'] * $item['quantity']) . "</p>";
                $total_amount += $bed['price'] * $item['quantity'];
            } elseif ($item['type'] == 'amenity') {
                $result = $conn->query("SELECT * FROM amenities WHERE id = " . $item['id']);
                $amenity = $result->fetch_assoc();
                echo "<p>{$amenity['amenity_name']} - Quantity: {$item['quantity']} - Price: $" . ($amenity['price'] * $item['quantity']) . "</p>";
                $total_amount += $amenity['price'] * $item['quantity'];
            }
        }

        $discount = 0;
        // Apply discounts if any combo is matched
        // Assume $discount is calculated here based on the combo rules

        $final_amount = $total_amount - $discount;
        echo "<p>Total Amount: $$total_amount</p>";
        echo "<p>Discount: $$discount</p>";
        echo "<p>Final Amount: $$final_amount</p>";
    }
    ?>

    <form method="post" action="finalize_order.php">
        <label for="customer_name">Customer Name:</label>
        <input type="text" name="customer_name" id="customer_name" required>
        <label for="payment">Payment:</label>
        <input type="number" name="payment" id="payment" min="<?php echo $final_amount; ?>" required>
        <button type="submit">Checkout</button>
    </form>
</body>
</html>
