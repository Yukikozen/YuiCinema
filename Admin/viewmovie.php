<style>
    .genre-checkbox {
        display: flex;
        align-items: center;
        margin-bottom: 10px; /* Adjust the value as needed */
        margin-left: 15px;
    }

    .checkbox-input {
        margin-right: 5px; /* Adjust the value as needed */
    }

    .modal-content {
        max-width: 600px; /* Adjust the value as needed */
    }

    .text-wrap {
        white-space: normal !important;
    }
    
    
    /* Add styles for the table container */
    .table-container {
        overflow-x: auto; /* Enable horizontal scrolling */
        max-width: 100%; /* Ensure it doesn't extend beyond the viewport width */
    }

    /* Default styles for larger screens */
    .table th,
    .table td,
    .table img {
        font-size: 16px;
        padding: 10px;
    }

    /* Media query for smaller screens */
    @media (max-width: 768px) {
        .table th,
        .table td,
        .table img {
            font-size: 14px;
            padding: 8px;
        }
    }
    
</style>

<?php
session_start();
include_once '../conn/config.php';

if (empty($_SESSION["admin_username"])) {
    header("Location:index.php");
} else {
    include_once("admin_header.php");

    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    // Prepare and execute the statement to retrieve all genres
    $query = "SELECT id, genre_name FROM genre";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch genres and store them in an array
    $genres = array();
    while ($row = $result->fetch_assoc()) {
        $genres[] = $row;
    }

    // Close the statement and connection
    $stmt->close();
    mysqli_close($conn);
}

// Assuming you have a database connection
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

// Prepare and execute the statement to retrieve all languages
$query = "SELECT id, lang_name FROM language";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Fetch languages and store them in an array
$languages = array();
while ($row = $result->fetch_assoc()) {
    $languages[] = $row;
}

// Close the statement and connection
$stmt->close();
mysqli_close($conn);

if (isset($_POST['btn_addmovie'])) {
    $movieName = $_POST['movieName'];
    $movieDescription = $_POST['movieDescription'];
    $movieDuration = $_POST['movieDuration'];
    $releaseDate = $_POST['releaseDate'];
    $languageId = $_POST['languageId'];
    $trailer = $_POST['trailer'];
//    $movievideo = $_POST['movievideo'];
    // Get the selected genre IDs
    $selectedGenres = isset($_POST['genreIds']) ? $_POST['genreIds'] : array();

    // Convert the array of selected genre IDs to a comma-separated string
    $genreId = implode(",", $selectedGenres);

    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    // Check if the movie name already exists in the database
    $check_query = "SELECT id FROM movie WHERE name = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param('s', $movieName);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Movie name already exists, handle accordingly (e.g., show error message)
        $error_message = "Movie with the same name already exists in the database.";
    } else {
        $imgpath = ""; // Initialize the image path

        if ($_FILES['fileToUpload']['name'] !== "") {
            $target_dir = "../images/movie/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 5000000) {
                $uploadOk = 0;
            }

            // Allow only certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $uploadOk = 0;
            }

            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $imgpath = "images/movie/" . $_FILES["fileToUpload"]["name"];
} else {
    echo "File upload failed: " . $_FILES["fileToUpload"]["error"];
}
            }
        }
        
        // Initialize $videopath with an empty value
$videopath = "";
// Check if a new video file is selected
if ($_FILES['videoToUpload']['name'] !== "") {
    $target_dir1 = "../videos/"; // Adjust the target directory for video uploads
    $target_file1 = $target_dir1 . basename($_FILES["videoToUpload"]["name"]);
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION));

    // Check if the uploaded file is a valid video file (you can add more video formats as needed)
    if ($videoFileType != "mp4" && $videoFileType != "avi" && $videoFileType != "mov") {
        $uploadOk = 0;
    }

    // Check file size (adjust the file size limit as needed)
    if ($_FILES["videoToUpload"]["size"] > 10000000000) {
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        // Delete old video file if it exists
        if (!empty($old_movie_video)) {
            $old_video_path = "../" . $old_movie_video;
            if (file_exists($old_video_path)) {
                unlink($old_video_path);
            }
        }

        // Debugging video file upload
//        echo "Target Directory: " . $target_dir1 . "<br>";
//        echo "Target File: " . $target_file1 . "<br>";

        if (move_uploaded_file($_FILES["videoToUpload"]["tmp_name"], $target_file1)) {
            $videopath = "videos/" . $_FILES["videoToUpload"]["name"];
        } else {
            echo "Video upload failed: " . $_FILES["videoToUpload"]["error"];
        }
    }
}

        $insert_query = "INSERT INTO movie (name, description, runtime, rdate, movie_banner, lang_id,trailer, videopath) VALUES (?, ?, ?, ?, ?, ?,?,?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param('ssississ', $movieName, $movieDescription, $movieDuration, $releaseDate, $imgpath, $languageId,$trailer,$videopath);

        if ($insert_stmt->execute()) {
            $movieId = $insert_stmt->insert_id;

            // Insert records into the movie_genre table for each selected genre
            foreach ($selectedGenres as $selectedGenreId) {
                $genreInsertQuery = "INSERT INTO movie_genre (movie_id, genre_id) VALUES (?, ?)";
                $genreInsertStmt = $conn->prepare($genreInsertQuery);
                $genreInsertStmt->bind_param('ii', $movieId, $selectedGenreId);
                $genreInsertStmt->execute();
                $genreInsertStmt->close();
            }

            // Success, upload the image and refresh the page or redirect
            echo '<script>
                $(document).ready(function() {
                    $("#addMovieSuccessModal").modal("show");
                });
            </script>';
            } else {
                // Error, handle accordingly
                echo '<script>
                $(document).ready(function() {
                    $("#addMovieErrorModall").modal("show");
                });
            </script>';
            
        }

        $insert_stmt->close();
    }

    $conn->close();
}

if (isset($_POST['btn_edit_movie'])) {
    $movieId = $_POST['movie_id'];
    $newMovieName = $_POST['edit_movie_name'];
    $newMovieDescription = $_POST['movieDescription'];
    $newMovieDuration = $_POST['movieDuration'];
    $newReleaseDate = $_POST['releaseDate'];
    $newGenreIds = isset($_POST['editGenreIds']) ? $_POST['editGenreIds'] : array();
    $newLanguageId = $_POST['editLanguageId'];
    $trailer= $_POST['trailer'];
//    $movievideo=$_POST['movievideo'];

    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    
    // Delete existing genre associations
    $deleteGenreQuery = "DELETE FROM movie_genre WHERE movie_id = ?";
    $deleteGenreStmt = $conn->prepare($deleteGenreQuery);
    $deleteGenreStmt->bind_param('i', $movieId);
    $deleteGenreStmt->execute();
    $deleteGenreStmt->close();

    // Insert new genre associations
    foreach ($newGenreIds as $genreId) {
        $insertGenreQuery = "INSERT INTO movie_genre (movie_id, genre_id) VALUES (?, ?)";
        $insertGenreStmt = $conn->prepare($insertGenreQuery);
        $insertGenreStmt->bind_param('ii', $movieId, $genreId);
        $insertGenreStmt->execute();
        $insertGenreStmt->close();
    }
    
    $check_query = "SELECT id FROM movie WHERE name = ? AND id != ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param('si', $newMovieName, $movieId);
    $check_stmt->execute();
    $check_stmt->store_result();

  if ($check_stmt->num_rows > 0) {
        $error_message = "Movie name already exists in the database.";
    } else {

        $imgpath = ""; // Initialize the imgpath variable
        $old_movie_banner = ""; // Initialize the old_movie_banner variable
        
        
        // Retrieve the existing movie_banner from the database
        $select_query = "SELECT movie_banner FROM movie WHERE id = ?";
        $select_stmt = $conn->prepare($select_query);
        $select_stmt->bind_param('i', $movieId);
        $select_stmt->execute();
        $select_stmt->bind_result($old_movie_banner);
        $select_stmt->fetch();
        $select_stmt->close();

        // Check if a new image is selected
        if ($_FILES['fileToUpload']['name'] !== "") {
            $target_dir = "../images/movie/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a valid image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
            // ... (rest of the file validation checks)
            // Check if file already exists
                if (file_exists($target_file)) {
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["fileToUpload"]["size"] > 5000000) {
                    $uploadOk = 0;
                }

                // Allow only certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $uploadOk = 0;
                }
                
                
                
                if ($uploadOk == 1) {
                // Delete old movie_banner if it exists
                if (!empty($old_movie_banner)) {
                    $old_image_path = "../" . $old_movie_banner;
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }

                // Move the uploaded image to the target directory
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $imgpath = "images/movie/" . $_FILES["fileToUpload"]["name"];
                } else {
                    echo "File upload failed: " . $_FILES["fileToUpload"]["error"];
                }
            }
        } else {
            // No new image selected, use the existing movie_banner
            $imgpath = $old_movie_banner;
        }   
        
// Initialize $old_movie_video with an empty value
$old_movie_video = "";

// Retrieve the existing video path from the database
$select_video_query = "SELECT videopath FROM movie WHERE id = ?";
$select_video_stmt = $conn->prepare($select_video_query);
$select_video_stmt->bind_param('i', $movieId);
$select_video_stmt->execute();
$select_video_stmt->bind_result($old_movie_video);
$select_video_stmt->fetch();
$select_video_stmt->close();

// Initialize $videopath with an empty value
$videopath = "";

// Check if a new video file is selected
if ($_FILES['videoToUpload']['name'] !== "") {
    $target_dir1 = "../videos/"; // Adjust the target directory for video uploads
    $target_file1 = $target_dir1 . basename($_FILES["videoToUpload"]["name"]);
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION));

    // Check if the uploaded file is a valid video file (you can add more video formats as needed)
    if ($videoFileType != "mp4" && $videoFileType != "avi" && $videoFileType != "mov") {
        $uploadOk = 0;
    }

    // Check file size (adjust the file size limit as needed)
    if ($_FILES["videoToUpload"]["size"] > 10000000000) {
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        // Delete old video file if it exists
        if (!empty($old_movie_video)) {
            $old_video_path = "../" . $old_movie_video;
            if (file_exists($old_video_path)) {
                unlink($old_video_path);
            }
        }

        // Debugging video file upload
        // echo "Target Directory: " . $target_dir1 . "<br>";
        // echo "Target File: " . $target_file1 . "<br>";

        if (move_uploaded_file($_FILES["videoToUpload"]["tmp_name"], $target_file1)) {
            $videopath = "videos/" . $_FILES["videoToUpload"]["name"];
        } else {
            echo "Video upload failed: " . $_FILES["videoToUpload"]["error"];
        }
    }
} else {
    // No new video file selected, use the existing video path
    $videopath = $old_movie_video;
}
    


// Output $videopath for debugging
//echo "Video Path: " . $videopath . "<br>";
        // Update movie details in the database
        $updateQuery = "UPDATE movie SET name=?, movie_banner=?, description=?, runtime=?, rdate=?, lang_id=?,trailer=?, videopath=? WHERE id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('sssisissi', $newMovieName, $imgpath, $newMovieDescription, $newMovieDuration, $newReleaseDate, $newLanguageId,$trailer, $videopath, $movieId);

        if ($stmt->execute()) {
            // Success, refresh the page
            echo '<script>
                $(document).ready(function() {
                    $("#editSuccessModal").modal("show");
                });
            </script>';
        } else {
            echo '<script>
                $(document).ready(function() {
                    $("#editErrorModal").modal("show");
                });
            </script>';
        }

        $stmt->close();
    }
         $conn->close();
    
    }


if (isset($_POST['btn_delete_movie'])) {
    $deleteMovieId = $_POST['delete_movie_id'];

    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    // Get the movie image path
    $imagePathQuery = "SELECT movie_banner FROM movie WHERE id = ?";
    $imagePathStmt = $conn->prepare($imagePathQuery);
    $imagePathStmt->bind_param('i', $deleteMovieId);
    $imagePathStmt->execute();
    $imagePathStmt->bind_result($movieImagePath);
    $imagePathStmt->fetch();
    $imagePathStmt->close();

    // Delete the movie image from the file system
    if (!empty($movieImagePath)) {
        $fullImagePath = "../" . $movieImagePath;
        if (file_exists($fullImagePath)) {
            unlink($fullImagePath);
        }
    }

    // Delete records from movie_genre table linked to the deleted movie
    $deleteGenreLinkQuery = "DELETE FROM movie_genre WHERE movie_id = ?";
    $deleteGenreLinkStmt = $conn->prepare($deleteGenreLinkQuery);
    $deleteGenreLinkStmt->bind_param('i', $deleteMovieId);
    $deleteGenreLinkStmt->execute();
    $deleteGenreLinkStmt->close();

    // Delete the movie from the database
    $deleteMovieQuery = "DELETE FROM movie WHERE id = ?";
    $deleteMovieStmt = $conn->prepare($deleteMovieQuery);
    $deleteMovieStmt->bind_param('i', $deleteMovieId);
    
    if ($deleteMovieStmt->execute()) {
        // Success, refresh the page or redirect
        echo '<script>
            $(document).ready(function() {
                $("#deleteSuccessModal").modal("show");
            });
        </script>';
    } else {
        // Error, handle accordingly
        echo '<script>
            $(document).ready(function() {
                $("#deleteErrorModal").modal("show");
            });
        </script>';
    }

    $deleteMovieStmt->close();
    $conn->close();
}

?>

  <section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2" style="background-color: maroon;">
                <?php include('admin_sidenavbar.php'); ?>
            </div>
            <div class="col-md-10">
                <h5 class="text-center mt-2" style="color: maroon;">Movie Lists</h5>
                <!--<a href="addlanguage.php">Add Language</a>-->
                <div class="table-container">

                <table class="table mt-5" border="1">
                    <thead style="background-color: maroon; color: white;">
                      
                        <?php
                        // Get the current page number from the URL query parameter
                        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                        // Define the number of records per page
                        $records_per_page = 10;

                        // Calculate the offset for SQL query
                        $offset = ($current_page - 1) * $records_per_page;

                        // Prepare the statement
                        $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

                        // Prepare the statement
                        $query = "SELECT movie.id, movie.name, movie_banner, description, rdate, GROUP_CONCAT(movie_genre.genre_id) AS genre_ids, GROUP_CONCAT(genre.genre_name) AS genre_names, language.lang_name, runtime, viewers, trailer, videopath 
                            FROM movie 
                            JOIN movie_genre ON movie.id = movie_genre.movie_id
                            JOIN genre ON movie_genre.genre_id = genre.id
                            JOIN language ON movie.lang_id = language.id
                            GROUP BY movie.id
                            LIMIT ?,?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param('ii', $offset, $records_per_page);
                        $stmt->execute();

                        // Fetch data
                        $stmt->bind_result($id, $name, $banner, $des, $rd, $gid, $gname, $lname, $rt, $v, $t, $vp);

                        $rows = [];
                        while ($stmt->fetch()) {
                            $rows[] = [
                                "id" => $id,
                                "name" => $name,
                                "movie_banner" => $banner,
                                "description" => $des,
                                "rdate" => $rd,
                                "genre_ids" => $gid,
                                "genre_names" => $gname, // This will be a comma-separated list of genre names
                                "lang_name" => $lname,
                                "runtime" => $rt,
                                "viewers" => $v,
                                "trailer" => $t,
                                "videopath" => $vp,
                            ];
                        }

                        // Count total records
                        $total_records = mysqli_query($conn, "SELECT COUNT(*) FROM movie")->fetch_row()[0];

                        // Calculate total pages
                        $total_pages = ceil($total_records / $records_per_page);
                        ?>

                        <tr>
                            <th>ID</th>
                            <th>Movie Banner</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Release Date</th>
                            <th>Genre Name</th>
                            <th>Language Name</th>
                            <th>Runtime</th>
                            <th>Viewers</th>
                            <th>Trailer</th>
                            <th>Video URL</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($rows)) {
                            echo '<tr><td colspan="11">No records found.</td></tr>';
                        } else {
                            foreach ($rows as $row) {
                                echo "<tr>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td><img src='../{$row['movie_banner']}' width='200rem'></td>";
                                echo "<td>{$row['name']}</td>";
                                echo "<td>{$row['description']}</td>";
                                echo "<td>{$row['rdate']}</td>";

                                // Add spacing between genre names
                                $genreNamesWithSpacing = str_replace(",", ", ", $row['genre_names']);
                                echo "<td>{$genreNamesWithSpacing}</td>";

                                echo "<td>{$row['lang_name']}</td>";
                                echo "<td>{$row['runtime']}</td>";
                                echo "<td>{$row['viewers']}</td>";
                                echo "<td>{$row['trailer']}</td>";
                                echo "<td>{$row['videopath']}</td>";
                                echo "<td>";
                                //    echo "<button class='btn btn-primary' data-toggle='modal' data-target='#editModal_{$row['id']}'>Edit</button> | ";
                                echo "<button class='btn btn-primary' data-toggle='modal' data-target='#editModal_{$row['id']}'>Edit</button> | ";
                                echo "<button class='btn btn-danger' data-toggle='modal' data-target='#deleteModal_{$row['id']}'>Delete</button>";
                                // Hidden input for existing banner path
                                echo "<input type='hidden' id='existingBannerPath_{$row['id']}' value='{$row['movie_banner']}'>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                    <?php
                    $stmt->close();
                    $conn->close();
                    ?>
                </table>
                </div>

                <div class="pagination d-flex justify-content-center col-md-12">
                    <?php
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<span class="page-number"><a href="?page=' . $i . ' ">' . $i . '</a> ';
                    }
                    ?>
                </div>
                <br>
                <div class="col-md-12 text-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addMovieModal">Add Movie</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Slider Modal -->
<div class="modal fade" id="addMovieModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <h5 class="modal-title">Add Movie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add your form fields for adding a movie here -->
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fileToUpload">Movie Banner</label><br>
                        <img id="imagePreview" src="#" alt="Selected Image" style="max-width: 100%; display: none;"><br><br>
                        <input type="file" name="fileToUpload" id="fileToUpload" required onchange="previewImage(event)">
                    </div>
                    
                    <div class="form-group">
                        <label for="movieName">Movie Name</label>
                        <input type="text" class="form-control" id="movieName" name="movieName" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="movieDescription">Movie Description</label>
                        <textarea class="form-control" id="movieDescription" name="movieDescription" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="movieDuration">Movie Duration (minutes)</label>
                        <input type="number" class="form-control" id="movieDuration" name="movieDuration" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="releaseDate">Release Date</label>
                        <input type="date" class="form-control" id="releaseDate" name="releaseDate" required>
                    </div>
                    

<div class="form-group">
    <label>Genre</label>
    <div class="row">
        <?php foreach ($genres as $genre) { ?>
            <div class="col-md-3">
                <div class="genre-checkbox">
                    <input class="form-check-input checkbox-input" type="checkbox" name="genreIds[]" value="<?php echo $genre['id']; ?>" id="genre-<?php echo $genre['id']; ?>">
                    <label class="form-check-label text-wrap" for="genre-<?php echo $genre['id']; ?>">
                        <?php echo $genre['genre_name']; ?>
                    </label>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
                    
                    <div class="form-group">
                        <label for="languageId">Language</label>
                        <select class="form-control" id="languageId" name="languageId" required>
                            <?php foreach ($languages as $language) { ?>
                                <option value="<?php echo $language['id']; ?>"><?php echo $language['lang_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="movieName">Trailer URL</label>
                        <input type="text" class="form-control" id="movieName" name="trailer" required>
                    </div>
                    
                    <!-- Add Movie Video Input Here -->
      <!-- Display the old video from the database -->
<video width="320" height="240" controls>
    <source src="<?php echo $row['videopath']; ?>" type="video/mp4">
    Your browser does not support the video tag.
</video><br><br>

<!-- Input for uploading a new video -->
<label for="videoToUpload">Upload New Video</label><br><br>
<input type="file" name="videoToUpload" id="videoToUpload" accept=".mp4, .avi, .mov"><br><br><br>
                    <button type="submit" name="btn_addmovie" class="btn btn-primary">Add Movie</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
   
<!-- Add Movie Success Modal -->
<div class="modal fade" id="addMovieSuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Movie added successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Movie Error Modal -->
<div class="modal fade" id="addMovieErrorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Movie already exists. Please choose a different movie.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
$movieGenresMap = array(); // Initialize an associative array to map movie IDs to their genre arrays

foreach ($rows as $row) {
    // Retrieve associated genre IDs for the current movie
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    $movieGenresQuery = "SELECT genre_id FROM movie_genre WHERE movie_id = ?";
    $movieGenresStmt = $conn->prepare($movieGenresQuery);
    $movieGenresStmt->bind_param('i', $row['id']);
    $movieGenresStmt->execute();
    $movieGenresResult = $movieGenresStmt->get_result();

    $movieGenres = array();
    while ($genreRow = $movieGenresResult->fetch_assoc()) {
        $movieGenres[] = $genreRow['genre_id'];
    }

    $movieGenresMap[$row['id']] = $movieGenres; // Map the movie ID to its genre array

    $movieGenresStmt->close();
}
?>

<?php foreach ($rows as $row) : ?>

<div class="modal fade" id="editModal_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <h5 class="modal-title">Edit Movie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <!-- Display existing movie banner -->
                        <img src="../<?php echo $row['movie_banner']; ?>" alt="<?php echo $row['name']; ?>" width="250rem"><br><br>
                        
                        <!-- Input for new movie banner -->
                        <label for="fileToUpload">New Movie Banner</label><br>
                        <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
                        
                        <!-- Movie Name input -->
                        <label for="edit_movie_name">Movie Name</label>
                        <input type="text" class="form-control" id="edit_movie_name" name="edit_movie_name" value="<?php echo $row['name']; ?>" required>
                    </div>
                    
                    <label for="movieDescription">Movie Description</label>
                     <!--Populate the textarea with the existing movie description--> 
                    <textarea class="form-control" id="movieDescription" name="movieDescription" rows="3" required><?php echo $row['description']; ?></textarea>
              
                    
                    <label for="movieDuration">Movie Duration (minutes)</label>
                     <!--Populate the input with the existing movie duration--> 
                    <input type="number" class="form-control" id="movieDuration" name="movieDuration" value="<?php echo $row['runtime']; ?>" required>
                    
                    
                    <label for="releaseDate">Release Date</label>
                     <!--Populate the input with the existing release date--> 
                    <input type="date" class="form-control" id="releaseDate" name="releaseDate" value="<?php echo $row['rdate']; ?>" required>
                    
                    
                     <!-- Edit Genre -->
                        <div class="form-group">
                            <label>Edit Genre</label>
                            <div class="row">
                                <?php foreach ($genres as $genre) { ?>
                                    <div class="col-md-3">
                                        <div class="genre-checkbox">
                                            <?php
                                            $isChecked = in_array($genre['id'], $movieGenresMap[$row['id']]) ? "checked" : "";
                                            ?>
                                            <input class="form-check-input checkbox-input" type="checkbox" name="editGenreIds[]" value="<?php echo $genre['id']; ?>" id="edit-genre-<?php echo $genre['id']; ?>" <?php echo $isChecked; ?>>
                                            <label class="form-check-label text-wrap" for="edit-genre-<?php echo $genre['id']; ?>">
                                                <?php echo $genre['genre_name']; ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                     
                     <!-- Language selection -->
<div class="form-group">
    <label for="editLanguageId">Language</label>
    <select class="form-control" id="editLanguageId" name="editLanguageId" required>
        <?php foreach ($languages as $language) { ?>
            <option value="<?php echo $language['id']; ?>"
                <?php
                // Check if the language id matches the lang_id from the current movie
                if (isset($row['lang_id']) && $language['id'] == $row['lang_id']) {
                    echo 'selected'; // Add the 'selected' attribute to the option
                }
                ?>>
                <?php echo $language['lang_name']; ?>
            </option>
        <?php } ?>
    </select>
</div>
                     <!-- Movie Name input -->
                        <label for="edit_movie_name">Trailer URL</label>
                        <input type="text" class="form-control" id="edit_movie_name" name="trailer" value="<?php echo $row['trailer']; ?>" required>
                        
                        <!-- Display the old video from the database -->
                        <video width="320" height="240" controls oncontextmenu="return false;" controlsList="nodownload">
    <source src="../<?php echo $row['videopath']; ?>"  type="video/mp4">
    <!--<track kind="subtitles" src="subtitles.vtt" srclang="en" label="English">-->

    Your browser does not support the video tag.
                        </video>  <br><br>
<!--<video width="320" height="240" controls>
    <source src="<?php echo $row['videopath']; ?>" type="video/mp4">
    Your browser does not support the video tag.
</video><br><br>-->

<!-- Input for uploading a new video -->
<label for="videoToUpload">Upload New Video</label><br><br>
<input type="file" name="videoToUpload" id="videoToUpload" accept=".mp4, .avi, .mov"><br><br><br>
<!--                         <label for="edit_movie_name">Video URL</label>
                        <input type="text" class="form-control" id="edit_movie_name" name="movievideo" value="<?php echo $row['videopath']; ?>" required>-->
                      
                        <input type="hidden" name="movie_id" value="<?php echo $row['id']; ?>">

                    <!--<input type="hidden" name="movie_id" value="<?php echo $row['id']; ?>">-->
                    <button type="submit" name="btn_edit_movie" class="btn btn-primary">Update</button>
                </form>
                    </div>
<div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
                    
        </div>
    </div>
     
                    </div>

<?php endforeach; ?>

<div class="modal fade" id="editSuccessModal" tabindex="-1" role="dialog" aria-labelledby="editSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSuccessModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Movie has been successfully updated.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editErrorModal" tabindex="-1" role="dialog" aria-labelledby="editErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editErrorModalLabel">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                An error occurred while updating the movie. Please try again.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php foreach ($rows as $row) : ?>
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <h5 class="modal-title">Delete Slider</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this movie name <?php echo $row['name']; ?>? </p>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="slider_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="btn_delete_movie" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>



<div class="modal fade" id="deleteSuccessModal" tabindex="-1" role="dialog" aria-labelledby="deleteSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSuccessModalLabel">Movie Deleted Successfully</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                The selected movie has been successfully deleted.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteErrorModal" tabindex="-1" role="dialog" aria-labelledby="deleteErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteErrorModalLabel">Error Deleting Movie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                An error occurred while trying to delete the movie. Please try again later.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


</body>

    
<script>
// JavaScript to handle image preview for the file input
document.querySelectorAll('#fileToUpload').forEach(input => {
    input.addEventListener('change', function() {
        const imgPreview = this.parentNode.querySelector('img');
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            imgPreview.src = "../<?php echo $row['movie_banner']; ?>"; // Restore original image if no file selected
        }
    });
});
</script>
<script>
function previewImage(event) {
    var input = event.target;
    var imagePreview = document.getElementById("imagePreview");
    
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = "block"; // Show the preview image
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        imagePreview.style.display = "none"; // Hide the preview if no file selected
    }
}
</script>

<script>
    // JavaScript function to update video preview
    function updateVideoPreview(input) {
        var videoElement = input.parentNode.querySelector('video');
        var videoSource = videoElement.querySelector('source');
        var newVideoFile = input.files[0];

        // Check if a new video file is selected
        if (newVideoFile) {
            // Update video source with the selected file
            videoSource.src = URL.createObjectURL(newVideoFile);
            videoElement.load(); // Load and play the new video
        } else {
            // No new video file selected, keep the existing video
            var existingVideoPath = videoElement.getAttribute('data-existing-src');
            videoSource.src = existingVideoPath;
            videoElement.load(); // Load and play the existing video
        }
    }

    // Add event listeners to file inputs for updating the video preview
    var videoInputs = document.querySelectorAll('input[type="file"]');
    videoInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            updateVideoPreview(this);
        });
    });
</script>



    <?php
     include("admin_footer.php");

?>