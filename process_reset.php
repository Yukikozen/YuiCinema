<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once 'conn/config.php';
//include_once 'header.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$email = $_GET['email'];

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if (isset($_POST['btn_change'])) { //change password btn
    $newpwd = $_POST['change_psw'];
//    $hashed_password2 = password_hash($newpwd, PASSWORD_DEFAULT);
    $cfmpwd = $_POST['changepsw_repeat'];
        $hashed_password2 = password_hash($newpwd, PASSWORD_DEFAULT);
    //    $uname = $_GET['uname'];

    $sql = $conn->prepare("SELECT username,email FROM customer WHERE email=?");
    $sql->bind_param('s', $email);
    $member_list = $sql->execute();
    $sql->store_result();
    $sql->bind_result($uname, $email);
    $sql->fetch();
    $success = true;

    if (empty($uname) || empty($newpwd) || empty($cfmpwd)) {
        $success = false;
    }

    if ($success) {
//        if ($newpwd != $cfmpwd) { //if user inputs passwords do not match
//            $message = "Passwords do not match!";
//            echo "<script type='text/javascript'>alert('$message');</script>";
//            $success = false;
//            header("Location: resetPassword.php?retry=fail");
//        } else { // if success, update the new password in hashed format
            $hashed_password2 = password_hash($newpwd, PASSWORD_DEFAULT);
            $sql = $conn->prepare("UPDATE customer SET password=? WHERE username=?");
            $sql->bind_param('ss', $hashed_password2, $uname);
            $result = $sql->execute();

            if ($result) {
                $mail = new PHPMailer();
                $mail->CharSet = "utf-8";
                $mail->IsSMTP();
                $mail->Host = SMTPSSL;
                // enable SMTP authentication

                $mail->SMTPAuth = true;
                // GMAIL username
                $mail->Username = SMTPUSER;
                // GMAIL password
                $mail->Password = SMTPPASS;
                $mail->SMTPSecure = SMTPSEC;
                // sets GMAIL as the SMTP server
                $mail->Host = SMTPHOST;
                // set the SMTP port for the GMAIL server
                $mail->Port = SMTPPORT;
                $mail->From = SMTPFROM;
                $mail->FromName = SMTPFROMNAME;
                $mail->AddAddress($email, $uname);
                $mail->Subject = 'Password Changed';
                $mail->IsHTML(true);

                $msg = "<p>Your password has been changed.</p> <br>
                ------------------------ <br>
                Username: $uname <br>
                Password: $newpwd<br>
                ------------------------ <br>
                Please click this link to log in to your PV account:
                <a href='http://localhost/OnlineShow/index.php'>http://localhost/OnlineShow/index.php</a>";

                $mail->Body = $msg;
                if ($mail->Send()) {
                    include_once 'header.php';
                    echo "<div style='margin-top: 100px;' class='ml-5'>";
                    echo "<h4> Password has been reset.</h4>";
                    echo "Message was Successfully Sent :) <br> <br>";
                    echo "<a href='index.php'><button class='mb-5'>Return to Home</button></a>";
                    echo "</div>";
                } else {
                    $message = "Password Change UNSUCCESSFUL!";
                    echo "<div style='margin-top: 100px' class='ml-5'>";
                    echo "<h2> Oops! </h2>";
                    echo "<h4>The following input errors were detected:</h4>";
                    echo "<script type='text/javascript'>alert('$message');</script>";
                    echo "Mail Error - >" . $mail->ErrorInfo;
                    echo "</div>";
                }
            } else {// if there is no record of the username
                $email = $_GET['email'];
                header("Location: resetPassword.php?update=fail&email=$email");
            }
        }
    }
//}

mysqli_close($conn);
?>

<html lang="en">
<head>
    <title>RESET PASSWORD</title>
    <?php
    //session_start();
    if (isset($_SESSION['username']) == true) {
        header('Location:index.php?login=success');
    }

    if (isset($_GET['valid']) == 'fail') {
        $message = "INVALID EMAIL AND USERNAME";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
    ?>
</head>
<body>

</body>

<?php include_once 'footer.php'; ?>
</html>
