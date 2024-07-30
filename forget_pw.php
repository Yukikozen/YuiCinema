<!DOCTYPE html>
<html>
    <head>
        <title>FORGET PASSWORD</title>
        <!--<link rel="stylesheet" href="css/forgetpw_css.css">-->
        <?php
        include_once "header.php";
        ?>

        <?php
        if (isset($_SESSION['username']) == true) {
            header('Location:index.php?login=success');
        }

//    if (isset($_GET['valid']) && $_GET['valid'] == 'fail') {
//    if (!empty($errorMessage)) {
//        echo '<script>$(document).ready(function() { 
//            $("#invalidEmailUsernameModal").modal("show");
//        });</script>';
//    }
//}
      if (isset($_GET['valid']) && $_GET['valid'] == 'fail') {
    echo '<script>$(document).ready(function() { 
        $("#invalidEmailUsernameModal").modal("show");
    });</script>';
}
        ?>
    </head>
    <div class="modal-body">
        <form method="POST" action="process_forget.php">
            <div class="container" style="color:maroon;">
                <center>
                    <h1>Forget Password</h1>
                    <p>Please fill in this form to reset password.</p>
                </center>

                <hr>

                <label for="username"><b>Username</b></label>
                <input type="text" style="border-radius:30px;" placeholder="Enter Your Username" id="username" name="forget_user" required>

                <label for="forget_email"><b>Email</b></label>
                <input type="text" style="border-radius:30px;" placeholder="Enter Email" name="forget_email" id="forget_email" required>

                <button type="submit" class="btn" name="btn_forget" style="background-color:maroon;color:white;">Reset Password</button>
                <hr>
            </div>

            <div class="container">
                <p>Already have an account? <a  style="color:gray" data-toggle="modal" data-target="#modelId1" data-dismiss="modal">Log in</a>.</p>
            </div>
        </form>
    </div>
<!-- Invalid Email and Username Modal -->
<div class="modal fade" id="invalidEmailUsernameModal" tabindex="-1" role="dialog" aria-labelledby="invalidEmailUsernameModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invalidEmailUsernameModalLabel">Invalid Email and Username</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Invalid email and username. Please check your information and try again.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- You can add any action you prefer here, like returning to the login or registration page -->
            </div>
        </div>
    </div>
</div>
</body>
<?php
include_once "footer.php";
?>
</html>


