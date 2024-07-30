<!DOCTYPE html>
<html>
    <head>

        <title>Contact Us</title>

    </head>
    <body>
        <?php
        /*
         * To change this license header, choose License Headers in Project Properties.
         * To change this template file, choose Tools | Templates
         * and open the template in the editor.
         */
        include_once("header.php");
        include_once("conn/config.php");
        ?>

        <?php
         function sanitize_input($data)
 {
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data; 
 }
        if (isset($_POST["btn_submit"])) {
            // Include your database configuration here
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

            $name = sanitize_input($_POST["name"]);
            $email =  sanitize_input($_POST["email"]);
            $phone =  sanitize_input($_POST["phonenum"]);
//            $message = $_POST["message"];
//            $message = sanitize_input($_POST["message"]); 
            $message = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

                
            $tbl = "contact"; // Assuming you have a 'contact' table
            // SQL query to insert contact data into the database
            $sql = "INSERT INTO $tbl (name, email, hpnum, msg, msg_date) VALUES (?, ?, ?, ?, NOW())";

            // Create a prepared statement
            $stmt = $conn->prepare($sql);

            // Bind parameters
            $stmt->bind_param('ssss', $name, $email, $phone, $message);

            // Execute the prepared statement
            if ($stmt->execute()) {
                // Insertion was successful, show a success message
                echo '<script>
            $(document).ready(function() {
                $("#contactSuccessModal").modal("show");
            });
        </script>';
            } else {
                // There was an error during insertion, show an error message
                echo '<script>
            $(document).ready(function() {
                $("#contactErrorModal").modal("show");
            });
        </script>';
            }

            // Close the prepared statement
            $stmt->close();

            // Close the database connection
            $conn->close();
        }
        ?>

        <section style="min-height: 450px;">
            <div class="container" style="color:maroon";>
                <div class="col-md-12">
                    <center>
                        <h1>Contact Us</h1>
                        <h5>GET IN TOUCH</h5>
                        <p>We'd love to talk about how we can work together.
                            Send us a message below and we'll respond as soon as possible.</p>
                    </center>
                </div>
                <div class="row" style="color:white;">
                    <div class="col-md-6 mt-5 mb-5" style="border-radius: 30px; background-color:maroon; ">
                        <h2 class="mt-5" >Contact Information</h2>
                        <p class="mt-1">
                            Our team will get back to you within 24 hours.
                        </p>
                        <p class="mt-5">
                            <i class="fa fa-phone mt-3"> &nbsp; 0300-1234567</i>
                        </p>

                        <p class="mt-3">
                            <i class="fa fa-envelope mt-3"> &nbsp; movieticket@live.com</i>
                        </p>

                        <p class="mt-3">
                            <i class="fa fa-map-marker mt-3"> &nbsp; movieticket@live.com</i>
                        </p>

                        <h2 class="mt-5" >Join Us</h2>
                        <div class="mb-5">
                            <a href="#" class="mt-5 ml-3" style="color:white;">
                                <i class="fa fa-facebook-square mt-3 fa-2x"></i>
                            </a>
                            <a href="#" class="mt-5 ml-3" style="color:white;">
                                <i class="fa fa-twitter-square mt-3 ml-3 fa-2x"></i>
                            </a>
                            <a href="#" class="mt-5  ml-3" style="color:white;">
                                <i class="fa fa-instagram mt-3 ml-3 fa-2x"></i>
                            </a>
                        </div> 
                    </div>
                    <div class="col-md-6">
  <form method="POST">
    <div class="container" style="color: maroon;">
        <hr>
        <label for="username"><b>Your Name</b></label>
        <input type="text" style="border-radius: 30px;" placeholder="Enter Name" name="name" id="username" required autocomplete="name">

        <label for="contact_email"><b>Email</b></label>
        <input type="email" style="border-radius: 30px;" placeholder="Enter Email" name="email" id="contact_email" required autocomplete="email">

        <label for="phonenum"><b>Phone Number</b></label>
        <input type="tel" style="border-radius: 30px;" placeholder="Enter Phone Number" name="phonenum" id="phonenum" required autocomplete="tel">

        <label for="message"><b>Message</b></label>
        <textarea name="message" id="message" rows="4" style="resize:none; width:100%; border-radius: 30px;" autocomplete="message"></textarea>

        <button type="submit" name="btn_submit" class="btn" style="background-color: maroon; color: white;">Send Message</button>
    </div>
</form>
                    </div>
                </div>

            </div>
        </div>

    </section>

    <!-- Success Modal for Add Genre -->
    <div class="modal fade" id="contactSuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    We will Contact You Soon on Your Email Address
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once("footer.php");
    ?>
</body>
</html>