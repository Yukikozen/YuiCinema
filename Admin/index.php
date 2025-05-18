<?php
session_start();
$_SESSION["admin_username"] = null;
$error = "";

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "yuicinema"; // <-- change to your DB name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["btn_login"])) {
    $email_id = $_POST["log_email"];
    $paswrd_log = $_POST["log_psw"];

    $sql = "SELECT password FROM admin WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($paswrd_log, $row['password'])) {
            $_SESSION["admin_username"] = $email_id;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid Password";
        }
    } else {
        $error = "Invalid Email";
    }
}

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <title>Admin Panel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <!-- Optional: support PNG -->
<link rel="icon" type="image/png" href="../images/3461151.png?v=2">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          crossorigin="anonymous">

    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6" style="margin:auto;">
            <form method="post">
                <div class="container" style="color:maroon;">
                    <center>
                        <h1> Admin Login</h1>
                    </center>
                    <hr>
                    <label for="email"><b>Email</b></label>
                    <input type="text" style="border-radius:30px;" placeholder="Enter Email" name="log_email" id="email" required>

                    <label for="psw"><b>Password</b></label>
                    <input type="password" style="border-radius:30px;" placeholder="Enter Password" name="log_psw" id="psw" required>

                    <button type="submit" name="btn_login" class="btn" style="background-color:maroon;color:white;">Login</button>
                </div>
            </form>
            <p style="color:maroon;margin-left:1%;"><?php echo $error; ?></p>
        </div>
    </div>
</div>

<?php include_once 'admin_footer.php'; ?>
</body>
</html>
