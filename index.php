<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>-->
   <style>
    /* CSS for the overlay */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent black overlay */
        z-index: 9999; /* Ensure it's above other content */
    }
    
    /* CSS to make the OTP modal appear above the overlay */
    #otpModal {
        z-index: 10000; /* Higher than the overlay */
    }
</style>
    </head>
    <body>
        <?php
        session_start();
        include_once"header.php";
        include_once 'conn/config.php';
        ?>

        <?php

        
        if (isset($_GET['register'])) {
        if ($_GET['register'] === 'success') {
            echo '<script>$(document).ready(function() { $("#registerSuccessModal").modal("show"); });</script>';
        } elseif ($_GET['register'] === 'fail') {
            echo '<script>$(document).ready(function() { $("#registerFailModal").modal("show"); });</script>';
        }
    }


    if (isset($_GET['login'])) {
        if ($_GET['login'] === 'success') {
            echo '<script> $(document).ready(function() { $("#loginSuccessModal").modal("show"); });</script>';
        } elseif ($_GET['login'] === 'fail') {
            echo '<script>$(document).ready(function() { $("#loginFailModal").modal("show"); });</script>';
        }
    }
   

    if (isset($_GET['login'])) {
        if ($_GET['login'] === 'disabled') {
         echo '<script> $(document).ready(function() { $("#loginFailureModal").modal("show"); });</script>';

        }
    }

    ?>
    
        <?php
        $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $resultset = $conn->query("SELECT imgpath,alt FROM slider"); // take name and path from sql cinema_gallery
        $image_count = 0; // initalise count as 0
        $rows = mysqli_fetch_assoc($resultset); //fetch name and path from table
        ?>

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



        <section style="min-height:250px;">

            <div class="jumbotron" style="margin-top: 15px; padding-top: 30px; padding-bottom: 30px;">
                <div class="row">
                    <div class="col">
                        <form action="index.php" method="GET">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="search_query" placeholder="Enter your search query">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                        <div class="search-tags">
<?php
if (isset($_GET['search_query'])) {
    echo "<span class='badge badge-primary'>" . $_GET['search_query'] . "<button class='close'>&times;</button></span>";
}
?>
                        </div>
                        <div class="container">
<?php
if (isset($_GET['search_query'])) {
    $searchQuery = $_GET['search_query'];
//
//                    // Database connection setup
    include_once 'conn/config.php';
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
//
//                    // Prepare and execute the statement to search for movies by name
    $query = "SELECT * FROM movie WHERE name LIKE ?";
    $stmt = $conn->prepare($query);
    $searchQuery = '%' . $searchQuery . '%'; // Add wildcard % to search for partial matches
    $stmt->bind_param("s", $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();
//
    if ($result->num_rows > 0) {
        echo "<h4 style='color: black; font-size: 30px;'>Search Results:</h4>";
        $movies_per_row = 4; // Number of movies to display in each row
        $count = 0;

        echo "<div class='movie-list'>";
        echo "<div class='row'>";
//
        while ($row = $result->fetch_assoc()) {
//                            // Create a container for each movie result with a form
//                            echo "<div class='col-md-3 movie-result'>";
            echo "<div class='col-md-3'>";

            echo "<div class='movie-card'>";

            echo "<form action='movie.php' method='POST'>";
            echo "<input type='hidden' name='movie_id' value='" . $row['id'] . "'>";
            echo "<button type='submit' name='submit' class='btn' style='border: none; background: none; cursor: pointer;'>";

//                            // Display the movie banner image with fixed dimensions
//                            echo "<img src='" . $row['movie_banner'] . "' alt='" . $row['name'] . "' class='movie-banner' />";
            echo "<img src='" . $row['movie_banner'] . "' alt='" . $row['name'] . "' class='movie-image' />";

//                            // Display the movie name
//                            echo "<h3>" . $row['name'] . "</h3>";
            echo "<h3 class='movie-title'>" . $row['name'] . "</h3>";

//                            // Close the button and form
            echo "</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }

        echo "</div>"; // Close the movie-list div
    } else {
        echo "<h4 style='color: black; font-size: 30px;'>No results found.</h4>";
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "<h4 style='color: black; font-size: 30px;'>Recent: No recent searches</h4>";
}
?>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <section style="min-height:450px;">

            <div id="carouselId" class="carousel slide" data-ride="carousel"> 
                <ol class="carousel-indicators">
<?php
foreach ($resultset as $rows) {
    $actives = '';
    if (!$image_count) {
        $actives = 'active';
        $image_count++;
    }
    ?>
                        <li data-target="#carouselExampleIndicators" data-slide-to= "<?= $image_count; ?>" class="<?= $actives; ?>"></li>
                        <?php $image_count++;
                    }
                    ?>
                </ol>

                <div class="carousel-inner" role="listbox">
<?php
$image_count = 0; // set count to 0 
foreach ($resultset as $rows) {
    $actives = '';
    if ($image_count == 0) {
        $actives = 'active';
    }
    ?>
                        <div class="carousel-item <?= $actives; ?>">
                            <img class="d-block-img-fluid" src="<?= $rows['imgpath'] ?>" alt="<?= $rows['alt'] ?>"
                                 style="width: 100%; max-height: 1000px; object-fit: cover;"> <!-- Adjust the height as needed -->
                            <div class="carousel-caption">
                                <h5><?= $rows['alt'] ?></h5>
                            </div>
                        </div>
    <?php
    $image_count++;
}
?>
                </div>

                <a class="carousel-control-prev" href="#carouselId" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselId" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

        </section>

        <section style="min-height:450px;">             
            <div class="jumbotron">
                <h2 style='margin-top:0px;padding-top:0px;'>Latest updated</h2>
                <div class="row">
<?php
include 'latest-fetcher.php';
?>
                </div>
            </div>
            <div class="jumbotron">
                <h2 style="margin-top: 0; padding-top: 0;">All movies</h2>
                <div class="view-all-link">
                    <a href="all_movies.php" style="display: inline-block; margin-right: 10px;">View All Movies</a>
                </div>
<?php include 'fetcher.php'; ?>
            </div>

        </section>

        <!-- Success Modal for Login -->
<div class="modal fade" id="loginSuccessModal" tabindex="-1" role="dialog" aria-labelledby="loginSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginSuccessModalLabel">Login Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                LOGIN SUCCESS!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
        <!-- Fail Modal for Login -->
<div class="modal fade" id="loginFailModal" tabindex="-1" role="dialog" aria-labelledby="loginFailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginFailModalLabel">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Login failed. Please check your input.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

        
        <!-- Success Modal for Registration -->
<div class="modal fade" id="registerSuccessModal" tabindex="-1" role="dialog" aria-labelledby="registerSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerSuccessModalLabel">Registration Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Registration successful!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
        <!-- Fail Modal for Registration -->
<div class="modal fade" id="registerFailModal" tabindex="-1" role="dialog" aria-labelledby="registerFailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerFailModalLabel">Registration Failed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Registration failed. Please check your input.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

        <!-- Login Failure Modal -->
<div class="modal" id="loginFailureModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Login Failure</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="color: red;">Account has been temporary locked for 5 minutes due to multiple failed login attempts</p>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="lockoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Account Locked Duration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
  <div class="modal-body">
        <!--<span class="close" onclick="closeLockoutModal()">&times;</span>-->
        <p>Your account is temporarily locked due to too many failed login attempts. Remaining time: <span id="remainingTime"></span> seconds</p>
           </div>
        </div>
    </div>
</div>
       <script>
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);
        const remainingTime = urlParams.get('remainingTime');
        if (remainingTime) {
            $('#lockoutModal').modal('show');
            $('#remainingTime').text(remainingTime);
        }
    });
</script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchTags = document.querySelector('.search-tags');
                if (searchTags) {
                    searchTags.addEventListener('click', function (event) {
                        if (event.target.classList.contains('close')) {
                            // Remove the search tag when the close button is clicked
                            const tag = event.target.parentNode;
                            tag.parentNode.removeChild(tag);
                            // Clear the search results
                            const searchResults = document.querySelector('.movie-list');
                            if (searchResults) {
                                searchResults.innerHTML = '';
                            }
                            // You can also update the URL or trigger a new search here if needed.
                        }
                    });
                }
            });
        </script>

<script>
    // Check if the show_otp_modal session variable is set to true and the URL parameter is "verifying" or "invalid_otp"
    <?php if (isset($_SESSION['show_otp_modal']) && $_SESSION['show_otp_modal'] === true && (isset($_GET['login']) && ($_GET['login'] === 'verifying' || $_GET['login'] === 'invalid_otp'))) : ?>
    // Display the OTP modal when the page loads
    $(document).ready(function () {
        $('#otpModal').modal('show');
        // Add an overlay to block interactions with the page content
        var overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        document.body.appendChild(overlay);
    });
    <?php endif; ?>
</script>
<?php
include_once("footer.php");
?>
    </body>

</html>


