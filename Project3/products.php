<?php
/*
    session_start() must run before ANY HTML output.
    Products page includes ?v=time() in the navbar, which is PHP code.
    Running session_start() later would break header outputs.
*/
session_start(); 
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Products</title>
<link rel="stylesheet" href="style.css">

<style>
    body {
        text-align: center; /* center page title + navbar */
    }

    .product-card {
        width: 240px;
        border: 1px solid #ccc;
        padding: 10px;
        display: inline-block;
        margin: 10px;
        text-align: center;
        border-radius: 8px;
    }

    .product-card img {
        width: 180px;
        height: 180px;
        object-fit: cover;
    }

    /* This box shows/hides product descriptions */
    .description {
        max-height: 70px;
        overflow-y: auto;      
        margin-top: 8px;
        display: none;
        padding: 6px;
        background: #fafafa;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    /* Align “Add to Cart” and “More” on the same row */
    .button-row {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 8px;
    }

    .button-row form {
        margin: 0;
    }
</style>
</head>

<body>

<div class="navbar" style="display: flex; align-items: center; justify-content: center; gap: 20px;">
    <img src="images/logo.png" alt="Logo" style="width: 60px; height: auto;">
    <a href="products.php">📱 Products</a>
    <a href="cart.php">🛒 Cart</a>
    <a href="orders.php?v=<?php echo time(); ?>">📦 Orders</a>
    <!-- Add timestamp to force a fresh request & prevent caching -->
</div>




<h2>🦋 Welcome to W-Mart!</h2>

<?php
$server = "localhost";
$id = "u9zdvfnnrmrcu";
$pw = "ThisIsMyPassword";
$db = "dbcihigmvdztw3";

$conn = new mysqli($server, $id, $pw, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/*
    Retrieve all products.
    Images are stored in /images/ and DB stores only the filename.
*/
$sql = "SELECT * FROM product";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $pid   = $row['id'];
        $name  = $row['name'];
        $price = $row['price'];
        $desc  = $row['description'];
        $img   = $row['image_url'];   // filename like "iphone.jpg"
?>

<div class="product-card">
    <!-- Load image from /images/ subfolder -->
    <img src="images/<?php echo $img; ?>" alt="<?php echo $name; ?>">

    <h3><?php echo $name; ?></h3>
    <p>$<?php echo $price; ?></p>

    <div class="button-row">
        <!-- Add product to cart -->
        <form method="POST" action="action_additem.php">
            <input type="hidden" name="id" value="<?php echo $pid; ?>">
            <button type="submit">Add to Cart</button>
        </form>

        <!-- Toggle description box (More/Less) -->
        <button type="button" onclick="toggleDesc(<?php echo $pid; ?>, this)">More</button>
    </div>

    <div id="d<?php echo $pid; ?>" class="description">
        <?php echo $desc; ?>
    </div>
</div>

<?php
    }

} else {
    echo "<p>No products found.</p>";
}

$conn->close();
?>

<script>
/*
    Shows/hides the product's description box.
    The button element is passed as "btn" so its text can toggle
    between More and Less without relying on DOM structure.
*/
function toggleDesc(id, btn) {
    const div = document.getElementById("d" + id);

    if (div.style.display === "none" || div.style.display === "") {
        div.style.display = "block";
        btn.innerText = "Less";
    } else {
        div.style.display = "none";
        btn.innerText = "More";
    }
}
</script>

</body>
</html>
