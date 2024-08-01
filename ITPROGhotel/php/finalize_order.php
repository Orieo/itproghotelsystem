<?php
$conn = new mysqli('localhost', 'root', '', 'restaurant');

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $payment = $_POST['payment'];

    $total_amount = 0;
    foreach ($_SESSION['cart'] as $item) {
        if ($item['type'] == 'bed') {
            $result = $conn->query("SELECT * FROM beds WHERE id = " . $item['id']);
            $bed = $result->fetch_assoc();
            $total_amount += $bed['price'] * $item['quantity'];
        } elseif ($item['type'] == 'amenity') {
            $result = $conn->query("SELECT * FROM amenities WHERE id = " . $item['id']);
            $amenity = $result->fetch_assoc();
            $total_amount += $amenity['price'] * $item['quantity'];
        }
    }

    $discount = 0;
    // Apply discounts if any combo is matched
    // Assume $discount is calculated here based on the combo rules

    $final_amount = $total_amount - $discount;

    if ($payment < $final_amount) {
        echo "Insufficient payment. Please enter a valid amount.";
        exit;
    }

    $conn->query("INSERT INTO orders (customer_name, order_date, total_amount, discount, final_amount) VALUES ('$customer_name', NOW(), $total_amount, $discount, $final_amount)");
    $order_id = $conn->insert_id;

    foreach ($_SESSION['cart'] as $item) {
        $item_id = $item['id'];
        $quantity = $item['quantity'];
        $type = $item['type'];
        $conn->query("INSERT INTO order_items (order_id, item_type, item_id, quantity) VALUES ($order_id, '$type', $item_id, $quantity)");
    }

    echo "Order successful! Change: $" . ($payment - $final_amount);
    session_destroy();
}
?>
