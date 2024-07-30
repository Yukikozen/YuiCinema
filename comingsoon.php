<!DOCTYPE html>
<html>
    <head>
        <title>Coming Soon</title>
    </head>
    <body>
        <?php
        include_once("header.php");
        include_once 'conn/config.php';

        $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

        $query = "SELECT movie.*, language.lang_name, GROUP_CONCAT(genre.genre_name SEPARATOR ', ') AS genre_names
        FROM movie AS movie
        LEFT JOIN language ON movie.lang_id = language.id
        LEFT JOIN movie_genre ON movie.id = movie_genre.movie_id
        LEFT JOIN genre ON movie_genre.genre_id = genre.id
        WHERE movie.rdate >NOW()
        GROUP BY movie.id
        ORDER BY movie.name ASC";

        $stmt = $conn->prepare($query);


        $result = mysqli_query($conn, $query);
//// Execute the statement
//$stmt->execute();
//
//// Bind result variables
//$stmt->bind_result($id, $name, $imgname);
//$rows = [];
//while ($stmt->fetch()) {
//    $rows[] = ["id" => $id, "name" => $name, "movie_banner" => $imgname];
//}
        ?>


        <!--<body>-->
        <section class="mt-5">
            <h3 class="text-center" style="color: maroon;">Coming Soon</h3>
            <div class="container">
                <div class="movie-list">
                    <div class='row'>
                        <?php foreach ($result as $row) : ?>
                            <div class='col1'>
                                <div class='movie-card'>
                                    <form action='movie.php' method='POST'>
                                        <input type='hidden' name='movie_id' value='<?php echo $row['id']; ?>'>
                                        <button type='submit' name='submit' style='border: none; background: none; cursor: pointer;'>
                                            <img src='<?php echo $row['movie_banner']; ?>' />
                                        </button>
                                    </form>
                                    <h3 class="movie-title1"><?php echo $row['name']; ?></h3>
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
