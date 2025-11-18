<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Select Genres</title>
</head>

<body>

<h2>Select Genres</h2>

<?php

    $server = "localhost";
    $id = "u9zdvfnnrmrcu";
    $pw = "ThisIsMyPassword";
    $db = "dbni4kw5atlfhg";

    $conn = new mysqli($server, $id, $pw, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT genre_name FROM genre";
    $result = $conn->query($sql);

    // Begin the form that submits selected genres to db2.php
    echo "<form action='db2.php' method='POST'>";

    // Create a checkbox for each genre
    while($row = $result->fetch_assoc()) {
        $g = $row["genre_name"];
        echo "<input type='checkbox' name='genres[]' value='$g'> $g <br>";
    }
    
    // Submit button for the form
    echo "<input type='submit' value='Search Songs'>";
    echo "</form>";

    $conn->close();

?>

</body>
</html>
