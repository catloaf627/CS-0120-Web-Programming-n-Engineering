<?php
session_start();

$server = "localhost";
$id = "u9zdvfnnrmrcu";
$pw = "ThisIsMyPassword";
$db = "dbcihigmvdztw3";

$conn = new mysqli($server, $id, $pw, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* 
   Retrieve all orders for display.
   The assignment does not include user accounts, so all orders are shown.
   Newest orders appear first (DESC).
*/
$order_sql = "SELECT * FROM `order` ORDER BY order_id DESC";
$orders = $conn->query($order_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body { text-align: center; }

        .order-box {
            width: 60%;
            margin: 20px auto;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 8px;
            text-align: left;
        }

        table {
            width: 80%;
            margin: 10px auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #aaa;
            padding: 6px 10px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }

        .back-link {
            display: block;
            margin-top: 25px;
        }
    </style>
</head>

<body>

<div class="navbar">
    <a href="products.php">📱 Products</a>
    <a href="cart.php">🛒 Cart</a>

    <!-- Add timestamp to URL to force a fresh request and bypass caching -->
    <a href="orders.php?v=<?php echo time(); ?>">📦 Orders</a>
</div>

<h2>Order History</h2>

<?php
if ($orders && $orders->num_rows > 0) {

    while ($row = $orders->fetch_assoc()) {

        $order_id = $row['order_id'];
        $date     = $row['order_date'];
        $total    = $row['total_price'];

        echo "<div class='order-box'>";
        echo "<h3>Order #$order_id</h3>";
        echo "<p>Date: $date</p>";
        echo "<p><b>Total Price: $$total</b></p>";

        /*
           Fetch all items belonging to this order.
           JOIN gives access to product name and price.
           order_item links each product to this specific order.
        */
        $item_sql = "
            SELECT p.name, p.price, oi.quantity
            FROM order_item oi
            JOIN product p ON oi.product_id = p.id
            WHERE oi.order_id = $order_id
        ";
        $items = $conn->query($item_sql);

        if ($items && $items->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Product</th><th>Unit Price</th><th>Quantity</th><th>Subtotal</th></tr>";

            while ($item = $items->fetch_assoc()) {
                $name  = $item['name'];
                $price = $item['price'];
                $qty   = $item['quantity'];

                /* 
                   Subtotal is recomputed here instead of trusting stored values.
                   (Ensures consistency even if product prices ever change.)
                */
                $subtotal = $price * $qty;

                echo "<tr>
                        <td>$name</td>
                        <td>$$price</td>
                        <td>$qty</td>
                        <td>$$subtotal</td>
                      </tr>";
            }

            echo "</table>";
        }

        echo "</div>";
    }

} else {
    echo "<p>No orders found.</p>";
}

$conn->close();
?>

<a href="products.php" class="back-link">Back to Products</a>

</body>
</html>
