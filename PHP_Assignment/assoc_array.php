<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Business Hours</title>

<style>
    .hours {
        font-family: Arial, sans-serif;
        width: 250px;
    }
    .day {
        display: inline-block;
        width: 100px;
        font-weight: bold;
    }
    .time {
        display: inline-block;
        width: 120px;
    }
</style>

</head>

<body>
<h1>Business Hours</h1>

<?php

// Array storing the business hours for each day of the week
$hours = [
    "Monday"    => "9am - 4pm",
    "Tuesday"   => "9am - 4pm",
    "Wednesday" => "9am - 4pm",
    "Thursday"  => "10am - 6pm",
    "Friday"    => "10am - 3pm",
    "Saturday"  => "Closed",
    "Sunday"    => "Closed"
];

// Builds HTML that displays each day and its corresponding hours.

function displayHours($arr) {
    $output = "";

    // Loop through each day and time, building HTML blocks
    foreach($arr as $day => $time) {
        $output .= "<div class='hours'>
                        <span class='day'>$day</span>
                        <span class='time'>$time</span>
                    </div>";
    }

    return $output;
}

echo displayHours($hours);

?>

</body>
</html>
