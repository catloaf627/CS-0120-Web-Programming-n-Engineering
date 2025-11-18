<?php
session_start();
date_default_timezone_set("America/New_York");   // ensure correct order time

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: cart.php");
    exit();
}

/* Database */
$server = "localhost";
$id = "u9zdvfnnrmrcu";
$pw = "ThisIsMyPassword";
$db = "dbcihigmvdztw3";

$conn = new mysqli($server, $id, $pw, $db);
if ($conn->connect_error) die("Connection failed");

/* Calculate total */
$total = 0;
foreach ($_SESSION['cart'] as $pid => $qty) {
    $price = $conn->query("SELECT price FROM product WHERE id=$pid")
                  ->fetch_assoc()['price'];
    $total += $price * $qty;
}

/* Insert order */
$order_date = date("Y-m-d H:i:s");
$conn->query("INSERT INTO `order` (order_date, total_price)
              VALUES ('$order_date', '$total')");
$order_id = $conn->insert_id;

/* Insert each item */
foreach ($_SESSION['cart'] as $pid => $qty) {
    $conn->query("INSERT INTO order_item (order_id, product_id, quantity)
                  VALUES ($order_id, $pid, $qty)");
}

/* Empty cart + redirect */
$_SESSION['cart'] = [];
header("Location: thankyou.php?order_id=$order_id");
exit();
?>
