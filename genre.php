<!DOCTYPE html>
<html>
    <head>
<!--<style>
    .movie-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin: 0 -10px;
    }

    .movie-card {
        flex: 0 0 calc(25% - 20px);  /*Adjust the width and margin to create spacing between cards */
        /*margin: 10px;*/
        text-align: center;
        border: 1px solid #ccc;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        padding: 15px;
        margin-bottom: 20px;
          width: 20rem;
          margin-right:40rem;
    }
     .movie-card:hover {
        transform: scale(1.05);
    }

    .movie-image {
        width: 15rem;
                height: 450px; /* Allow the image to scale naturally */

/*        height: 220px;  Set a fixed height to control the image size */
        object-fit: contain; /* Maintain aspect ratio without cropping */
    }

    .movie-button {
        background-color: maroon;
        color: white;
        width: 100%;
        padding: 10px;
        border: none;
        cursor: pointer;
    }
    
     .jumbotron {
        flex-basis: 48%;
        margin-bottom: 0;
        margin-left: 10px;
        padding-right: 20px; /* Add some padding to the right to balance the layout */
    }

    @media screen and (max-width: 768px) {
         .movie-list {
            justify-content: center; /* Center-align movie cards */
            margin: 0; /* Remove negative margin */
        }
        .movie-card {
            flex: 0 0 calc(50% - 20px); /* Adjust the width to have two cards in a row */
             min-width: calc(50% - 20px); /* Set a minimum width for movie cards in smaller screens */
        margin: 10px 0;
                  margin-left:4rem;

        }

        .movie-image {
            height: auto; /* Allow the image to scale naturally */
        }
    }
</style>-->
    </head>
    <?php
    include_once("header.php");
    include_once("conn/config.php");



    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $tbl = "movie";

// Check if the genre ID is provided via POST
    if (isset($_POST['genre_id'])) {
        $genre_id = $_POST['genre_id'];

        // Prepare and execute the statement to retrieve the genre name
        $genre_query = "SELECT genre_name FROM genre WHERE id = ?";
        $genre_stmt = $conn->prepare($genre_query);
        $genre_stmt->bind_param("i", $genre_id);
        $genre_stmt->execute();
        $genre_result = $genre_stmt->get_result();

        if ($genre_result->num_rows > 0) {
            $genre_row = $genre_result->fetch_assoc();
            $genre_name = $genre_row['genre_name'];

            // Prepare and execute the statement to retrieve movies associated with the genre
            $movie_query = "SELECT movie.*, language.lang_name, GROUP_CONCAT(genre.genre_name SEPARATOR ', ') AS genre_names
                        FROM $tbl AS movie
                        LEFT JOIN language ON movie.lang_id = language.id
                        LEFT JOIN movie_genre ON movie.id = movie_genre.movie_id
                        LEFT JOIN genre ON movie_genre.genre_id = genre.id
                        WHERE movie.rdate <= NOW() AND movie_genre.genre_id = ?
                        GROUP BY movie.id";
            $movie_stmt = $conn->prepare($movie_query);
            $movie_stmt->bind_param("i", $genre_id);
            $movie_stmt->execute();
            $movie_result = $movie_stmt->get_result();
            ?>
            <body>
                <div class='jumbotron' style='margin-top: 15px; padding-top: 30px; padding-bottom: 30px;'>
                    <div class='row'>
                        <div class='col'>
                            <div style='overflow-x: auto; white-space: nowrap;'>
                                <?php
                                // Database connection setup
                                include_once 'conn/config.php';
                                $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

                                // Prepare and execute the statement to retrieve all genres
                                $query = "SELECT * FROM genre ORDER BY genre_name ASC";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    echo "<form method='post' action='genre.php'>";
                                    echo "<div class='btn-group' role='group' aria-label='Genres'>";
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<button type='submit' class='btn btn-primary btn-sm mb-2 mr-2' name='genre_id' value='" . $row['id'] . "'>" . $row['genre_name'] . "</button>";
                                    }
                                    echo "</div>";
                                    echo "</form>";
                                } else {
                                    echo "<p>No genres found.</p>";
                                }

                                // Close the database connection
                                mysqli_close($conn);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="mt-5">
                    <h3 class="text-center" style="color: maroon;">Movies in the '<?php echo $genre_name; ?>' Genre</h3>
                    <div class="container">
                        <div class="movie-list">
                            <div class='row'>
                                <?php foreach ($movie_result as $row) : ?>

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

                <?php
            } else {
//                echo "<p>Genre not found.</p>";
                include 'invalid.php';
            }
        } else {
//            echo "<p>Genre ID not provided.</p>";
            include 'invalid.php';
        }

// Close the database connection
//$conn->close();
        include_once("footer.php");
        ?>
    </body>
</html>