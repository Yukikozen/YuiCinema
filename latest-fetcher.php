<?php

include_once 'conn/config.php';

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

$query = "SELECT * FROM movie ORDER BY id DESC LIMIT 6"; // Retrieve the latest 6 records
$stmt = $conn->prepare($query);

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

while ($fetchr = mysqli_fetch_assoc($result)) {
    echo "<form action='movie.php' method='POST'>";
    echo "<div class='col'>";
    echo "<img src='" . $fetchr['movie_banner'] . "' height='250' width='200' style='margin-top: 30px;margin-left:50px;margin-right:20px;' />";
    echo "<div class='noob'>";

    // Add a hidden input field to pass the movie ID
    echo "<input type='hidden' name='movie_id' value='" . $fetchr['id'] . "' />";

    // Adjust the font-size property to fit the text
    echo "<input type='submit' name='submit' class='btn btn-outline-info' style='display:block;width:200px;height:50px;padding-bottom:15px;margin-bottom:30px;margin-left:50px;margin-right:20px;white-space:normal;font-size:12px;' value='" . ucwords($fetchr['name']) . "'/>";
    echo "</div>";
    echo "</div>";
    echo "</form>";
}