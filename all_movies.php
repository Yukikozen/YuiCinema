<?php
include_once("header.php");
include_once 'conn/config.php';

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

$query = "SELECT id, name, movie_banner FROM movie ORDER BY name ASC"; // Select all movies
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

<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <section class="mt-5">
            <h3 class="text-center" style="color: maroon;">Movies</h3>
            <div class="container">
                <div class="movie-list">
                    <div class='row'>
                        <?php foreach ($rows as $row) : ?>
                            <div class='col1'>
                                <div class='movie-card'>
                                    <form action='movie.php' method='POST'>
                                        <input type='hidden' name='movie_id' value='<?php echo $row['id']; ?>'>
                                        <button type='submit' name='submit' style='border: none; background: none; cursor: pointer;'>
                                            <img src='<?php echo $row['movie_banner']; ?>' />
                                        </button>
                                        <h3 class="movie-title1"><?php echo $row['name']; ?></h3>

                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    </body>
    <?php
    include_once("footer.php");
    ?>
</html>
