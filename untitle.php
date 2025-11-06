<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Connect to Database</title>
</head>

<body>
    <h1>Database Demo</h1>
    <?php
        $server = "localhost";
        $id = "u9zdvfnnrmrcu";
        $pw = "ThisIsMyPassword";
        $db = "dby0lwlhxq89y8";

        $conn = new mysqli($server, $id, $pw);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error); // not for production
        }

        echo "It worked!<br>";
    ?>
</body>
</html>

