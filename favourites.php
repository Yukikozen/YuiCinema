<!DOCTYPE html>
<html>
<head>
    <title>Favourites</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <?php
    session_start();
    include_once "header.php";
    include_once 'conn/config.php';

    if (!isset($_SESSION['cust_id'])) {
        // Redirect to the login page if the user is not logged in
        header("Location: login.php");
        exit();
    }

    $userId = $_SESSION['cust_id'];

    // Fetch all the user's favorite movies
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $query = "SELECT movie.id, movie.movie_banner, movie.name
              FROM favorites
              INNER JOIN movie ON favorites.movie_id = movie.id
              WHERE favorites.cust_id = ?
              ORDER BY movie.name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($id, $img, $movieName);

    $rows = [];
    while ($stmt->fetch()) {
        $rows[] = ["id" => $id, "movie_banner" => $img, "name" => $movieName];
    }
    ?>

    <section class="mt-5">
        <h3 class="text-center" style="color: maroon;">Favourite Movies</h3>
        <div class="container">
            <div class="movie-list">
                <div class='row' id="movieRow">
                    <?php
                    $initialCount = 10; // Number of movies to display initially
                    for ($i = 0; $i < min(count($rows), $initialCount); $i++) {
                        $row = $rows[$i];
                        echo '<div class="col1">';
                        echo '<div class="movie-card">';
                        echo '<form action="movie.php" method="POST">';
                        echo '<input type="hidden" name="movie_id" value="' . $row['id'] . '">';
                        echo '<button type="submit" name="submit" style="border: none; background: none; cursor: pointer;">';
                        echo '<img src="' . $row['movie_banner'] . '" />';
                        echo '</button>';
                        echo '<h3 class="movie-title1">' . $row['name'] . '</h3>';
                        echo '</form>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <script>
        // JavaScript to handle infinite scrolling
        var displayedCount = <?php echo $initialCount; ?>;
        var totalCount = <?php echo count($rows); ?>;
        var loadCount = 10; // Number of movies to load on each scroll

        window.onscroll = function () {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                loadMoreMovies();
            }
        };

        function loadMoreMovies() {
            var movieRow = document.getElementById('movieRow');

            while (displayedCount < totalCount && displayedCount < (loadCount + displayedCount)) {
                var row = <?php echo json_encode($rows); ?>[displayedCount];
                var movieCard = document.createElement('div');
                movieCard.className = 'col1';
                movieCard.innerHTML = '<div class="movie-card"><form action="movie.php" method="POST">' +
                    '<input type="hidden" name="movie_id" value="' + row.id + '">' +
                    '<button type="submit" name="submit" style="border: none; background: none; cursor: pointer;">' +
                    '<img src="' + row.movie_banner + '" />' +
                    '</button>' +
                    '<h3 class="movie-title1">' + row.name + '</h3>' +
                    '</form></div>';
                movieRow.appendChild(movieCard);
                displayedCount++;
            }
        }
    </script>

    <?php include_once("footer.php"); ?>
</body>
</html>
