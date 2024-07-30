<?php
//include_once("../conn/conn.php");
include_once("../conn/config.php");

if (!isset($_SESSION)) {
    session_start();
}

$showModal = false;
// Set the session timeout duration (in seconds)
$_SESSION['timeout'] = 300; // 5 minutes (300 seconds)

if (isset($_GET["action"])) {
    if ($_GET["action"] == "logout") {
        // Clear the session variables
        $_SESSION["username"] = null;
        $_SESSION["cust_id"] = null;
        
        // Set a flag to indicate that the user has logged out
        $_SESSION["logged_out"] = true;

        // Destroy the session
        session_destroy();
        
        // Clear the countdown timer and hide the modal
        echo '<script>';
        echo 'clearTimeout(countdownTimer);';
        echo '$("#sessionTimeoutModal").modal("hide");';
        echo '</script>';
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Online Movie Ticket Booking</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <style>
            
        </style>
         <title>Admin Panel - Online Movie Ticket</title>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

        <!--<link rel="icon" href="images/logo.png">-->
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <!-- Font Awesome Icons -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
        <!-- font-awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
        

<link rel="stylesheet" href="../css/admin.css">

    </head>


    <body>

        <nav class="navbar navbar-expand-md navbar-dark" style="background-color:maroon">
            <a class="navbar-brand" href="dashboard.php"><img src="../images/png-transparent-movies-logo-the-film-television-logo.png" style="width: 60px;"/></a>
                <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <h5><a class="nav-link" href="dashboard.php">Admin Panel Online Movie Ticket Booking</a></h5>
                        </li>
                        
                    </ul>
            
                    <ul  class="navbar-nav ">
                            <li class="nav-item">
                             <li class="nav-item"> <a class="nav-link"> Hello Admin</a></li>
                            <a class="nav-link" href="../Admin/index.php" >Logout</a>
                            </li>
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
        
    

    
<!--        <script>
            // JavaScript to dynamically update the profile picture in the navigation bar

            document.addEventListener("DOMContentLoaded", function () {
                // Get a reference to the profile picture container
                const profilePicContainer = document.getElementById("profile-pic-container");

                // Function to update the profile picture
                function updateProfilePic() {
                    // Check if the session profilepic value has changed
                    if (profilePicContainer && <?php echo json_encode($_SESSION["profilepic"]); ?> !== profilePicContainer.querySelector("img").src) {
                        // Update the profile picture source
                        profilePicContainer.querySelector("img").src = <?php echo json_encode($_SESSION["profilepic"]); ?>;
                    }
                }

                // Call the updateProfilePic function initially
                updateProfilePic();

                // You can also set up a timer to periodically check for updates
                //     setInterval(updateProfilePic, 1000); // Check every 5 seconds, adjust as needed
            });
        </script>
-->


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



