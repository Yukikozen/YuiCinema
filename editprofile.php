<!DOCTYPE html>
<html>
    <head>
        <title>Edit Profile</title>
        <style>
            /* styles.css */
            .accordion
            {
                margin-left: -5rem;
                margin-right:auto;
            }
            .accordion-btn
            {
                background-color: #f1f1f1;
                color: #333;
                cursor: pointer;
                padding: 10px;
                width: 105rem;
                text-align: left;
                border: none;
                outline: none;
                transition: background-color 0.3s;

            }

            .accordion-panel
            {
                display: none;
                padding: 10px;
                border-top: 1px solid #ddd;
            }

            .accordion-panel.active
            {
                display: block;
            }


            .circular-profile-pic
            {
                width: 30rem; /* Set a fixed width for the circular border */
                height: 30rem; /* Set a fixed height for the circular border */
                border-radius: 50%;
                border: 3px solid #333;
                display: block;
                margin: 0 auto;
            }

            @media screen and (max-width: 768px)
            {

                .accordion
                {
                    margin-left:2rem;
                }

            }

            @media (max-width: 576px)
            {
                .accordion
                {
                    margin-left:0rem;
                }
            }


        </style>
    </head>
    <body>
        <?php
        session_start();
//include_once"header.php";
        include_once 'conn/config.php';
        ?>
        <?php
        if (empty($_SESSION["username"])) {
            header("Location:index.php");
        } else {
            include_once("header.php");
        }
        ?>
        <?php
// Check if the edit info form is submitted
        if (isset($_POST['btn_edit_info'])) {
            // Get the user's ID from the form
            $userID = $_POST['user_id'];

            // Get the updated user information from the form
            $newEmail = $_POST['edit_email'];
            $newPhone = $_POST['edit_phone'];
            $newGender = $_POST['edit_gender'];

            // Perform input validation here (e.g., check if email is valid, phone number format, etc.)
            // Update the user information in the database
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $updateQuery = "UPDATE customer SET email=?, hpnum=?, gender=? WHERE id=?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("sssi", $newEmail, $newPhone, $newGender, $userID);

            if ($stmt->execute()) {
                // Update successful
                echo '<script>
        $(document).ready(function() {
        $("#editInfoSuccessModal").modal("show");
        });
         </script>';
            } else {
                // Update failed
                echo '<script>
        $(document).ready(function() {
        $("#editInfoErrorModal").modal("show");
        });
        </script>';
            }

            $stmt->close();
            $conn->close();
        }
        ?>
        <?php
// Check if the edit profile picture form is submitted
        if (isset($_POST['btn_edit_picture'])) {
            // Get the user's ID from the form
            $userID = $_POST['user_id'];

            // Remove the old profile picture if it exists
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $query = "SELECT profilepic FROM customer WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $stmt->bind_result($oldProfilePic);
            $stmt->fetch();
            $stmt->close();

            // Define the target directory for uploading profile pictures
            $targetDirectory = "images/profile_pictures/";



// Perform file upload and validation for the new profile picture
            if (!empty($_FILES['fileToUpload']['name'])) {
                // Check if the uploaded file is an image
                $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
                if ($check !== false) {
                    // File is an image
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }

                // Check file size (adjust the limit as needed)
                if ($_FILES['fileToUpload']['size'] > 500000) {
                    echo '<script>
            $(document).ready(function() {
                $("#fileSizeErrorModal").modal("show");
            });
        </script>';
                    $uploadOk = 0;
                }

                // Allow only specific image file formats (e.g., jpg, jpeg, png)
                $imageFileType = strtolower(pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION));
                if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                    echo '<script>
            $(document).ready(function() {
                $("#fileExtErrorModal").modal("show");
            });
        </script>';
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    // Handle errors as needed
                } else {
                    // If everything is fine, move the uploaded file to the target directory
                    $newProfilePicPath = $targetDirectory . basename($_FILES['fileToUpload']['name']);
                    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $newProfilePicPath)) {
                        // Update the profile picture path in the database
                        $updatePictureQuery = "UPDATE customer SET profilepic=? WHERE id=?";
                        $stmt = $conn->prepare($updatePictureQuery);
                        $stmt->bind_param("si", $newProfilePicPath, $userID);

                        if ($stmt->execute()) {
                            // Update successful
                            // Check if there is an old profile picture to delete
                            if (!empty($oldProfilePic)) {
                                // Delete the old profile picture
                                if (file_exists($oldProfilePic)) {
                                    unlink($oldProfilePic);
                                    // Unset the old profilepic session variable to clear it
                                    unset($_SESSION["profilepic"]);
                                }
                            }
                            // Set the session profilepic variable to the new path
                            $_SESSION["profilepic"] = $newProfilePicPath;

                            echo '<script>
                    $(document).ready(function() {
                        $("#editPicSuccessModal").modal("show");
                    });
                </script>';
                        } else {
                            // Update failed
                            echo '<script>
                    $(document).ready(function() {
                        $("#editPicErrorModal").modal("show");
                    });
                </script>';
                        }

                        $stmt->close();
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            } else {
                // No new file uploaded, use the existing profile picture
                $newProfilePicPath = $oldProfilePic;
            }

            // Close the database connection
            $conn->close();
        }
        ?>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if the edit profile picture form is submitted
            if (isset($_POST['btn_edit_pass'])) {
                $user_id = $_POST['user_id'];
                $username = $_POST['username'];
                $old_password = $_POST['old_password'];
                $new_password = $_POST['new_password'];
                $newcfm_password = $_POST['newcfm_password'];

                // Check if the new password and confirm new password match
                if ($new_password !== $newcfm_password) {
                    echo '<script>
                    $(document).ready(function() {
                        $("#editPassCheckErrorModal").modal("show");
                    });
                </script>';
//            echo "New password and confirm new password do not match.";
                } else {
                    // Connect to the database
                    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Verify the old password
                    $query = "SELECT password FROM customer WHERE id = ? AND username = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("is", $user_id, $username);
                    $stmt->execute();
                    $stmt->bind_result($db_password);
                    $stmt->fetch();
                    $stmt->close();

                    if (password_verify($old_password, $db_password)) {
                        // Old password matches, so update the password
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $update_query = "UPDATE customer SET password = ? WHERE id = ?";
                        $update_stmt = $conn->prepare($update_query);
                        $update_stmt->bind_param("si", $hashed_password, $user_id);

                        if ($update_stmt->execute()) {
//                    echo "Password updated successfully!";
                            echo '<script>
                    $(document).ready(function() {
                        $("#editPassSuccessModal").modal("show");
                    });
                </script>';
                        } else {
                            echo '<script>
                    $(document).ready(function() {
                        $("#editPassErrorModal").modal("show");
                    });
                </script>';
                        }

                        $update_stmt->close();
                    } else {
//                echo "Old password is incorrect.";
                        echo '<script>
                    $(document).ready(function() {
                        $("#editPassErrorModal").modal("show");
                    });
                </script>';
                    }

                    // Close the database connection
                    $conn->close();
                }
            }
        }

// Remain on the same page if the form was not submitted
        ?>

        <section style="min-height: 450px;">
            <div class="container-fluid">
                <div class="row justify-content-center"> <!-- Center-align the content -->
                    <div class="col-md-10">
                        <h5 class="text-center mt-2" style="color:maroon;">Edit Profile</h5>
                        <?php
                        $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                            // Retrieve the user's ID and username from the session
                        }
                        $userID = $_SESSION['cust_id'];
                        $username = $_SESSION['username'];
                        $query = "SELECT id, username, email,hpnum,gender FROM customer where id=? AND username =?";
                        $stmt = $conn->prepare($query);

                        // Bind the session variables to the query parameters
                        $stmt->bind_param("is", $userID, $username);

                        // Execute the statement
                        $stmt->execute();

                        // Bind result variables
                        $stmt->bind_result($id, $name, $email, $hp, $gender);

                        $rows = [];
                        while ($stmt->fetch()) {
                            $rows[] = ["id" => $id, "username" => $name, "email" => $email, "hpnum" => $hp, "gender" => $gender];
                        }
//                                    // Close the statement
//                                    $stmt->close();
                        ?>
                        <?php
                        if (empty($rows)) {
                            echo 'No records found.';
                        } else {
                            foreach ($rows as $row) {
                                echo '<div class="accordion">';
                                echo '<button class="accordion-btn">Personal Information</button>';
                                echo '<div class="accordion-panel">';

                                echo "Username: {$row['username']}<br>";
                                echo "Email: {$row['email']}<br>";
                                echo "Phone Number: {$row['hpnum']}<br>";
                                echo "Gender: {$row['gender']}<br>";

                                // Add Bootstrap classes to align the button to the right
                                echo '<div class="text-right mb-3">';
                                echo '<button style="width: 15rem; height: 3rem;" class="btn btn-primary" data-toggle="modal" data-target="#editUserProfileModal_' . $row['id'] . '">Edit</button>';
                                echo '</div>';

                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                        <?php
                        $stmt->close();
                        $conn->close();
                        ?>
                        <br>
                    </div>
                </div>
            </div>
        </section>
        <!-- Add this code after the user profile section -->
        <!-- Edit User Profile Modal -->
        <div class="modal fade" id="editUserProfileModal_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editUserProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserProfileModalLabel">Edit User Profile</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Create a form for editing user profile details -->
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">

                            <!-- Add input fields for editing user profile attributes -->
                            <div class="form-group">
                                <label for="edit_username">Username</label>
                                <input type="text" class="form-control" id="edit_username" name="edit_username" value="<?php echo $row['username']; ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="edit_email" value="<?php echo $row['email']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="edit_phone">Phone Number</label>
                                <input type="tel" class="form-control" id="edit_phone" name="edit_phone" value="<?php echo $row['hpnum']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Gender</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_gender" id="edit_gender_male" value="Male" <?php echo (strtolower($row['gender']) === 'male') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="edit_gender_male">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_gender" id="edit_gender_female" value="Female" <?php echo (strtolower($row['gender']) === 'female') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="edit_gender_female">Female</label>
                                </div>
                            </div>

                            <!-- Add more input fields for other user profile attributes as needed -->

                            <!-- Save changes button -->
                            <button type="submit"  name="btn_edit_info" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <section style="min-height: 450px;">
            <div class="container-fluid">
                <div class="row justify-content-center"> <!-- Center-align the content -->

                    <div class="col-md-10">
                        <!--                            <h5 class="text-center mt-2" style="color:maroon;">Edit Profile</h5>-->

                        <?php
                        $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                            // Retrieve the user's ID and username from the session
                        }
                        $userID = $_SESSION['cust_id'];
                        $username = $_SESSION['username'];

                        $query = "SELECT id, username,  profilepic FROM customer where id=? AND username =?";
                        $stmt = $conn->prepare($query);

// Bind the session variables to the query parameters
                        $stmt->bind_param("is", $userID, $username);


// Execute the statement
                        $stmt->execute();

// Bind result variables
                        $stmt->bind_result($id, $name, $img);

                        $rows = [];
                        while ($stmt->fetch()) {
                            $rows[] = ["id" => $id, "username" => $name, "profilepic" => $img];
                        }
//                                    // Close the statement
//                                    $stmt->close();
                        ?>



                        <?php
                        if (empty($rows)) {
                            echo 'No records found.';
                        }
//                               <?php
                        else {
                            foreach ($rows as $row) {
                                echo '<div class="accordion">';
                                echo ' <button class="accordion-btn">Profile Picture</button>';
                                echo '<div class="accordion-panel">';
                                echo '<div id="profilePictureContainer">';

                                // Check if the profile picture is empty
                                if (!empty($row['profilepic'])) {
                                    // Display the user's profile picture
                                    echo '<img src="' . $row['profilepic'] . '" alt="Profile Picture" class="img-fluid circular-profile-pic">';
                                } else {
                                    // Display a default profile picture
                                    echo '<img src="images/Default_pfp.png" alt="Default Profile Picture" class="img-fluid circular-profile-pic">';
                                }
                                echo '</div>';



                                // Add Bootstrap classes to align the button to the right
                                echo '<div class="text-right mb-3">';

                                echo '<button class="btn btn-primary" data-toggle="modal" data-target="#editUserProfileModalPic_' . $row['id'] . '" style="margin-top:2rem; width: 15rem; height: 3rem;">Change Profile Picture</button>';
                                echo '</div>';

                                echo '</div>';
//    echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                        <?php
                        $stmt->close();
                        $conn->close();
                        ?>

                        <br>

                    </div>
                </div>
            </div>



        </section>
        <!-- Add this code after the user profile section -->
        <!-- Edit User Profile Modal -->
        <div class="modal fade" id="editUserProfileModalPic_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editUserProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserProfileModalLabel">Edit User Profile Picture</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Display the current user profile picture or the default picture -->

                        <?php
                        if (!empty($row['profilepic'])) {
                            // Display the user's profile picture
                            echo '<div class="circular-profile-pic-container">
        <img id="profilePicPreview" src="' . (!empty($row['profilepic']) ? $row['profilepic'] : 'images/Default_pfp.png') . '" alt="Profile Picture" class="img-fluid circular-profile-pic">
    </div>';
                        } else {
                            // Display a default profile picture
                            echo '<img src="images/Default_pfp.png" alt="Default Profile Picture" class="img-fluid circular-profile-pic">';
                        }
                        ?>

                        <!-- Create a form for editing user profile details -->
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="fileToUpload">Upload New Profile Picture</label>
                                <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload">
                            </div>
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">

                            <!-- Save changes button -->
                            <button type="submit" name="btn_edit_picture" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Success Modal for Adding Favorite -->
        <div class="modal fade" id="editInfoSuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Success</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Profile edited successfully!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Error Modal for Adding Favorite -->
        <div class="modal fade" id="editInfoErrorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "Error updating profile information: " . $stmt->error;
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal for Adding Favorite -->
        <div class="modal fade" id="editPicSuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Success</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Profile edited successfully!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Error Modal for Adding Favorite -->
        <div class="modal fade" id="editPicErrorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "Error updating profile picture: " . $stmt->error;
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Size Too Large Modal -->
        <div class="modal fade" id="fileSizeErrorModal" tabindex="-1" role="dialog" aria-labelledby="fileSizeErrorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileSizeErrorModalLabel">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        The selected file size is too large. Please choose a smaller file.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="fileExtErrorModal" tabindex="-1" role="dialog" aria-labelledby="fileSizeErrorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileSizeErrorModalLabel">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Sorry, only JPG, JPEG, and PNG files are allowed.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <section style="min-height: 450px;">
            <div class="container-fluid">
                <div class="row justify-content-center"> <!-- Center-align the content -->
                    <div class="col-md-10">
                        <?php
                        // Your database connection code here
                        $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        $userID = $_SESSION['cust_id'];
                        $username = $_SESSION['username'];

                        $query = "SELECT id, username, password FROM customer WHERE id = ? AND username = ?";
                        $stmt = $conn->prepare($query);

                        // Bind the session variables to the query parameters
                        $stmt->bind_param("is", $userID, $username);

                        // Execute the statement
                        $stmt->execute();

                        // Bind result variables
                        $stmt->bind_result($id, $name, $password);

                        $rows = [];
                        while ($stmt->fetch()) {
                            $rows[] = ["id" => $id, "username" => $name, "password" => $password];
                        }
                        ?>

                        <?php
                        if (empty($rows)) {
                            echo 'No records found.';
                        } else {
                            foreach ($rows as $row) {
                                echo '<div class="accordion">';
                                echo '<button class="accordion-btn">Change Password</button>';
                                echo '<div class="accordion-panel">';
                                echo '<form method="POST">';
                                echo '<label for="oldpw"><b> Current Password</b></label>';
                                echo '<div class="input-group">';
                                echo '<input type="password" style="border-radius: 30px;" placeholder="Current Password" name="old_password" id="oldpw" required>';
                                echo '<i class="far fa-eye reg__eye toggle-password" id="toggleOldPassword" data-target="old_password"></i>';
                                echo '</div>';

                                echo '<label for="newpw"><b> New Password</b></label>';
                                echo '<div class="input-group">';
                                echo '<input type="password" style="border-radius: 30px;" name="new_password" placeholder="New Password" id="newpw" required>';
                                echo '<i class="far fa-eye reg__eye toggle-password" id="toggleNewPassword" data-target="new_password"></i>';
                                echo '</div>';

//                                             Password Strength Indicator 
                                echo '<div class="password-strength-container">';
                                echo '<div class="password-strength-bar" id="password-strength-bar1"></div>';
                                echo '<div class="password-strength-text" id="password-strength-text1">Password strength: Weak</div>';
                                echo '</div><br>';

                                echo '<label for="newcfm"><b>Confirm Password</b></label>';
                                echo '<div class="input-group">';
                                echo '<input type="password" style="border-radius: 30px;" name="newcfm_password" placeholder="Confirm New Password" id="newcfm" required>';
                                echo '<i class="far fa-eye reg__eye toggle-password" id="toggleConfirmNewPassword" data-target="newcfm_password"></i>';
                                echo '</div>';

//                                             Confirm Password Strength Indicator 
                                echo '<div class="password-strength-container">';
                                echo '<div class="password-strength-bar" id="confirm-password-strength-bar1"></div>';
                                echo '<div class="password-strength-text" id="confirm-password-strength-text1">Password strength: Weak</div>';
                                echo'</div>';
                                echo '<div id="password-match-message1"></div><br>';


                                echo '<input type="hidden" name="user_id" value="' . $row['id'] . '">';
                                echo '<input type="hidden" name="username" value="' . $row['username'] . '">';
                                echo '<div class="text-right mb-3">';
                                echo '<button type="submit" name="btn_edit_pass" class="btn btn-primary btn-sm" style="width: 15rem; height: 3rem;">Submit</button>';
                                echo '</div>';
                                echo '</form>';


                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                        <?php
                        // Close the statement and database connection
                        $stmt->close();
                        $conn->close();
                        ?>
                        <br>
                    </div>
                </div>
            </div>
        </section>

        <!-- Success Modal for Adding Favorite -->
        <div class="modal fade" id="editPassSuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Success</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Password Updated Successfully!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editPassErrorModal" tabindex="-1" role="dialog" aria-labelledby="fileSizeErrorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileSizeErrorModalLabel">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Old Password is incorrect!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editPassCheckErrorModal" tabindex="-1" role="dialog" aria-labelledby="fileSizeErrorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileSizeErrorModalLabel">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        New password and Confirm new password do not match.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const accordionButtons = document.querySelectorAll(".accordion-btn");

                accordionButtons.forEach(button => {
                    button.addEventListener("click", function () {
                        const panel = this.nextElementSibling;
                        if (panel.style.display === "block") {
                            panel.style.display = "none";
                        } else {
                            panel.style.display = "block";
                        }
                    });
                });
            });

        </script>

        <script>
            // JavaScript to handle image preview for the file input
            document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll('#fileToUpload').forEach(input => {
                    input.addEventListener('change', function () {
                        const imgPreview = document.getElementById("profilePicPreview");
                        const file = this.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                imgPreview.src = e.target.result;

                                // Update the profile picture immediately in the profilePictureContainer
                                const profilePictureContainer = document.getElementById("profilePictureContainer");
                                profilePictureContainer.innerHTML = ''; // Clear the container
                                const newProfilePic = document.createElement('img');
                                newProfilePic.src = e.target.result;
                                newProfilePic.alt = "Profile Picture";
                                newProfilePic.className = "img-fluid circular-profile-pic";
                                profilePictureContainer.appendChild(newProfilePic);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // Restore the original image if no file selected
                            imgPreview.src = "images/Default_pfp.png";
                        }
                    });
                });
            });
        </script>



        <script>
            function previewImage(event) {
                var input = event.target;
                var imagePreview = document.getElementById("imagePreview");

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = "block"; // Show the preview image
                        //            imagePreview.style.maxWidth = "25rem"; // Set max-width for the preview image
                        //            imagePreview.style.maxHeight = "25rem"; // Set max-height for the preview image
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    imagePreview.style.display = "none"; // Hide the preview if no file selected
                }
            }
        </script>
        <script>
            // Function to calculate and update password strength
            function calculatePasswordStrength1(password1, progressBarId, strengthTextId) {
                // Define a regular expression pattern for a strong password
                const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/;

                // Check if the password matches the pattern
                const isStrongPassword = passwordPattern.test(password1);

                const progressBar = document.getElementById(progressBarId);
                const strengthText = document.getElementById(strengthTextId);

                // Update the password strength bar and text based on the pattern match
                progressBar.style.width = isStrongPassword ? '100%' : '50%';

                // Change the text color to green when the password is strong
                if (isStrongPassword) {
                    strengthText.style.color = 'green';
                } else {
                    strengthText.style.color = ''; // Reset the color when it's not strong
                }

                strengthText.textContent = isStrongPassword ? 'Password strength: Strong' : 'Password strength: Weak';
            }

            // Function to check if password and confirm password match
            function checkPasswordMatch1() {
                const password1 = document.getElementById('newpw').value;
                const confirmPassword1 = document.getElementById('newcfm').value;
                const passwordMatchMessage1 = document.getElementById('password-match-message1');

                if (password1 === confirmPassword1) {
                    passwordMatchMessage1.textContent = 'Passwords match!';
                    passwordMatchMessage1.style.color = 'green';
                } else {
                    passwordMatchMessage1.textContent = 'Passwords do not match.';
                    passwordMatchMessage1.style.color = 'red';
                }

            }

            // Listen for input changes in the password field
            const passwordInput1 = document.getElementById('newpw');
            passwordInput1.addEventListener('input', function () {
                const password1 = this.value;
                calculatePasswordStrength1(password1, 'password-strength-bar1', 'password-strength-text1');
            });

            // Listen for input changes in the confirm password field
            const confirmPasswordInput1 = document.getElementById('newcfm');
            confirmPasswordInput1.addEventListener('input', function () {
                const confirmPassword1 = this.value;
                calculatePasswordStrength1(confirmPassword1, 'confirm-password-strength-bar1', 'confirm-password-strength-text1');
                checkPasswordMatch1(); // Check password match on input change
            });
        </script>

        <script>
            $(document).ready(function () {
                // Toggle password visibility for old password field
                $("#toggleOldPassword").click(function () {
                    const oldPasswordField = $("#oldpw");
                    const type = oldPasswordField.attr("type");
                    if (type === "password") {
                        oldPasswordField.attr("type", "text");
                        $(this).removeClass("far fa-eye").addClass("far fa-eye-slash");
                    } else {
                        oldPasswordField.attr("type", "password");
                        $(this).removeClass("far fa-eye-slash").addClass("far fa-eye");
                    }
                });

                // Toggle password visibility for new password field
                $("#toggleNewPassword").click(function () {
                    const newPasswordField = $("#newpw");
                    const type = newPasswordField.attr("type");
                    if (type === "password") {
                        newPasswordField.attr("type", "text");
                        $(this).removeClass("far fa-eye").addClass("far fa-eye-slash");
                    } else {
                        newPasswordField.attr("type", "password");
                        $(this).removeClass("far fa-eye-slash").addClass("far fa-eye");
                    }
                });

                // Toggle password visibility for confirm new password field
                $("#toggleConfirmNewPassword").click(function () {
                    const confirmNewPasswordField = $("#newcfm");
                    const type = confirmNewPasswordField.attr("type");
                    if (type === "password") {
                        confirmNewPasswordField.attr("type", "text");
                        $(this).removeClass("far fa-eye").addClass("far fa-eye-slash");
                    } else {
                        confirmNewPasswordField.attr("type", "password");
                        $(this).removeClass("far fa-eye-slash").addClass("far fa-eye");
                    }
                });
            });
        </script>



        <?php
        include_once("footer.php");
        ?>

    </body>
</html>