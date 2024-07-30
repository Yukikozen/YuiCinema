<html lang="en">
    <head>
        <title>ONLINE BOOKING TICKET</title>
        <?php
        // include_once "../header.php";
        ?>
        <?php
//        if (isset($_GET['valid']) && $_GET['valid'] == 'fail') {
//            $message = "INVALID EMAIL AND USERNAME";
//            echo "<script type='text/javascript'>alert('$message');</script>";
//        }
        if (isset($_SESSION['reg_full_name']) == true) {
        header('Location: index.php?login=success');
        }
        ?>
<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once 'conn/config.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Writer;

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST["btn_reg"])) {
        $uname = $_POST["reg_full_name"];
        $email = $_POST["reg_email"];
        $phone = $_POST["reg_number_txt"];
//        $gender = $_POST["reg_gender_txt"];
        $paswrd = $_POST["reg_psw"];
        $hashed_password = password_hash($paswrd, PASSWORD_DEFAULT);
        $hash = md5(rand(0, 1000));

        $cnfrm_paswrd = $_POST["psw_repeat"];
        $success = true;

//    $paswrd = $_POST["reg_psw"];
//    $cnfrm_paswrd = $_POST["psw_repeat"];

   if (empty($_POST["reg_full_name"])) {
        $errorMessages[] = "Username is required.";
        $success = false;
        }

        if (empty($_POST["reg_psw"])) {
        $errorMessages[] = "Password is required.";
        $success = false;
        }
        if (empty($_POST["psw_repeat"])) {
        $errorMessages[] = "Confirm Password is required.";
        $success = false;
        }
        if (empty($_POST["reg_gender_txt"])) {
        $errorMessages[] = "Gender is required.";
        $success = false;
        }

        if (empty($_POST["reg_email"])) {
        $errorMessages[] = "Email is required.";
        $success = false;
        }

        if (empty($_POST["reg_number_txt"])) {
        $errorMessages[] = "Phone is required.";
        $success = false;
        }
     else {
        $email = sanitize_input($_POST["reg_email"]);
        $uname = sanitize_input($_POST["reg_full_name"]);
        $gender = sanitize_input($_POST["reg_gender_txt"]);
        $phone = sanitize_input($_POST["reg_number_txt"]);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessages[] = "Invalid email format.";
        $success = false;
        }
        if (!is_numeric($_POST["reg_number_txt"])) {
        $errorMessages[] = "Phone must be a numeric value.";
        $success = false;
        }

        if ($paswrd != $cnfrm_paswrd) {
        $errorMessages[] = "Password doesn't match.";
        $success = false;
        }
    }
}

function saveCustomerToDB() {
    ob_start();
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    $uname = $_POST["reg_full_name"];
    $email = $_POST["reg_email"];
    $phone = $_POST["reg_number_txt"];
    $gender = $_POST["reg_gender_txt"];
    $paswrd = $_POST["reg_psw"];
    $hashed_password = password_hash($paswrd, PASSWORD_DEFAULT);
//    $time = time();
    $hash = md5(rand(0, 1000));

    $cnfrm_paswrd = $_POST["psw_repeat"];
    $success = true;

    $sql = $conn->prepare("SELECT username FROM customer where username=?");
    $sql->bind_param('s', $uname);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($unameDB);
    $sql->fetch();

    $userfound = $sql->num_rows;
    if ($userfound >= 1) {
        ob_clean(); // Clean the output buffer before setting the header
        header("Location: index.php?register=fail");
        $success = false;
    } else {
        $time = time();
        // After validating and processing user registration, generate a secret key
       $_gf2a = new Google2FA();

        $secretKey = $_gf2a->generateSecretKey();
        
        
        $sql_insert = $conn->prepare("INSERT INTO customer(username, email, hpnum, gender, password, hash, profilepic, expirytime, secret) VALUES(?,?,?,?,?,?,?,?,?)");
        $a = 'images/Default_pfp.png';
        $sql_insert->bind_param('ssissssss', $uname, $email, $phone, $gender, $hashed_password, $hash, $a, $time,$secretKey);
        $result = $sql_insert->execute();

        if ($result) {
            ob_clean(); // Clean the output buffer before setting the header
            header("Location: index.php?register=success");
            $success = true;
        } else {
            header("Location: index.php?register=fail");
        }

        mysqli_close($conn);
        if ($success) {
            return 1;
        } else {
            return 0;
        }
    }
}


?>

<!--<html lang="en">
    <head>
        <title>ONLINE BOOKING TICKET</title>
        <?php
        // include_once "../header.php";
        ?>
        <?php
//        if (isset($_GET['valid']) && $_GET['valid'] == 'fail') {
//            $message = "INVALID EMAIL AND USERNAME";
//            echo "<script type='text/javascript'>alert('$message');</script>";
//        }
        if (isset($_SESSION['reg_full_name']) == true) {
            header('Location: index.php?login=success');
        }
        ?>
    </head>-->
    <body>
        <?php
        if ($success) {
            if (saveCustomerToDB() == 1) {
                $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                $sql = $conn->prepare("SELECT id, username, email, password, hash FROM customer WHERE username=? && email=?");

                $sql->bind_param('ss', $uname, $email);
                $sql->execute();
                $sql->store_result();
                $sql->bind_result($Id, $uname, $email, $passwd, $hash);
                $sql->fetch();

                $mail = new PHPMailer();
                $mail->CharSet = "utf-8";
                $mail->IsSMTP();
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
                $mail->addAddress($email, $uname);
                $mail->Subject = 'Account has been created';
                $mail->IsHTML(true);
                $msg = "Thanks for signing up! Your account has been created, you can login with the following credentials after you have activated your account by pressing the URL below. <br>
                                    ------------------------ <br>
                                    Username: $uname <br>
                                    Password: $paswrd <br>
                                    ------------------------ <br>

                                    Please click this link to activate your account:
                                    <a href='http://localhost/OnlineShow/verify.php?Id=$Id&hash=$hash'>http://localhost/OnlineShow/verify.php?Id=$Id&hash=$hash</a>";

                $mail->Body = $msg;
                if ($mail->Send()) {
                      include_once 'header.php';
                    echo "<div style='margin-top: 100px' class='ml-5'>";
                    echo "<h4>Your registration was successful!</h4>";
                    echo "Message was Successfully Sent :)";
                    echo "<p>Thank you for signing up, " . $uname;
                    echo "<br>";
                    echo "<a href='index.php'><button class='mb-5'>Return to Home</button></a>";
                    echo "</div>";
                       include_once 'footer.php';
                } else {
                    include_once 'header.php';
                    echo "<div style='margin-top: 100px'>";
                    echo "<h2> Oops! </h2>";
                    echo "<h4>The following input errors were detected:</h4>";
                    echo "Mail Error - >" . $mail->ErrorInfo;
                    echo "</div>";
                    include_once 'footer.php';
                }
            }
        } else {
             include_once 'header.php';
            echo "<div class='ml-5' style='margin-top: 100px'>";
            echo "<h2> Oops! </h2>";
            echo "<h4>The following input errors were detected:</h4>";
            foreach ($errorMessages as $errorMessage) {
            echo "<p>" . $errorMessage . "</p>";
            }
            echo '<a href="index.php"><button class="mb-5">Return to Sign Up</button></a>';
            echo "</div>";
            include_once 'footer.php';
        }
        ?>
        <?php
// include_once "footer.php";
        ?>
    </body>
</html>
