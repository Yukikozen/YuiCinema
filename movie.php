<?php
session_start();
include_once "header.php";
include_once 'conn/config.php';

if (isset($_POST['movie_id'])) {
    $movieId = $_POST['movie_id'];

    // Fetch movie details based on the ID from your database
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $query = "SELECT * FROM movie WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $movieId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();

        // Check if the user is logged in
        if (isset($_SESSION['username'])) {
            $person = $_SESSION['username'];
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

            // Check if the user has not already viewed the movie
            $viewerCheckQuery = "SELECT * FROM movie_viewers WHERE username = ? AND movie_id = ?";
            $viewerCheckStmt = $conn->prepare($viewerCheckQuery);
            $viewerCheckStmt->bind_param("si", $person, $movieId);
            $viewerCheckStmt->execute();
            $viewerCheckResult = $viewerCheckStmt->get_result();

            if ($viewerCheckResult->num_rows === 0) {
                // If the user has not viewed the movie, update the viewer count
                $current = $movie['viewers'];
                $newcount = $current + 1;

                $newsql = "UPDATE movie SET viewers = ? WHERE id = ?";
                $stmt1 = $conn->prepare($newsql);
                $stmt1->bind_param("ii", $newcount, $movieId);
                $stmt1->execute();

                // Insert user into movie_viewers table to mark the movie as viewed
                $insertViewerQuery = "INSERT INTO movie_viewers (username, movie_id) VALUES (?, ?)";
                $insertViewerStmt = $conn->prepare($insertViewerQuery);
                $insertViewerStmt->bind_param("si", $person, $movieId);
                $insertViewerStmt->execute();
            }

            // Check if the movie is already in the user's favorites
            $userId = $_SESSION['cust_id'];
            $checkFavoriteQuery = "SELECT * FROM favorites WHERE cust_id = ? AND movie_id = ?";
            $checkFavoriteStmt = $conn->prepare($checkFavoriteQuery);
            $checkFavoriteStmt->bind_param("ii", $userId, $movieId);
            $checkFavoriteStmt->execute();
            $checkFavoriteResult = $checkFavoriteStmt->get_result();

            if ($checkFavoriteResult->num_rows > 0) {
                // Movie is already in favorites, set $isFavorite to true
                $isFavorite = true;
                $favoriteColor = 'red';
                $favoriteAction = 0;
            } else {
                $isFavorite = false;
                $favoriteColor = 'black';
                $favoriteAction = 1;
            }

            if (isset($_POST['action'])) {
                $action = $_POST['action'];
                if ($action == 1) {
                    if (!$isFavorite) {
                        // Add the movie to favorites
                        $addFavoriteQuery = "INSERT INTO favorites (cust_id, movie_id) VALUES (?, ?)";
                        $addFavoriteStmt = $conn->prepare($addFavoriteQuery);
                        $addFavoriteStmt->bind_param("ii", $userId, $movieId);
                        if ($addFavoriteStmt->execute()) {
                            // Movie added to favorites successfully
                            $isFavorite = true;
                            $favoriteColor = 'red';
                            $favoriteAction = 0;

                            echo '<script>
                $(document).ready(function() {
                    $("#addFavSuccessModal").modal("show");
                });
           </script>';
                        } else {
                            // Error adding movie to favorites
                            // Handle the error as needed

                            echo '<script>
                $(document).ready(function() {
                    $("#addFavErrorModal").modal("show");
                });
            </script>';
                        }
                    }
                } elseif ($action == 0) {
                    // Remove the movie from favorites
                    $removeFavoriteQuery = "DELETE FROM favorites WHERE cust_id = ? AND movie_id = ?";
                    $removeFavoriteStmt = $conn->prepare($removeFavoriteQuery);
                    $removeFavoriteStmt->bind_param("ii", $userId, $movieId);
                    if ($removeFavoriteStmt->execute()) {
                        // Movie removed from favorites successfully
                        $isFavorite = false;
                        $favoriteColor = 'black';
                        $favoriteAction = 1;

                        echo '<script>
               $(document).ready(function() {
                    $("#removeFavSuccessModal").modal("show");
                });
            </script>';
                    } else {
                        // Error removing movie from favorites
                        // Handle the error as needed

                        echo '<script>
                $(document).ready(function() {
                    $("#removeFavErrorModal").modal("show");
                });
            </script>';
                    }
                }
            }
        }
  } else {
        // Movie not found, handle this case
        $movie = null;
        include("error_404.php");
    }
} else {
    // Handle the case when 'movie_id' is not set
    $movie = null;
    include("invalid.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <style>
            .movie-details {
                display: flex;
                align-items: center;
                margin-left: 20px;
            }

            .movie-details img {
                max-width: 300px; /* Adjust the max-width as needed */
                height: auto;
                margin-top: 20px; /* Add margin at the top of the image */
                margin-bottom: 20px; /* Add margin at the bottom */
            }

            .movie-info {
                margin-top: 20px;
                margin-left: 20px;
                display: flex;
                flex-direction: column; /* Stack the info vertically */
                align-items: flex-start; /* Align info to the left side */
            }

            .movie-info h2 {
                margin-top: 0;
            }
            .movie-info p {
                word-wrap: break-word; /* Apply word wrap to movie description */
                width:70%;
            }

            /* Add this CSS inside your <style> tag */
            .video-container {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 20px; /* Adjust margin as needed */
                margin-bottom: 20px; /* Add margin at the bottom */
            }

            .video-container iframe {
                max-width: 100%; /* Set iframe width to 100% */
                max-height: 100%; /* Set iframe height to 100% */
                border: none;
            }

            video {
                width: 100%; /* Make the video player width 100% of its container */
                max-width: 100%;
                height: auto; /* Maintain the aspect ratio */
            }
            .text-danger {
                color: red;  /*Set your desired red color */
            }


            @media screen and (max-width: 768px) {
                /* Apply different styles for h2 on screens with max-width 768px */
                .movie-info h2 {
                    font-size: 24px;
                    margin-bottom: 10px;
                    margin-left:4rem;
                }
                .movie-info p{

                    margin-left:4rem;
                }

                .movie-details {
                    flex-direction: column; /* Stack image and info vertically */
                    align-items: center;
                }

                .movie-details img {
                    margin-left: 0; /* Remove margin-left for the image */
                }

                /* Adjust the width and margin for the video container */
                .video-container {
                    max-width: 90%; /* Adjust the maximum width as needed for smaller screens */
                    margin-left: auto; /* Center-align the video container horizontally */
                    margin-right: auto; /* Center-align the video container horizontally */
                }

                .video-container iframe {
                    max-width: 90%; /* Adjust the maximum width as needed for smaller screens */
                    max-height: 50vh; /* Adjust the maximum height as needed for smaller screens */
                }

                video {
                    max-width: 90%; /* Reduce the width for smaller screens */
                    max-height: 100%; /* Further reduce the width for very small screens */
                }
                .favorite-button {
                    margin-left:4rem; /* Add margin at the top of the favorite button */
                }

            }

            @media screen and (max-width: 576px) {
                /* Apply different styles for h2 on screens with max-width 576px */
                .movie-info h2 {
                    font-size: 20px;
                    margin-bottom: 8px;
                }

                iframe{
                    width:400;
                    height:300;
                    margi-left:20px;
                }

                video {
                    max-width: 80%; /* Further reduce the width for very small screens */
                    max-height: 100%; /* Further reduce the width for very small screens */
                }

                @media screen and (min-width: 992px) {
                    /* Shift movie details beside movie_banner on desktop view */
                    .movie-details {
                        flex-direction: row; /* Display image and info side by side */
                        align-items: flex-start;
                    }

                    .movie-details img {
                        margin-left: 0;
                        margin-right: 20px; /* Add margin between image and info */
                    }

                    .movie-info {
                        margin-left: 0;
                    }

                    /*        iframe{
                                width:600px;
                                height:450px;
                            }*/
                }
            </style>
            <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-rHyoN1Wi7a6H5/5u7z3n6KG5B48S9VmLrS9z3Sf5w5R5u5A5v5G5C5O5T5J5l5U5L5" crossorigin="anonymous">-->

        </head>
        <body>
            <!-- Add your header and other content here -->

            <?php if ($movie) : ?>
                <div class="movie-details">
                    <img src="<?php echo $movie['movie_banner']; ?>" />

                    <div class="movie-info">
                        <h2>Movie Name: <?php echo $movie['name']; ?></h2>
                        <p>Release Date: <?php echo $movie['rdate']; ?></p>
                        <p>Description: <?php echo $movie['description']; ?></p>
                        <p>Runtime: <?php echo $movie['runtime']; ?> minutes</p>
                        <p>Views: <?php echo $movie['viewers']; ?></p>

                        <!-- Display favorite/unfavorite button -->
                        <?php if (isset($_SESSION['username'])) : ?>
                            <button type="submit" class="favorite-button" style="color: <?php echo $favoriteColor; ?>"
                                    onclick="toggleFavorite(<?php echo $movieId; ?>, <?php echo $favoriteAction; ?>);">
                                <i class="fa fa-heart"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Larger YouTube video overlay -->
<!-- Add this code inside the movie-details div -->
<div class="video-container">
    <?php if (isset($_SESSION['username'])) : ?>
        <!-- User is logged in, display the movie -->
        <video controls oncontextmenu="return false;" controlsList="nodownload"
               id="movieVideo" onplay="onVideoPlay();" onpause="onVideoPause();">
            <source src="<?php echo $movie['videopath']; ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    <?php else : ?>
        <!-- User is not logged in, display the trailer -->
        <iframe width="1800" height="1000" src="<?php echo $movie['trailer']; ?>" frameborder="0" allowfullscreen></iframe>
    <?php endif; ?>
</div>
            <?php else : ?>
                <!--<p>Movie not found.</p>-->
            <?php endif; ?>

            <!-- Success Modal for Adding Favorite -->
            <div class="modal fade" id="addFavSuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Success</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Movie added to favorites successfully!
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Modal for Adding Favorite -->
            <div class="modal fade" id="addFavErrorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Error</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Error adding movie to favorites.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Modal for Removing Favorite -->
            <div class="modal fade" id="removeFavSuccessModal" tabindex="-1" role="dialog" aria-labelledby="deleteSuccessModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteSuccessModalLabel">Success</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Movie removed from favorites successfully.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Modal for Removing Favorite -->
            <div class="modal fade" id="removeFavErrorModal" tabindex="-1" role="dialog" aria-labelledby="deleteErrorModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteErrorModalLabel">Error</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Error removing movie from favorites.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>




        </body>

        <script>
            function toggleFavorite(movieId, action) {
                // Create a form dynamically and submit it
                const form = document.createElement('form');
                form.method = 'post';
                form.action = 'movie.php'; // Redirect to the same page

                const inputMovieId = document.createElement('input');
                inputMovieId.type = 'hidden';
                inputMovieId.name = 'movie_id';
                inputMovieId.value = movieId;

                const inputAction = document.createElement('input');
                inputAction.type = 'hidden';
                inputAction.name = 'action';
                inputAction.value = action; // Use 1 for add and 0 for remove

                form.appendChild(inputMovieId);
                form.appendChild(inputAction);

                document.body.appendChild(form);
                form.submit();

                // Get the button element by its movieId
                const button = document.querySelector(`button[data-movie-id="${movieId}"]`);

                // Update the button's color and behavior based on the action
                if (action === 1) {
                    // User added the movie to favorites, change button color to red
                    button.style.color = 'red';
                    button.setAttribute('onclick', `toggleFavorite(${movieId}, 0);`);
                } else {
                    // User removed the movie from favorites, change button color to black
                    button.style.color = 'black';
                    button.setAttribute('onclick', `toggleFavorite(${movieId}, 1);`);
                }
            }
        </script>
        <?php
        include_once("footer.php");
        ?>
