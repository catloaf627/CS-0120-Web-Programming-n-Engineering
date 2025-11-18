<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- Connect to database ---
$server = "localhost";
$id = "u9zdvfnnrmrcu";
$pw = "ThisIsMyPassword";
$db = "dbcihigmvdztw3";

$conn = new mysqli($server, $id, $pw, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_id = $_GET['order_id'];

// Retrieve order details
$sql = "SELECT * FROM `order` WHERE order_id = $order_id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $order = $result->fetch_assoc();
    $total = $order['total_price'];
} else {
    die("Order not found.");
}

// Expected ship date (2 days later)
$ship_date = date('Y-m-d', strtotime('+2 days'));
?>

<!DOCTYPE html>
<html>
<head>
<title>Thank You</title>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">

<style>
    /* Center all the thank-you content */
    .center-box {
        text-align: center;
        margin-top: 40px;
    }

    .center-box p {
        font-size: 18px;
        margin: 8px 0;
    }

    .center-box a {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        background: #007bff;
        color: white;
        padding: 10px 18px;
        border-radius: 6px;
    }

    .center-box a:hover {
        background: #0056b3;
    }
</style>
</head>

<body>

<div class="navbar">
    <a href="products.php">📱 Products</a>
    <a href="cart.php">🛒 Cart</a>
    <a href="orders.php?v=<?php echo time(); ?>">📦 Orders</a>
</div>

<div class="center-box">
    <h2>Thank you for your order!</h2>

    <p><b>Order Number:</b> <?php echo $order_id; ?></p>
    <p><b>Total Cost:</b> $<?php echo $total; ?></p>
    <p><b>Expected Ship Date:</b> <?php echo $ship_date; ?></p>

    <a href="products.php">Back to Products</a>
</div>

</body>
</html>
