<?php
require 'vendor/autoload.php';
include_once("conn/config.php");
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Writer;

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET["action"]) && $_GET["action"] == "logout") {
    $_SESSION["username"] = null;
    $_SESSION["cust_id"] = null;
    $_SESSION["profilepic"] = null;
    session_destroy();
}

// Initialize the session variable to track the number of OTP attempts
if (!isset($_SESSION['otp_attempts'])) {
    $_SESSION['otp_attempts'] = 0;
}

if (isset($_POST["btn_login"])) {
    $user_id = $_POST["log_user"];
    $paswrd_log = $_POST["log_psw"];

    // Check if a new profile picture was uploaded during login
    if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] === UPLOAD_ERR_OK) {
        $target_dir = "images/profile_pictures";
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $_SESSION["profilepic"] = $target_file;
        }
    }

    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    if (!$conn) {
        die("Database connection error: " . mysqli_connect_error());
    }
    
    // Check if the user is temporarily locked out due to too many failed login attempts
    if (isset($_SESSION["login_attempts"]) && $_SESSION["login_attempts"] >= 3) {
        $lockoutTime = 300; // 5 minutes in seconds
        $lockoutEndTime = $_SESSION["lockout_start"] + $lockoutTime;

        if (time() < $lockoutEndTime) {
            $remainingTime = $lockoutEndTime - time();
            // Redirect with a message indicating lockout
            header("Location: index.php?login=locked&remainingTime=" . $remainingTime);
            exit();
        } else {
            // If the lockout period has expired, reset login attempts
            $_SESSION["login_attempts"] = 0;
        }
    }

    $stmt = $conn->prepare("SELECT id, username, email, password, verify, profilepic,secret FROM customer WHERE username = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($cust_id, $username, $email, $hashed_password, $verify, $profilepic, $secret);
        
        while ($stmt->fetch()) {
            if (password_verify($paswrd_log, $hashed_password)) {
                if ($verify == 1) {
                      // Check if the secret is empty
           if (empty($secret)) {
                                   $_gf2a = new Google2FA();

                    // If the secret is empty, generate a new secret for the user
                    $newSecret = $_gf2a->generateSecretKey();
                    
                    // Update the user's secret in the database
                    $updateStmt = $conn->prepare("UPDATE customer SET secret = ? WHERE id = ?");
                    $updateStmt->bind_param("si", $newSecret, $cust_id);
                    $updateStmt->execute();

                    // Set the user's new secret in the session
                    $_SESSION['g2fa_secret'] = $newSecret;

                    // Generate the QR code for the new secret
                    $app_name = 'OnlineShow';
                    $qrCodeUrl = $_gf2a->getQrCodeUrl($app_name, $user->email, $newSecret);

                    $renderer = new ImageRenderer(new RendererStyle(250), new ImagickImageBackEnd());
                    $writer = new Writer($renderer);

                    // Generate and store the QR code data in the session
                    $encoded_qr_data = base64_encode($writer->writeString($qrCodeUrl));
                    $_SESSION['encoded_qr_data'] = $encoded_qr_data;
                    
                    // Redirect to the verifying page
                    header("Location: index.php?login=verifying");
                    exit();
                }
                    // Successful login, reset login attempts to 0.
                    $login_attempts = 0;

                    // Set session variables as before.
                    $_SESSION["username"] = $username;
                    $_SESSION["cust_id"] = $cust_id;

                    // Set a session variable to indicate successful login
                    $_SESSION["login_success"] = true;

                    // Set a session variable to indicate that the OTP modal should be displayed
                    $_SESSION['show_otp_modal'] = true;

                    // Reset login attempts on successful login
                    $_SESSION["login_attempts"] = 0;
//        $_SESSION['g2fa_secret'] = $secret; // Store the 2FA secret

                    if (empty($profilepic) && isset($_SESSION["profilepic"])) {
                        $profilepic = $_SESSION["profilepic"];
                    }

                    if (empty($profilepic)) {
                        $defaultProfilePic = 'images/Default_pfp.jpg';
                        $_SESSION["profilepic"] = $defaultProfilePic;
                    } else {
                        $_SESSION["profilepic"] = $profilepic;
                    }

                    
                    // Google 2FA setup
                    $_gf2a = new Google2FA();
                    $user = new stdClass();
                    $user->google2fa_secret = $_gf2a->generateSecretKey();
                    $user->email = $email;

                    // Store the secret key in the session
//                    $_SESSION['g2fa_secret'] = $user->google2fa_secret;
                    $_SESSION['g2fa_secret'] = $secret;

                    $app_name = 'OnlineShow';
                    $qrCodeUrl = $_gf2a->getQrCodeUrl($app_name, $user->email, $secret);

                    $renderer = new ImageRenderer(new RendererStyle(250), new ImagickImageBackEnd());
                    $writer = new Writer($renderer);

                    // Generate and store the QR code data in the session
                    $encoded_qr_data = base64_encode($writer->writeString($qrCodeUrl));
                    $_SESSION['encoded_qr_data'] = $encoded_qr_data;
                     $current_otp = $_gf2a->getCurrentOtp($_SESSION['g2fa_secret']);
                    $_SESSION['otp'] = $current_otp;

                    header("Location: index.php?login=verifying");
                } else {
                    header("Location: index.php?verify=fail");
                }
            }
        }
        
        // Increment login attempts on failed login
        if (isset($_SESSION["login_attempts"])) {
            $_SESSION["login_attempts"]++;
        } else {
            $_SESSION["login_attempts"] = 1;
            $_SESSION["lockout_start"] = time(); // Record lockout start time
        }

        if ($_SESSION["login_attempts"] >= 3) {
            // If login attempts exceed 3, disable the account and set lockout time
            $_SESSION["login_attempts"] = 3; // Cap login attempts at 3
            header("Location: index.php?login=disabled");
            exit();
        }
    }
}
?>

<?php
if (isset($_POST['btn_verify_otp'])) {
    // Initialize the Google2FA object
    $_g2fa = new Google2FA();

    // Retrieve the secret key from your database based on the user's identity
    $secret = $_SESSION['g2fa_secret'];

    // Get the OTP value from the form
    $otp = $_POST['otpCode'];

    // Verify the OTP
    $valid = $_g2fa->verifyKey($secret, $otp);

    if ($valid) {
        // OTP is valid
        // You can set a session variable to indicate successful login or do any other actions here.
        header('Location: index.php?login=success');
        exit();
    } else {
        // Invalid OTP
        $_SESSION['show_otp_modal'] = true; // Set to true to display the OTP modal
        $_SESSION['otp_attempts']++; // Increment OTP attempts

        if ($_SESSION['otp_attempts'] >= 3) {
            // User has exceeded the limit, log them out
            session_destroy();
            header('Location: index.php?action=logout');
            exit();
        }

        header('Location: index.php?login=invalid_otp');
        exit();
    }
}
?>