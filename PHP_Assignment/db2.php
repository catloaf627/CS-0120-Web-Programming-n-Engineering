<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Song Results</title>

<style>
<style>
    body { 
        font-family: Arial, sans-serif; 
        background-color: #f0f3f7;
    }

    .song {
        width: 300px;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #8da9ff;
        border-radius: 6px;
        background-color: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .title {
        font-weight: bold;
        font-size: 18px;
        color: #1a2b6d;
        margin-bottom: 4px;
    }

    .artist {
        font-size: 15px;
        color: #b22222;
    }

    .genre {
        font-size: 13px;
        color: #666;
    }
</style>


</style>

</head>
<body>

<h2>Matching Songs</h2>

<?php
    $server = "localhost";
    $id     = "u9zdvfnnrmrcu";
    $pw     = "ThisIsMyPassword";
    $db     = "dbni4kw5atlfhg";

    $conn = new mysqli($server, $id, $pw, $db);
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

    if (!isset($_POST["genres"])) {
        echo "No genres selected.";
        exit;
    }

    // Build a string like 'Rock','Pop','Jazz' for SQL IN()
    $genres = $_POST["genres"];
    $genreList = "'" . implode("','", $genres) . "'";

    /**
     * SQL Query explanation:
     * - Join song with artist and genre via song_genre (many-to-many)
     * - Filter only songs whose genres match the selected ones
     * - GROUP_CONCAT collects all genres of the same song into a list
     * - GROUP BY ensures each song appears only once
     */

    $sql = "
        SELECT 
            song.song_title,
            artist.artist_name,
            GROUP_CONCAT(genre.genre_name SEPARATOR ', ') AS genres
        FROM song
        JOIN song_genre ON song.song_id = song_genre.song_id
        JOIN genre ON genre.genre_id = song_genre.genre_id
        JOIN artist ON song.artist_id = artist.artist_id
        WHERE genre.genre_name IN ($genreList)
        GROUP BY song.song_id
    ";

    $result = $conn->query($sql);

    // If SQL itself failed, show error
    if (!$result) {
        echo "SQL error: " . $conn->error;
        exit;
    }
    
    // Display results
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='song'>
                    <span class='title'>{$row['song_title']}</span><br>
                    <span class='artist'>{$row['artist_name']}</span><br>
                    <span class='genre'>{$row['genres']}</span>
                </div>";
        }
    } else {
        echo "No songs found for these genres.";
    }

    $conn->close();
?>

</body>
</html>
