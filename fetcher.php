<?php
include_once 'conn/config.php';

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

$query = "SELECT id, name, movie_banner FROM movie ORDER BY name ASC LIMIT 6"; // Select top 5 movies
$stmt = $conn->prepare($query);

// Execute the statement
$stmt->execute();

// Bind result variables
$stmt->bind_result($id, $name, $imgname);
$rows = [];
while ($stmt->fetch()) {
    $rows[] = ["id" => $id, "name" => $name, "movie_banner" => $imgname];
}
?>

<style>

</style>

<div class='row'>
    <?php foreach ($rows as $row) : ?>
        <div class='col1'>
            <form action='movie.php' method='POST'>
                <input type='hidden' name='movie_id' value='<?php echo $row['id']; ?>'>
                <button type='submit' name='submit' class='btn' style='border: none; background: none; cursor: pointer;'>
                    <img src='<?php echo $row['movie_banner']; ?>' />
                    <h3 class="movie-title1"><?php echo $row['name']; ?></h3>
                </button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
