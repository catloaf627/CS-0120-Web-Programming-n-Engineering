<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PHP Practice</title>
</head>

<body>
    <h1>Optional Assignment: PHP Practice</h1>
    <?php

        $n = isset($_GET['n']) ? intval($_GET['n']) : 0;

        $fib = [];

        if ($n > 0) {
            $fib[] = 0;
        }
        if ($n > 1) {
            $fib[] = 1;
        }

        // Generate the rest of the Fibonacci sequence up to length n
        for ($i = 2; $i < $n; $i++) {
            $fib[$i] = $fib[$i - 1] + $fib[$i - 2];
        }

        // Prepare result as an associative array
        $result = [
            "length" => $n,
            "fibSequence" => $fib
        ];

        // Return the result in JSON format
        header('Content-Type: application/json');
        echo json_encode($result);
        
    ?>
</body>
</html>

