<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item = [
        'type' => $_POST['type'],
        'id' => $_POST['id'],
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'quantity' => $_POST['quantity']
    ];

    if (!isset($_SESSION['booking'])) {
        $_SESSION['booking'] = [];
    }

    $_SESSION['booking'][] = $item;
    // echo "<pre>";
    // print_r($_SESSION['booking']);
    // echo "</pre>";
    header('Location: summary.php');
    exit;
}
?>
