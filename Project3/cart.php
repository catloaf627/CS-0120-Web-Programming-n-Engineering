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
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Your Cart</title>
<link rel="stylesheet" href="style.css">

<style>
    body {
        text-align: center;  /* Center all top-level content */
    }

    table {
        margin: 20px auto;
        border-collapse: collapse;
        text-align: center;
    }

    th, td {
        padding: 8px 12px;
        border: 1px solid #aaa;
    }

    .empty-cart-box {
        margin-top: 30px;
    }
</style>
</head>

<body>

<div class="navbar">
    <a href="products.php">📱 Products</a>
    <a href="cart.php">🛒 Cart</a>

    <!-- Timestamp added to force fresh request and avoid cached order pages -->
    <a href="orders.php?v=<?php echo time(); ?>">📦 Orders</a>
</div>

<h2>Your Shopping Cart</h2>

<?php
/* 
   If the cart has no items:
   - This can happen when the user hasn't added anything yet OR
     immediately after a checkout clears the session.
*/
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<div class='empty-cart-box'>";
    echo "<p>Your cart is empty.</p>";
    echo "<a href='products.php'>Continue Shopping</a>";
    echo "</div>";
    exit();
}
?>

<table>
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
        <th>Remove</th>
    </tr>

<?php
$total = 0;

/* 
   Loop through all items stored in the session.
   Each key is a product ID; value is the quantity.
*/
foreach ($_SESSION['cart'] as $pid => $qty) {

    // Retrieve product info for this item
    $sql = "SELECT * FROM product WHERE id = $pid";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $name = $row['name'];
    $price = $row['price'];

    // Subtotal recalculated here to ensure accuracy
    $subtotal = $price * $qty;
    $total += $subtotal;
?>
    <tr>
        <td><?php echo $name; ?></td>
        <td><?php echo $qty; ?></td>
        <td>$<?php echo $price; ?></td>
        <td>$<?php echo $subtotal; ?></td>

        <td>
            <!-- Remove a single product from the cart -->
            <form method="POST" action="action_removeitem.php">
                <input type="hidden" name="id" value="<?php echo $pid; ?>">
                <button type="submit">Remove from cart</button>
            </form>
        </td>
    </tr>
<?php
}
?>
</table>

<h3>Order Total: $<?php echo $total; ?></h3>

<!-- Submits the order and clears the session cart -->
<form action="action_checkout.php" method="POST">
    <button type="submit">Check Out</button>
</form>

<br>

<a href="products.php">Continue Shopping</a>

</body>
</html>
