<?php
include_once '../conn/config.php';
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

// Query to count total views per genre
$sql = "SELECT g.genre_name, COUNT(mv.id) AS total_views
        FROM genre g
        LEFT JOIN movie_genre mg ON g.id = mg.genre_id
        LEFT JOIN movie_viewers mv ON mg.movie_id = mv.movie_id
        GROUP BY g.id, g.genre_name";

$result = $conn->query($sql);

$genreViews = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $genreViews[] = [
            'genre' => $row['genre_name'],
            'views' => (int)$row['total_views']
        ];
    }
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($genreViews);
?>
