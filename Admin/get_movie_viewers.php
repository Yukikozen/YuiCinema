<?php
include_once '../conn/config.php'; // adjust if needed
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

header('Content-Type: application/json');

$sql = "SELECT m.name, COUNT(v.id) AS viewer_count
        FROM movie m
        LEFT JOIN movie_viewers v ON m.id = v.movie_id
        GROUP BY m.id, m.name
        ORDER BY viewer_count DESC";

$result = $conn->query($sql);

$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'movie' => $row['name'],
            'viewers' => (int)$row['viewer_count']
        ];
    }
} else {
    // Return error info for debugging (optional)
    $data = ['error' => $conn->error];
}

echo json_encode($data);
?>
