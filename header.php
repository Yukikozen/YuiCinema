<?php
//include_once("conn/conn.php");
include_once("conn/config.php");

if (!isset($_SESSION)) {
    session_start();
}



$showModal = false;
// Set the session timeout duration (in seconds)
$_SESSION['timeout'] = 300; // 5 minutes (300 seconds)

if (isset($_GET["action"]) && $_GET["action"] == "logout" && !empty($_SESSION["username"])) {
//    if ($_GET["action"] == "logout") {
        // Clear the session variables
        $_SESSION["username"] = null;
        $_SESSION["cust_id"] = null;
        $_SESSION["profilepic"] = null;

        // Set a flag to indicate that the user has logged out
        $_SESSION["logged_out"] = true;

        // Destroy the session
        session_destroy();
        
        // Clear the countdown timer and hide the modal
//        echo '<script>';
//        echo 'clearTimeout(countdownTimer);';
//        echo '$("#sessionTimeoutModal").modal("hide");';
//        echo '</script>';
    }
//}



if (empty($_SESSION["username"]) || isset($_SESSION["logged_out"])) {
    // Check if the user has logged out; if so, prevent the modal from appearing
    unset($_SESSION["logged_out"]);
    $_SESSION["ul"] = '<li class="nav-item"> <a class="nav-link"  data-toggle="modal" data-target="#modelId">Register</a></li><li class="nav-item"><a class="nav-link" data-toggle="modal" data-target="#modelId1">Login</a></li>';
}
//if (empty($_SESSION["username"])) {
//    $_SESSION["ul"] = '<li class="nav-item"> <a class="nav-link"  data-toggle="modal" data-target="#modelId">Register</a></li><li class="nav-item"><a class="nav-link" data-toggle="modal" data-target="#modelId1">Login</a></li>';
//} 
else {
    $_SESSION["ul"] = '
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="profile-pic" id="profile-pic-container">
                        <div class="profile-pic-circle">
                            <img src="' . $_SESSION["profilepic"] . '" alt="Profile Picture">
                        </div>
                    </div>
                    <div class="username">' . $_SESSION["username"] . '</div>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownId">
                    <a class="dropdown-item" href="editprofile.php">Profile</a>
                    <a class="dropdown-item" href="favourites.php">Favorites</a>
                </div>
            </li>
        </ul>

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php?action=logout">
                    <i class="fa fa-sign-out fa-lg ml-4 mt-3 mb-2"></i>
                    <div class="d-flex align-items-center">
                        <div class="logout ml-1 mt-2"> Logout</div>
                    </div>
                </a>
            </li>
        </ul>';
    // Set the flag to show the modal for logged-in users
    $showModal = true;
}
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Online Movie</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <style>

            .profile-pic-circle {
                width: 50px;  /*Adjust the width and height as needed */
                height: 50px;
                border-radius: 50%;  /*Creates a circular border */
                overflow: hidden; /* Ensures the image stays within the circle */
            }

            .profile-pic-circle img {
                width: 100%;
                height: 100%;
                object-fit: cover;  /*Ensures the image covers the entire circle */
            }

            .username {
                font-weight: bold;
                margin-top: 0.05rem; /* Adjust the margin as needed to align with the logout link */
            }
            .logout {
                font-weight: bold;
                /* Remove the margin-top property from here */
            }
            .fa fa-sign-out{
                margin-top:5rem;
                wdith:15rem;
                height:15rem;

            }

            .view-all-link {
                text-align: right; /* Align the "View All Movies" link to the right */
                margin-top: -20px;
                margin-right: 20px; /* Increase the right margin to push it further right */
            }

            .all-movies-container {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                flex-wrap: wrap;
                margin-left: -10px; /* Add negative margin to shift the content to the left */
            }

            /*    .jumbotron {
                    flex-basis: 48%;
                    margin-bottom: 0;
                    margin-left: 10px;
                    padding-right: 20px;  Add some padding to the right to balance the layout 
                }*/
            /* Default styles for carousel images */
            .carousel-inner img {
                width: 100%; /* Set your desired width */
                height: auto; /* Allow the image to scale naturally */
            }



            .movie-banner {
                width: 10rem; /* Set a fixed width for movie banners */
                height: 15rem; /* Allow the height to adjust proportionally */
                margin-bottom: 1rem;
                /*margin-right: 10rem;  Add right margin to create spacing between movie banners */
            }


            .movie-list {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                /*margin: 0 -10px;*/


            }
            /* Movie card styles */
            .movie-card {
                flex: 0 0 calc(15% - 20px);
                text-align: center;
                border: 1px solid #ccc;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                background-color: #fff;
                width: 20rem; /* Set a fixed width for the movie card */
                height: 35rem; /* Set a fixed height for the movie card */
                /*margin: 2rem 7rem;*/  
                margin-top:5rem;
                /*margin-right:5rem;*/

                margin-left: 9rem;  /*Add margin to create spacing between movie cards */

            }
            .movie-card:hover {
                transform: scale(1.05);
                cursor: pointer;
            }


            .movie-image {
                width: 100%;
                height: 75%; /* Set the image height to a percentage of the card's height */
                object-fit: cover; /* Maintain aspect ratio and cover the entire container */
            }

            .movie-title {
                height: 25%; /* Set the height for the movie title area */
                display: flex;
                align-items: center; /* Center the movie title vertically */

            }


            .col1 {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                margin-bottom: 20px;
                margin-left: 60px; /* Adjusted left margin */
                margin-right: 30px; /* Adjusted right margin */
                width: 250px; /* Fixed width for consistent size */
            }

            .col1 img {
                width: 100%;
                height: 350px; /* Fixed height for consistent size */
                object-fit: cover; /* Maintain aspect ratio while filling the container */
                margin-top: 20px;
                margin-bottom: 10px;
            }
        

            .btn {
                display: block;
                width: 100%;
                padding: 10px;
                margin-top: 10px;
            }

            .movie-title1{
                font-size:1rem;
                text-align: center;
            }

            .reg__eye{
                font-size:1.25rem;
                position: absolute;
                right:2rem;
                top:1.5rem;
                bottom:2rem;
                cursor: pointer;

            }
            .login__eye{
                font-size:1.25rem;
                position: absolute;
                right:2rem;
                top:1.5rem;
                bottom:2rem;
                cursor: pointer;
            }
            @media screen and (max-width: 768px) {
                /* Apply styles for screens with a maximum width of 768px */
                .row {
                    justify-content: center; /* Center-align columns within the row */
                }
                .col1 {
                    margin-left: 10px; /* Adjust the margin for spacing */
                    margin-right: 10px; /* Adjust the margin for spacing */
                }
                .movie-list {
                    justify-content: center;
                    margin: 0;
                }
                .movie-card {
                    flex: 0 0 calc(50% - 20px); /* Adjust the width to have two cards in a row */
                    min-width: calc(50% - 20px); /* Set a minimum width for movie cards in smaller screens */
                    margin: 10px; /* Add margin to separate cards */
                    text-align: center; /* Center-align the content */
                    width:15rem;
                    height: 30rem; /* Set a fixed height for the movie card */

                    margin-left: auto;
                    margin-right: auto;
                    /*                height:23rem;*/
                }

                /* Styles for smaller screens */
                .carousel-inner {
                    max-width: 40rem;
                    max-height: 50rem;
                    margin: 0 auto;
                }

                .carousel-inner img {
                    width: 30%;
                    height: 40%;
                    object-fit: cover;
                }


                /* Apply styles for screens with a maximum width of 768px */
                .row {
                    justify-content: center; /* Center-align columns within the row */
                }

                .col1 {
                    margin-left: 10px; /* Adjust the margin for spacing */
                    margin-right: 10px; /* Adjust the margin for spacing */
                }

                .col1 img {
                    max-height:300px; /* Decreased max-height for smaller screens */
                }

                h3 {
                    font-size: 12px; /* Further decreased font size for smaller screens */
                }


            }

            @media (max-width: 576px) {
                .carousel-inner {
                    max-width: 70rem; /* Set your desired max-width */
                    max-height: 40rem; /* Set your desired max-height */
                    margin: 0 auto; /* Center-align the container */
                }



                /* Adjust the height and margins for other elements if needed */
            }


        </style>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

        <!--<link rel="icon" href="images/logo.png">-->
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <!-- Font Awesome Icons -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
        <!-- font-awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
        

        <link rel="stylesheet" href="css/register.css">

    </head>


    <body>

        <nav class="navbar navbar-expand-md navbar-dark" style="background-color:maroon">
            <a class="navbar-brand" href="index.php"><img src="images/png-transparent-movies-logo-the-film-television-logo.png" style="width: 60px;"/></a>
            <button id="navToggleButton" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavId">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Movie</a>
                        <div class="dropdown-menu" aria-labelledby="dropdownId">
                            <a class="dropdown-item" href="comingsoon.php">Coming Soon</a>
                            <a class="dropdown-item" href="nowshowing.php">Now Showing</a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>

                <ul  class="navbar-nav ">
                    <?php echo $_SESSION["ul"]; ?>

                </ul>


            </div>
        </nav>
    <?php
    if ($showModal) {
      echo '
        <!-- Modal for session timeout warning -->
        <div class="modal fade" id="sessionTimeoutModal" tabindex="-1" role="dialog" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sessionTimeoutModalLabel">Session Timeout Warning</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Your session is about to expire. Would you like to continue your session?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="continueSessionBtn">Continue</button>
                        <button type="button" class="btn btn-secondary" id="logoutBtn" data-dismiss="modal">Logout</button>
                    </div>
                </div>
            </div>
        </div>';
    }
    ?>
        
        <!-- Register Modal -->
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: maroon; color: white;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="process_register.php" id="registrationForm">
                            <div class="container" style="color: maroon;">
                                <center>
                                    <h1>Customer Register</h1>
                                    <p>Please fill in this form to create an account.</p>
                                </center>
                                <hr>
                                <label for="regUsername"><b>Username</b></label>
                                <input type="text" style="border-radius: 30px;" placeholder="Enter Your Username" name="reg_full_name" id="regUsername" required>

                                <label for="email"><b>Email</b></label>
                                <input type="text" style="border-radius: 30px;" placeholder="Enter Email" name="reg_email" id="email" required>

                                <label for="number"><b>Phone Number</b></label>
                                <input type="tel" style="border-radius: 30px;" placeholder="Enter number" name="reg_number_txt" id="number" required>

                                <label><b>Select Gender</b></label>
                                <br>
                                <input type="radio" style="border-radius: 30px; margin-right: 2%;" id="maleGender" value="Male" name="reg_gender_txt" required>Male
                                <input type="radio" style="border-radius: 30px; margin-left: 5%; margin-right: 2%;" id="femaleGender" value="Female" name="reg_gender_txt" required> Female

                                <br><br>

                                <label for="regPassword"><b>Password</b></label>
                                <div class="input-group">
                                    <input type="password" style="border-radius: 30px;" placeholder="Enter Password" name="reg_psw" id="regPassword" required>
                                    <i class="far fa-eye reg__eye toggle-password" id="togglePassword" data-target="regPassword"></i>
                                </div>

                                <!-- Password Strength Indicator -->
                                <div class="password-strength-container">
                                    <div class="password-strength-bar" id="password-strength-bar"></div>
                                    <div class="password-strength-text" id="password-strength-text">Password strength: Weak</div>
                                </div>

                                <label for="psw_repeat"><b>Repeat Password</b></label>
                                <div class="input-group">
                                    <input type="password" style="border-radius: 30px;" placeholder="Repeat Password" name="psw_repeat" id="psw_repeat" required>
                                    <i class="far fa-eye reg__eye toggle-password" id="toggleConfirmPassword" data-target="psw_repeat"></i>
                                </div>

                                <!-- Confirm Password Strength Indicator -->
                                <div class="password-strength-container">
                                    <div class="password-strength-bar" id="confirm-password-strength-bar"></div>
                                    <div class="password-strength-text" id="confirm-password-strength-text">Password strength: Weak</div>
                                </div>
                                <div id="password-match-message"></div>
                                <button type="submit" class="btn" name="btn_reg" id="registerButton" style="background-color: maroon; color: white;">Register</button>
                                <hr>
                            </div>
                            <div class="container">
                                <p>Already have an account? <a style="color: gray" data-toggle="modal" data-target="#modelId1" data-dismiss="modal">Log in</a>.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<!--     Login Modal 
<div class="modal fade" id="modelId1" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="process_login.php">
                    <div class="container" style="color: maroon;">
                        <center>
                            <h1>Login</h1>
                        </center>
                        <hr>
                        <label for="logUsername"><b>Username</b></label>
                        <input type="text" style="border-radius: 30px;" placeholder="Enter Username" name="log_user" id="logUsername" required>

                        <label for="logPassword"><b>Password</b></label>
                        <div class="input-group">
                            <input type="password" style="border-radius: 30px;" placeholder="Enter Password" name="log_psw" id="logPassword" required>
                            <i class="far fa-eye login__eye toggle-password" id="toggleLoginPassword" data-target="logPassword"></i>
                        </div>
                        <button type="submit" name="btn_login" class="btn" style="background-color: maroon; color: white;">Login</button>
                    </div>
                </form>
                <hr>
                <div class="text-center">  Add this div for text alignment 
                    <a href="forget_pw.php">Forgot Password</a>
                </div>
            </div>
            
            
        </div>
    </div>
</div>
    
    
     OTP Modal 
<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <h5 class="modal-title" id="otpModalLabel">Enter OTP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="verify.php">
                    <div class="container" style="color: maroon;">
                        <img src="data:image/png;base64, <?=$encoded_qr_data;?>" alt="QR Code">
                        <p>One-time password at time of generation; <?=$current_otp;?></p>
                        <h2>Verify Code</h2>
                        <label for="otpCode"> One-time password: </label>
                        <input type="number" style="border-radius: 30px;" name="otpCode" id="otpCode" required />
                        
                        <label for="otpCode"><b>Enter OTP</b></label>
                        <input type="text" style="border-radius: 30px;" placeholder="Enter OTP" name="otp_code" id="otpCode" required>
                        <button type="submit" name="btn_verify_otp" class="btn" style="background-color: maroon; color: white;" onclick="verify_otp();">Verify OTP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
     

<!--     Login Modal 
<div class="modal fade" id="modelId1" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="process_login.php">
                    <div class="container" style="color: maroon;">
                        <center>
                            <h1>Login</h1>
                        </center>
                        <hr>
                        <label for="logUsername"><b>Username</b></label>
                        <input type="text" style="border-radius: 30px;" placeholder="Enter Username" name="log_user" id="logUsername" required>

                        <label for="logPassword"><b>Password</b></label>
                        <div class="input-group">
                            <input type="password" style="border-radius: 30px;" placeholder="Enter Password" name="log_psw" id="logPassword" required>
                            <i class="far fa-eye login__eye toggle-password" id="toggleLoginPassword" data-target="logPassword"></i>
                        </div>
                        <button type="submit" name="btn_login" class="btn" style="background-color: maroon; color: white;">Login</button>
                    </div>
                </form>
                <hr>
                <div class="text-center">  Add this div for text alignment 
                    <a href="forget_pw.php">Forgot Password</a>
                </div>
            </div>
        </div>
    </div>
</div>

 OTP Modal 
<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <h5 class="modal-title" id="otpModalLabel">Enter OTP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="process_login.php">
                    <div class="container" style="color: maroon;">
                        <img src="data:image/png;base64,<?php echo $_SESSION['encoded_qr_data']; ?>" alt="QR Code"> <br>
One-time password at the time of generation: <?php echo $_SESSION['otp']; ?>

                        <h2>Verify Code</h2>
                        <label for="otpCode"> One-time password:</label>
                        <input type="text" style="border-radius: 30px;" name="otpCode" id="otpCode" required />

                        <button type="submit" name="btn_verify_otp" class="btn" style="background-color: maroon; color: white;">Verify OTP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>-->

<script>
//$(document).ready(function() {
//    // Check if the session variable 'show_otp_modal' is set to true
//    <?php // if (isset($_SESSION['show_otp_modal']) && $_SESSION['show_otp_modal']) { ?>
//        console.log("Triggering OTP modal");
//        // Trigger the OTP modal
//        $('#otpModal').modal('show');
//        <?php
        // Reset the session variable after showing the modal
//        $_SESSION['show_otp_modal'] = false;
//    } ?>//
//});
//</script>


<?php
//if (isset($_SESSION['show_otp_modal']) && $_SESSION['show_otp_modal']) {
//    echo '<script>$(document).ready(function() { $("#otpModal").modal("show"); });</script>';
//    // Reset the flag to avoid showing the modal again on page reload
//    $_SESSION['show_otp_modal'] = false;
//}
?>
<!-- Add this code to your HTML -->
<!--<script>-->
<!--$(document).ready(function() {-->
    <!--// Check if the session variable 'show_otp_modal' is set to true and the URL parameter 'login' is 'verifying'-->
    <?php // if (isset($_SESSION['show_otp_modal']) && $_SESSION['show_otp_modal'] && $_GET['login'] === 'verifying') { ?>
<!--//        console.log("Triggering OTP modal");-->
<!--//        // Trigger the OTP modal-->
<!--//        $('#otpModal').modal('show');-->
        <?php
//        // Reset the session variable after showing the modal
//        $_SESSION['show_otp_modal'] = false;
//    } ?>
<!--//});-->
<!--</script>-->

<?php
//if (isset($_SESSION['show_otp_modal']) && $_SESSION['show_otp_modal'] && $_GET['login'] === 'verifying') {
//    echo '<script>$(document).ready(function() { $("#otpModal").modal("show"); });</script>';
//    // Reset the flag to avoid showing the modal again on page reload
//    $_SESSION['show_otp_modal'] = false;
//}
?>

   <!-- Login Modal -->
<div class="modal fade" id="modelId1" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="process_login.php">
                    <div class="container" style="color: maroon;">
                        <center>
                            <h1>Login</h1>
                        </center>
                        <hr>
                        <label for="logUsername"><b>Username</b></label>
                        <input type="text" style="border-radius: 30px;" placeholder="Enter Username" name="log_user" id="logUsername" required>

                        <label for="logPassword"><b>Password</b></label>
                        <div class="input-group">
                            <input type="password" style="border-radius: 30px;" placeholder="Enter Password" name="log_psw" id="logPassword" required>
                            <i class="far fa-eye login__eye toggle-password" id="toggleLoginPassword" data-target="logPassword"></i>
                        </div>
                        <button type="submit" name="btn_login" class="btn" style="background-color: maroon; color: white;">Login</button>
                    </div>
                </form>
                <hr>
                <div class="text-center"> <!-- Add this div for text alignment -->
                    <a href="forget_pw.php">Forgot Password</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <h5 class="modal-title" id="otpModalLabel">Enter OTP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="process_login.php">
                    <div class="container" style="color: maroon;">
                        <img src="data:image/png;base64,<?php echo $_SESSION['encoded_qr_data']; ?>" alt="QR Code"> <br>
One-time password at the time of generation: <?php echo $_SESSION['otp']; ?>

                        <h2>Verify Code</h2>
                        <label for="otpCode"> One-time password:</label>
                        <input type="text" style="border-radius: 30px;" name="otpCode" id="otpCode" required />

                        <button type="submit" name="btn_verify_otp" class="btn" style="background-color: maroon; color: white;">Verify OTP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



        <script>
    // JavaScript to dynamically update the profile picture in the navigation bar

    document.addEventListener("DOMContentLoaded", function () {
        // Get a reference to the profile picture container
        const profilePicContainer = document.getElementById("profile-pic-container");

        // Function to update the profile picture
        function updateProfilePic() {
            // Check if the user is logged in and the session profilepic value has changed
            if (profilePicContainer && <?php echo isset($_SESSION["profilepic"]) ? json_encode($_SESSION["profilepic"]) : "null"; ?> !== profilePicContainer.querySelector("img").src) {
                // Update the profile picture source
                profilePicContainer.querySelector("img").src = <?php echo isset($_SESSION["profilepic"]) ? json_encode($_SESSION["profilepic"]) : "null"; ?>;
            }
        }

        // Call the updateProfilePic function initially
        updateProfilePic();

        // You can also set up a timer to periodically check for updates
        // setInterval(updateProfilePic, 1000); // Check every 5 seconds, adjust as needed
    });
</script>


        <script>
            // Function to calculate and update password strength
            function calculatePasswordStrength(password, progressBarId, strengthTextId) {
                // Define a regular expression pattern for a strong password
                const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/;

                // Check if the password matches the pattern
                const isStrongPassword = passwordPattern.test(password);

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
            function checkPasswordMatch() {
                const password = document.getElementById('regPassword').value;
                const confirmPassword = document.getElementById('psw_repeat').value;
                const passwordMatchMessage = document.getElementById('password-match-message');

                if (password === confirmPassword) {
                    passwordMatchMessage.textContent = 'Passwords match!';
                    passwordMatchMessage.style.color = 'green';
                } else {
                    passwordMatchMessage.textContent = 'Passwords do not match.';
                    passwordMatchMessage.style.color = 'red';
                }
                
            }

            // Listen for input changes in the password field
            const passwordInput = document.getElementById('regPassword');
            passwordInput.addEventListener('input', function () {
                const password = this.value;
                calculatePasswordStrength(password, 'password-strength-bar', 'password-strength-text');
            });

            // Listen for input changes in the confirm password field
            const confirmPasswordInput = document.getElementById('psw_repeat');
            confirmPasswordInput.addEventListener('input', function () {
                const confirmPassword = this.value;
                calculatePasswordStrength(confirmPassword, 'confirm-password-strength-bar', 'confirm-password-strength-text');
                checkPasswordMatch(); // Check password match on input change
            });
        </script>

        <!-- JavaScript to toggle password visibility -->
        <script>
            $(document).ready(function () {
                // Toggle password visibility for password field
                $("#togglePassword").click(function () {
                    const passwordField = $("#regPassword");
                    const type = passwordField.attr("type");
                    if (type === "password") {
                        passwordField.attr("type", "text");
                        $(this).removeClass("far fa-eye").addClass("far fa-eye-slash");
                    } else {
                        passwordField.attr("type", "password");
                        $(this).removeClass("far fa-eye-slash").addClass("far fa-eye");
                    }
                });

                // Toggle password visibility for confirm password field
                $("#toggleConfirmPassword").click(function () {
                    const confirmPasswordField = $("#psw_repeat");
                    const type = confirmPasswordField.attr("type");
                    if (type === "password") {
                        confirmPasswordField.attr("type", "text");
                        $(this).removeClass("far fa-eye").addClass("far fa-eye-slash");
                    } else {
                        confirmPasswordField.attr("type", "password");
                        $(this).removeClass("far fa-eye-slash").addClass("far fa-eye");
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                // Toggle password visibility for login password field
                $("#toggleLoginPassword").click(function () {
                    const loginPasswordField = $("#logPassword");
                    const type = loginPasswordField.attr("type");
                    if (type === "password") {
                        loginPasswordField.attr("type", "text");
                        $(this).removeClass("far fa-eye").addClass("far fa-eye-slash");
                    } else {
                        loginPasswordField.attr("type", "password");
                        $(this).removeClass("far fa-eye-slash").addClass("far fa-eye");
                    }
                });
            });
        </script>
        
<script>
    var countdownTimer;
    var sessionTimeout = <?php echo $_SESSION['timeout']; ?>; // Session timeout in seconds
    var logoutTimer; // Timer for automatic logout
    var sessionExpired = false;

    function startCountdown() {
        countdownTimer = setTimeout(function () {
            if (!isVideoPlaying() && !sessionExpired) {
                $('#sessionTimeoutModal').modal('show');

                logoutTimer = setTimeout(function () {
                    sessionExpired = true;
                    window.location.href = 'index.php?action=logout';
                }, 60000); // 60 seconds for automatic logout (adjust as needed)
            }
        }, sessionTimeout * 1000); // Convert seconds to milliseconds
    }

    function resetCountdown() {
        clearTimeout(countdownTimer);
        clearTimeout(logoutTimer);
        startCountdown();
    }

    function initSessionTimeout() {
        startCountdown();
        document.addEventListener('mousemove', resetCountdown);
        document.addEventListener('keypress', resetCountdown);
    }

    document.addEventListener('DOMContentLoaded', initSessionTimeout);

    $('#sessionTimeoutModal').on('show.bs.modal', function () {
        clearTimeout(countdownTimer);
        clearTimeout(logoutTimer);
    });

    $('#sessionTimeoutModal').on('hidden.bs.modal', function () {
        if (!sessionExpired) {
            startCountdown();
        }
    });

    $('#continueSessionBtn').on('click', function () {
        $('#sessionTimeoutModal').modal('hide');
        resetCountdown();
    });

    $('#logoutBtn').on('click', function () {
        clearTimeout(countdownTimer);
        clearTimeout(logoutTimer);
        sessionExpired = true;
        window.location.href = 'index.php?action=logout';
    });

    function onVideoPlay() {
        clearTimeout(countdownTimer);
        clearTimeout(logoutTimer);
    }

    function onVideoPause() {
        if (!sessionExpired) {
            startCountdown();
        }
    }

    function isVideoPlaying() {
        var video = document.getElementById('movieVideo');
        return !!(video && !video.paused && !video.ended && video.currentTime > 0);
    }
</script>