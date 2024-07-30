<?php
session_start();
$_SESSION["admin_username"]=null;
$error="";

if(isset($_POST["btn_login"]))
{
   $email_id=$_POST["log_email"];
   $paswrd_log=$_POST["log_psw"];

    if("yuizen08@gmail.com"==$email_id)
    {
        if("zzz1"==$paswrd_log)
        {
            $_SESSION["admin_username"]=$email_id;
            header("Location:dashboard.php");
        }
        else
        {
            $error="Invalid Password";
        }
           
    }
    else
    {
        $error="Invalid Email";
    } 
}
?>





<!doctype html>
<html lang="en">
  <head>
    <title>Admin Panel</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  
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


      
      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!--    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>-->
  
  <?php    include_once 'admin_footer.php';?>
  </body>
  
</html>