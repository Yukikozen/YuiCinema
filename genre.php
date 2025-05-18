<?php
$pageTitle = "Genre";
include_once("conn/config.php");

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$genre_name = "";
$genre_id = null;

if (isset($_POST['genre_id'])) {
    $genre_id = $_POST['genre_id'];

    $genre_query = "SELECT genre_name FROM genre WHERE id = ?";
    $genre_stmt = $conn->prepare($genre_query);
    $genre_stmt->bind_param("i", $genre_id);
    $genre_stmt->execute();
    $genre_result = $genre_stmt->get_result();

    if ($genre_result->num_rows > 0) {
        $genre_row = $genre_result->fetch_assoc();
        $genre_name = htmlspecialchars($genre_row['genre_name']);
        $pageTitle = $genre_name . " - Yui Cinema";
    } else {
        $pageTitle = "Genre Not Found - Yui Cinema";
    }
} else {
    $pageTitle = "Genre - Yui Cinema";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <!-- You can add your styles here or uncomment your previous CSS -->
    <!--<style> ... your CSS ... </style>-->
</head>
<body>
<?php 
include_once("header.php"); 
?>

<div class='jumbotron' style='margin-top: 15px; padding-top: 30px; padding-bottom: 30px;'>
    <div class='row'>
        <div class='col'>
            <div style='overflow-x: auto; white-space: nowrap;'>
                <?php
                // Fetch and display all genres as buttons
                $query = "SELECT * FROM genre ORDER BY genre_name ASC";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<form method='post' action='genre.php'>";
                    echo "<div class='btn-group' role='group' aria-label='Genres'>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<button type='submit' class='btn btn-primary btn-sm mb-2 mr-2' name='genre_id' value='" . $row['id'] . "'>" . htmlspecialchars($row['genre_name']) . "</button>";
                    }
                    echo "</div>";
                    echo "</form>";
                } else {
                    echo "<p>No genres found.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php if ($genre_id && $genre_name): ?>
    <section class="mt-5">
        <h3 class="text-center" style="color: maroon;">Movies in the '<?php echo $genre_name; ?>' Genre</h3>
        <div class="container">
            <div class="movie-list">
                <div class='row'>
                    <?php
                    // Query movies for the selected genre
                    $movie_query = "SELECT movie.*, language.lang_name, GROUP_CONCAT(genre.genre_name SEPARATOR ', ') AS genre_names
                                    FROM movie
                                    LEFT JOIN language ON movie.lang_id = language.id
                                    LEFT JOIN movie_genre ON movie.id = movie_genre.movie_id
                                    LEFT JOIN genre ON movie_genre.genre_id = genre.id
                                    WHERE movie.rdate <= NOW() AND movie_genre.genre_id = ?
                                    GROUP BY movie.id";
                    $movie_stmt = $conn->prepare($movie_query);
                    $movie_stmt->bind_param("i", $genre_id);
                    $movie_stmt->execute();
                    $movie_result = $movie_stmt->get_result();

                    if ($movie_result->num_rows > 0):
                        while ($row = $movie_result->fetch_assoc()):
                    ?>
                            <div class='col1'>
                                <div class='movie-card' style="width: 20rem; margin-bottom: 20px; border: 1px solid #ccc; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 15px; text-align: center;">
                                    <form action='movie.php' method='POST'>
                                        <input type='hidden' name='movie_id' value='<?php echo $row['id']; ?>'>
                                        <button type='submit' name='submit' style='border: none; background: none; cursor: pointer;'>
                                            <img src='<?php echo htmlspecialchars($row['movie_banner']); ?>' alt='<?php echo htmlspecialchars($row['name']); ?>' style="width: 15rem; height: 450px; object-fit: contain;" />
                                        </button>
                                    </form>
                                    <h3 class="movie-title1"><?php echo htmlspecialchars($row['name']); ?></h3>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    else:
                        echo "<p>No movies found for this genre.</p>";
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </section>
<?php elseif (isset($genre_id) && !$genre_name): ?>
    <?php include 'invalid.php'; ?>
<?php else: ?>
    <p>Please select a genre to see movies.</p>
<?php endif; ?>

<?php 
include_once("footer.php"); 
$conn->close();
?>
</body>
</html>
