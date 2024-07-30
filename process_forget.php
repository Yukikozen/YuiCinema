<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once 'conn/config.php';
//include_once 'header.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$forgettime = time();
$forgettime1 = $forgettime;
// echo $forgettime;
if (isset($_POST['btn_forget'])) {
    $success = true;
    $uname = $_POST['forget_user'];
    $email = $_POST['forget_email'];

    $uname = sanitize_input($_POST["forget_user"]);
    $email = sanitize_input($_POST["forget_email"]);

    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        $sql = $conn->prepare("SELECT id FROM customer WHERE username =? && email =?");
        $sql->bind_param('ss', $uname, $email);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($Id);
        $sql->fetch();

        if ($sql->num_rows > 0) {
            $sqlforget = $conn->prepare("UPDATE customer SET forgetExpire_time=? WHERE username=?");
            $sqlforget->bind_param('ss', $forgettime1, $uname);
            $sqlforget->execute();
            $sqlforget->store_result();
            $success = true;
        } else {
            $success = false;
            header("Location: forget_pw.php?valid=fail");
        }
    }

    $conn->close();
} else {
    include_once 'header.php';
    echo "<div style='margin-top: 100px'>";
    echo "<h2> Oops! </h2>";
    echo "<h3>The following input errors were detected:</h3>";
    echo "Username not found or email doesn't match...";
    echo "</div>";
    include_once 'footer.php';
    $success = false;
}
?>

<html lang="en">
    <head>
        <title>RESET PASSWORD</title>
<?php
//session_start();
if (isset($_SESSION['username']) == true) {
    header('Location:index.php?login=success');
}

//if (isset($_GET['valid']) == 'fail') {
//    $message = "INVALID EMAIL AND USERNAME";
//    echo "<script type='text/javascript'>alert('$message');</script>";
//}
?>
    </head>
    <body>
        <?php
        if ($success) {
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
            $sql = $conn->prepare("SELECT id, username, email FROM customer WHERE username=? && email=?");
            $sql->bind_param('ss', $uname, $email);
            $sql->execute();
            $sql->store_result();
            $sql->bind_result($Id, $uname, $email);
            $sql->fetch();

            $hash_pw = md5(rand(0, 1000));

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
            $mail->Subject = 'Reset your password for PV account';
            $mail->IsHTML(true);

            $msg = "<p>We received a password reset request. The link to reset your password is below if you did not make this request, you can ignore the email</p><br>
        
				------------------------<br>
				Username: $uname    <br>
				------------------------<br>
				 
				Please click this link to reset your account password:<br>
                                <a href =http://localhost/OnlineShow/resetpassword.php?Id=$Id&email=$email&hash=$hash_pw>http://localhost/OnlineShow/resetpassword.php?Id=$Id&email=$email&hash=$hash_pw</a>";

            $mail->Body = $msg;
            if ($mail->Send()) {
                include_once 'header.php';
                echo "<div style='margin-top: 100px' class='ml-5' 'mb-5'>";
                echo "<h4>Email Verification</h4>";
                echo "Message was Successfully Send :) <br>";
               echo "<br>";
                    echo "<a href='index.php'><button class='mb-5'>Return to Home</button></a>";
                    echo "</div>";
                include_once 'footer.php';
            } else {
                include_once 'header.php';
                echo "<div style='margin-top: 100px' class='ml-5' 'mb-5'>";
                echo "<h2> Oops! </h2>";
                echo "<h4>The following input errors were detected:</h4>";
                echo "Mail Error - >" . $mail->ErrorInfo;
                echo "</div>";
                include_once 'footer.php';
            }
        }
        ?>

    </body>

        <?php // include_once 'footer.php'; ?>
</html>