<?php
session_start();
// Check if the user is already logged in
//if (isset($_SESSION['username'])) {
//    // Redirect them to the index page or any other page you prefer
//    header('Location: index.php?login=success');
//    exit(); // Make sure to exit to prevent further execution
//}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once 'conn/config.php';
include_once 'header.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
?>

<html lang="en">
    <head>
        <title>REGISTRATION VERIFICATION</title>
        <?php
//session_start();
        if (isset($_SESSION['username']) == true) {
            header('Location:index.php?login=success');
        }

//        if (isset($_GET['valid']) == 'fail') {
//            $message = "INVALID EMAIL AND USERNAME";
//            echo "<script type='text/javascript'>alert('$message');</script>";
             if (isset($_GET['valid']) && $_GET['valid'] == 'fail') {
//            $message = "INVALID EMAIL AND USERNAME";
             if (!empty($errorMessage)) {
        echo '<script>$(document).ready(function() { 
            $("#errorModalLabel").text("Error");
            $("#errorModalBody").text("' . $errorMessage . '");
            $("#errorModal").modal("show");
        });</script>';
    }
        }
        ?>
    </head>
    <body>

        <?php
//    include_once '../header.php';
$endtime = time();

if (!empty($_GET['hash']) && isset($_GET['hash'])) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $hash = $_GET['hash'];

    $sqlhash = $conn->prepare("SELECT username, email, expirytime, verify FROM customer WHERE hash=?");
    $sqlhash->bind_param('s', $hash);
    $sqlhash->execute();
    $sqlhash->store_result();
    $sqlhash->bind_result($uname, $email, $time, $activated);
    $sqlhash->fetch();

 
        if ($sqlhash->num_rows > 0) {
         if ($endtime - $time <= 300) {
            if ($activated == 1) {
                // Account has already been activated
                 echo "<div class='ml-5 mt-5'>";
                echo '<script>$(document).ready(function() { $("#accountActivationModal").modal("show"); });</script>';
//                echo "<div style=''margin-top: 2rem;' class='ml-5'>";
                echo "<h4>Account has already been activated!</h4>";
                echo "<p>You may now log in with your account.<p>";
                echo "<br>";
                echo "<a href='index.php'><button>Return to Home</button></a>";
                echo "</div>";
            } else {
                // Activate the account
                $validate_active = $conn->prepare("UPDATE customer SET verify=? WHERE hash=?");
                $verify = 1;
                $validate_active->bind_param('is', $verify, $hash);
                $result = $validate_active->execute();

                if ($result) {
                    echo "<div class='ml-5 mt-5'>";
                    echo '<script> $(document).ready(function() { $("#activationSuccessModal").modal("show"); });</script>';
                    echo "<h4 class='mt-5'>Activation SUCCESSFUL!</h4>";
                    echo "<p>You may now log in to your account.<p>";
                    echo "<br>";
                    echo "<a href='index.php'><button>Return to Home</button></a>";
                    echo "</div>";
                } else {
                    $message = "Activation UNSUCCESSFUL!";
                    echo "<script type='text/javascript'>alert('$message');</script>";
                }
            }
        }
        else {
            $success = false;
            $delete = $conn->prepare("DELETE FROM customer WHERE email=?");
            $delete->bind_param('s', $email);
            $result = $delete->execute();
            echo "<div class='ml-5 mt-5'>";
            echo '<script>$(document).ready(function() { $("#activationLinkFailModal").modal("show"); });</script>';
            echo "<h4 class='mt-5'>ACTIVATION LINK HAS EXPIRED!!</h4>";
            echo "<p>Please register again!.<p>";
            echo "<br>";
            echo "<a href='index.php'><button>Return to Home</button></a>";
            echo "</div>";

//            $message = "Activation link has expired!";
//            echo "<script type='text/javascript'>alert('$message');</script>";
//            echo "<div style='margin-top: 100px'>";
//            echo "<h4>ACTIVATION LINK HAS EXPIRED!</h4>";
//            echo "<p>Please register again!";
//            echo "<br><br>";
//            echo "&nbsp;";
//            echo "<a href='index.php'><button>Return to Home</button></a>";
//            echo "</div>";
        }
    } else {
        echo "<div style='margin-top: 100px'>";
        echo '<h1>Something went wrong</h1>';
        echo "</div>";
    }
}

        ?>
        
           <!-- Activation Success Modal -->
<div class="modal fade" id="activationSuccessModal" tabindex="-1" role="dialog" aria-labelledby="activationSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activationSuccessModalLabel">Activation SUCCESSFUL!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!--<h4>Activation SUCCESSFUL!</h4>-->
                <p>You may now log in to your account.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="index.php" class="btn btn-primary">Return to Home</a>
            </div>
        </div>
    </div>
</div>
           
            <!-- Account Activation Modal -->
<div class="modal fade" id="accountActivationModal" tabindex="-1" role="dialog" aria-labelledby="accountActivationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accountActivationModalLabel">Account Activated</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Your account has already been activated. You may now log in with your account.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="index.php" class="btn btn-primary">Return to Home</a>
            </div>
        </div>
    </div>
</div>
            
            <!-- Activation Link Unsuccessful Modal -->
<div class="modal fade" id="activationLinkFailModal" tabindex="-1" role="dialog" aria-labelledby="activationLinkFailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activationLinkFailModalLabel">Activation Link Unsuccessful</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The activation link has expired or is invalid. Please register again.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="index.php" class="btn btn-primary">Return to Home</a>
            </div>
        </div>
    </div>
</div>
    </body>

        <?php include_once 'footer.php'; ?>
</html>