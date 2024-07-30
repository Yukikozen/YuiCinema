<?php
include_once 'header.php';
$endtimereset = time();
$success = false;
// include_once 'conn/config.php';
if (isset($_GET['email']) && (isset($_GET['hash']))) { //change password btn
    $email = $_GET['email'];
//    echo $email;
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $sqlhash = $conn->prepare("SELECT forgetExpire_time, username FROM customer WHERE email=?");
    $sqlhash->bind_param('s', $email);
    $sqlhash->execute();
    $sqlhash->store_result();
    $sqlhash->bind_result($time, $uname);
    $sqlhash->fetch();
    if (($endtimereset - $time) < 600) {

        $success = true;
    } else {
    
        $success = false;
    echo '<script>$(document).ready(function() { $("#linkExpiredModal").modal("show"); });</script>';
    }
} else if (isset($_GET['update'])) {
    $message = "RESET PASSWORD FAILED due to wrong username";
    echo "<script type='text/javascript'>alert('$message');</script>";
    $success = true;
    $email = $_GET['email'];
} else {
    $success = false;
}

// Remove the following lines that check for empty fields
if (isset($_POST['btn_change'])) {
    $password = $_POST['change_psw'];
    $confirmPassword = $_POST['changepsw_repeat'];

    if (empty($password) || empty($confirmPassword)) {
        // Password fields are blank, show the blank password modal
        echo '<script>$(document).ready(function() { $("#blankPasswordModal").modal("show"); });</script>';
        $success = false;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<?php
//        include_once "header.php";
?>
<?php
if (isset($_SESSION['username']) == true) {
    header('Location:index.php?login=success');
}
?>

    <head>
        <title>RESET PASSWORD</title>
        <!--        <link href="css/proreset.css" rel="stylesheet">-->
<!--    <script>
function validate() {
    var password = document.getElementById('change_psw').value;
    var confirmPassword = document.getElementById('changepsw_repeat').value;

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false; // Prevent form submission when passwords don't match
    }

    return true; // Allow form submission when passwords match
}
</script>-->
    </head>
    <body>
<?php
//    include "styles.inc.php";
?>

<?php
if ($success) {
    ?>
            <div class="modal-body">

            <form action="process_reset.php?email=<?php echo $email; ?>" method="POST" name="changepwd" id="changepwd">

                    <div class="container" style="color:maroon;">
                        <center>
                            <h1>Change Password</h1>
                            <p>Please fill in this form to change password.</p>
                        </center>
                        <label class=label for="Username">Username:</label>
                        <div class=text-left>
                            <input type="text" id="uname"  style="border-radius:30px;" name="uname" placeholder="Username" value="<?php echo $uname ?>" title="Enter your username in alphabets or alphabets with numbers"><br>
                        </div>
                        <hr>
                        <label for="psw"><b>Password</b></label>
                        <input type="password" style="border-radius:30px;" placeholder="Enter Password" name="change_psw" id="psw">

                        <label for="psw1"><b>Repeat Password</b></label>
                        <input type="password" style="border-radius:30px;" placeholder="Repeat Password" name="changepsw_repeat" id="psw1">



                        <button type="submit" class="btn" name="btn_change" style="background-color:maroon;color:white;">Reset Password</button>
                        <hr>
                    </div>

                    <div class="container">
                        <p>Already have an account? <a  style="color:gray" data-toggle="modal" data-target="#modelId1" data-dismiss="modal">Log in</a>.</p>
                    </div>
                </form>
            </div>

    <?php
} 
//
else {
    $success= false;
    echo "<div style='margin-top: 100px' class='ml-5'>";
    echo "<h3 style='color:red'> Oops! </h3>";
    echo "<h4>RESET PASSWORD LINK HAS EXPIRED</h4><br>";
    echo "<a href=forget_pw.php><button class='mb-5'>Return Forget Password Page</button></a>";
    echo "</div>";
}
?>
        
<!-- Add your modal code for blank password fields -->
<div class="modal" id="blankPasswordModal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Blank Password Fields</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Password and Confirm Password fields cannot be blank. Please enter your new password.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="linkExpiredModal" class="modal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Link Expired</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Link has expired. Please request a new link.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="passwordMismatchModal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Password Mismatch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Password and Confirm Password do not match. Please try again.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
        
<!--         Add your JavaScript to display the modal if the fields are blank 
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check if the "Reset Password" button was clicked
        const resetButton = document.querySelector('button[name="btn_change"]');
        if (resetButton) {
            resetButton.addEventListener('click', function () {
                const password = document.getElementById('psw').value.trim();
                const confirmPassword = document.getElementById('psw_repeat').value.trim();
                if (password === '' && confirmPassword === '') {
                    // Password fields are blank, show the blank password modal
                    $('#blankPasswordModal').modal('show');
                }
            });
        }
    });
</script>-->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const resetButton = document.querySelector('button[name="btn_change"]');
    if (resetButton) {
        resetButton.addEventListener('click', function (e) {
            const password = document.getElementById('psw').value.trim();
            const confirmPassword = document.getElementById('psw1').value.trim(); // Updated ID here
            console.log('Password:', password);
            console.log('Confirm Password:', confirmPassword);
            if (password === '' || confirmPassword === '') {
                e.preventDefault(); // Prevent the form from being submitted
                $('#blankPasswordModal').modal('show');
            }
        });
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const resetButton = document.querySelector('button[name="btn_change"]');
    if (resetButton) {
        resetButton.addEventListener('click', function (e) {
            const password = document.getElementById('psw').value.trim();
            const confirmPassword = document.getElementById('psw1').value.trim();
            if (password !== confirmPassword) {
                e.preventDefault(); // Prevent the form from being submitted
                $('#passwordMismatchModal').modal('show'); // Display the error modal
            }
        });
    }
});
</script>

<?php
include_once "footer.php";
?>
    </body>
</html>