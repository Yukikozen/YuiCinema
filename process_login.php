<?php
// Include dependencies
require 'vendor/autoload.php';
include_once("conn/config.php");
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Writer;

// Start session and output buffering
if (!isset($_SESSION)) {
    session_start();
}

// DB connection
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if (!$conn) {
    die("Database connection error: " . mysqli_connect_error());
}

// Initialize OTP attempts
if (!isset($_SESSION['otp_attempts'])) {
    $_SESSION['otp_attempts'] = 0;
}

// Handle login
if (isset($_POST["btn_login"])) {
    $user_id = $_POST["log_user"];
    $paswrd_log = $_POST["log_psw"];

    // Step 1: Check if the username exists in the database
    $stmt = $conn->prepare("SELECT id, username, email, password, verify, profilepic, secret, disabled FROM customer WHERE username = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();

    // If username doesn't exist, redirect immediately
    if ($stmt->num_rows == 0) {
        header("Location: index.php?login=fail");
        exit();  // Ensure no further execution
    }

    // Step 2: Fetch user data (username exists)
    $stmt->bind_result($cust_id, $username, $email, $hashed_password, $verify, $profilepic, $secret, $disabled);
    $stmt->fetch();

    // Step 3: Check if the account is disabled
    if ($disabled == 1) {
        // If account is locked, redirect to the locked page with the remaining time
        $remainingTime = max(0, ($_SESSION['lockout_start'] + 300) - time());
        header("Location: index.php?login=locked&remainingTime=" . $remainingTime);
        exit();  // Stop further execution
    }

    // Step 4: Handle login attempts and password validation
    if (!password_verify($paswrd_log, $hashed_password)) {
        // Increment login attempts
        $_SESSION["login_attempts"] = ($_SESSION["login_attempts"] ?? 0) + 1;

        if ($_SESSION["login_attempts"] >= 3) {
            // Disable the account after 3 failed login attempts
            $_SESSION["login_attempts"] = 3;
            $_SESSION["lockout_start"] = time();

            $updateStmt = $conn->prepare("UPDATE customer SET disabled = 1 WHERE username = ?");
            $updateStmt->bind_param("s", $user_id);
            $updateStmt->execute();
            
            // Redirect to index.php with a message indicating account disabled
            $_SESSION['login_error_message'] = 'Your account has been disabled due to too many failed login attempts.';
            header("Location: index.php?login=disabled");
            exit();  // Stop further script execution
        } else {
            // If it's just a failed login attempt, redirect back to index with failure message
            header("Location: index.php?login=fail");
            exit();  // Stop further script execution
        }
    }

    // Step 5: Successful login
    $_SESSION["username"] = $username;
    $_SESSION["cust_id"] = $cust_id;
    $_SESSION["login_success"] = true;
    $_SESSION["login_attempts"] = 0;
    $_SESSION["show_otp_modal"] = true;

    if (empty($profilepic) && isset($_SESSION["profilepic"])) {
        $profilepic = $_SESSION["profilepic"];
    }

    $_SESSION["profilepic"] = $profilepic ?: 'images/Default_pfp.jpg';

    // Step 6: Setup 2FA
    $_gf2a = new Google2FA();

    // Ensure the secret is valid before proceeding
    if (empty($secret) || strlen($secret) < 16) {
        // Generate a new secret key if it's missing or too short
        $secret = $_gf2a->generateSecretKey();
        $_SESSION['g2fa_secret'] = $secret;
    } else {
        $_SESSION['g2fa_secret'] = $secret;
    }

    // Generate QR code for Google Authenticator
    $qrCodeUrl = $_gf2a->getQrCodeUrl('OnlineShow', $email, $secret);
    $renderer = new ImageRenderer(new RendererStyle(250), new ImagickImageBackEnd());
    $writer = new Writer($renderer);

    $_SESSION['encoded_qr_data'] = base64_encode($writer->writeString($qrCodeUrl));
    $_SESSION['otp'] = $_gf2a->getCurrentOtp($secret);

    // Redirect to OTP verification page
    header("Location: index.php?login=verifying");
    exit();  // Stop further execution after successful login setup
}

// Handle OTP verification
if (isset($_POST['btn_verify_otp'])) {
    if (isset($_SESSION['g2fa_secret']) && !empty($_SESSION['g2fa_secret'])) {
        $_g2fa = new Google2FA();
        $secret = $_SESSION['g2fa_secret'];
        $otp = $_POST['otpCode'];

        // Verify the OTP
        if ($_g2fa->verifyKey($secret, $otp)) {
            header('Location: index.php?login=success');
            exit();
        } else {
            $_SESSION['show_otp_modal'] = true;
            $_SESSION['otp_attempts']++;

            if ($_SESSION['otp_attempts'] >= 3) {
                session_destroy();
                header('Location: index.php?action=logout');
                exit();
            }
            header('Location: index.php?login=invalid_otp');
            exit();
        }
    } else {
        // Handle missing or invalid session for 2FA secret
        header('Location: index.php?login=error');
        exit();
    }
}
?>
