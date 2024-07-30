<?php
session_start();
include_once '../conn/config.php';
//$name = "";



if(empty($_SESSION["admin_username"]))
{
    header("Location:index.php");
}

else
{
  
    include_once("admin_header.php");
    
   
    // Process Slider Update
// Process Edit Slider
if (isset($_POST['btn_edit_slider'])) {
    $slider_id = $_POST['slider_id'];
    $edit_alt = $_POST['edit_alt'];

    // Check if the new alt text already exists in the database
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $check_query = "SELECT id FROM slider WHERE alt = ? AND id != ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param('si', $edit_alt, $slider_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Alt text already exists, handle accordingly (e.g., show error message)
        $error_message = "Alt text already exists in the database.";
    } else {
        $imgpath = ""; // Initialize the imgpath variable
        $old_img_path = ""; // Initialize the old_img_path variable

        // Retrieve the existing imgpath from the database
        $select_query = "SELECT imgpath FROM slider WHERE id = ?";
        $select_stmt = $conn->prepare($select_query);
        $select_stmt->bind_param('i', $slider_id);
        $select_stmt->execute();
        $select_stmt->bind_result($old_img_path);
        $select_stmt->fetch();
        $select_stmt->close();

        // Check if a new image is selected
        if ($_FILES['fileToUpload']['name'] !== "") {
            $target_dir = "../images/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a valid image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 5000000) {
                $uploadOk = 0;
            }

            // Allow only certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $uploadOk = 0;
            }

            if ($uploadOk == 1) {
    // Delete old image if it exists
    if (!empty($old_img_path)) {
        $old_image_path = "../" . $old_img_path;
        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }
    }

    // Move the uploaded image to the target directory
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $imgpath = "images/" . $_FILES["fileToUpload"]["name"];
    }
}
        } else {
            // No new image selected, use the existing imgpath
            $imgpath = $old_img_path;
        }

        // Update the slider record
        $update_query = "UPDATE slider SET imgpath=?, alt=? WHERE id=?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('ssi', $imgpath, $edit_alt, $slider_id);

        if ($update_stmt->execute()) {
            // Success, refresh the page
            echo '<script>
                $(document).ready(function() {
                    $("#editSuccessModal").modal("show");
                });
            </script>';
        } else {
            echo '<script>
                $(document).ready(function() {
                    $("#editErrorModal").modal("show");
                });
            </script>';
        }

        $update_stmt->close();
        $conn->close();
    }
}



 // Process Add Slider
    if (isset($_POST['btn_add_slider'])) {
        $add_alt = $_POST['add_alt'];
        $imgpath = ""; // Initialize the imgpath variable

        // Check if the alt text already exists in the database
        $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $check_query = "SELECT id FROM slider WHERE alt = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param('s', $add_alt);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // Alt text already exists, handle accordingly (e.g., show error message)
            $error_message = "Alt text already exists in the database.";
        } else {
            if ($_FILES['fileToUpload']['name'] !== "") {
                $target_dir = "../images/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }

                // Check if file already exists
                if (file_exists($target_file)) {
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["fileToUpload"]["size"] > 5000000) {
                    $uploadOk = 0;
                }

                // Allow only certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $uploadOk = 0;
                }

                if ($uploadOk == 1) {
                    // Move the uploaded image to the target directory
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $imgpath = "images/" . $_FILES["fileToUpload"]["name"];
                    }
                }
            }

            // Insert new slider record
            $insert_query = "INSERT INTO slider (imgpath, alt) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param('ss', $imgpath, $add_alt);
            if ($insert_stmt->execute()) {
                // Success, refresh the page
                echo '<script>
                $(document).ready(function() {
                    $("#addSliderSuccessModal").modal("show");
                });
            </script>';
            } else {
                // Error, handle accordingly
                echo '<script>
                $(document).ready(function() {
                    $("#addSliderErrorModal").modal("show");
                });
            </script>';
            }
            $insert_stmt->close();
            $conn->close();
        }
    }
    
// Process Delete Slider
if (isset($_POST['btn_delete_slider'])) {
    $slider_id = $_POST['slider_id'];

    // Retrieve the image path for the slider
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $select_query = "SELECT imgpath FROM slider WHERE id = ?";
    $select_stmt = $conn->prepare($select_query);
    $select_stmt->bind_param('i', $slider_id);
    $select_stmt->execute();
    $select_stmt->bind_result($imgpath);
    $select_stmt->fetch();
    $select_stmt->close();
    
    // Delete associated image file if it exists
        if (!empty($imgpath)) {
            $image_path = "../" . $imgpath;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

    // Delete the slider record from the database
    $delete_query = "DELETE FROM slider WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param('i', $slider_id);

    if ($delete_stmt->execute()) {
        

        // Success, refresh the page
       echo '<script>
        $(document).ready(function() {
            $("#deleteSuccessModal").modal("show");
        });
    </script>';
    } else {
        echo '<script>
        $(document).ready(function() {
            $("#deleteErrorModal").modal("show");
        });
    </script>';
    }
    $delete_stmt->close();
    $conn->close();
}
}
?>
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-2" style="background-color:maroon;">
                            <?php include('admin_sidenavbar.php'); ?>
                        </div>
                        <div class="col-md-10">
                            <h5 class="text-center mt-2" style="color:maroon;">Slider Details</h5>
                            <!--<a href="addgenre.php">Add Genre</a>-->
                            
                            <table class="table mt-5" border="1">
                                <thead style="background-color:maroon;color:white;">
                                    <?php
                                    // Get the current page number from the URL query parameter
                                    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    // Define the number of records per page
                                    $records_per_page = 10;

                                    // Calculate the offset for SQL query
                                    $offset = ($current_page - 1) * $records_per_page;
                                    // Prepare the statement
                                    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

                                    $query = "SELECT id,imgpath,alt FROM slider LIMIT ?,?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param('ii', $offset, $records_per_page);

                                    // Execute the statement
                                    $stmt->execute();
                                    
                                    // Bind result variables
                                    $stmt->bind_result($id, $imgpath, $alt);
                                    
                                    $rows = [];
                                    while ($stmt->fetch()) {
                                    $rows[] = ["id" => $id, "imgpath" => $imgpath, "alt" => $alt];
                                        }
//                                    // Close the statement
//                                    $stmt->close();

                                    // Count total records
                                    $total_records = mysqli_query($conn, "SELECT COUNT(*) FROM slider")->fetch_row()[0];

                                    // Calculate total pages
                                    $total_pages = ceil($total_records / $records_per_page);
                                        
                                    ?>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Alt</th>
                                         <th>Action</th>
                                    </tr>
                                    
                                </thead>
                           <tbody>
                               <?php
                            if (empty($rows)) {
                                echo '<tr><td colspan="6">No records found.</td></tr>';
                                }
//                               <?php
                                else{
                               foreach ($rows as $row) {
                                echo "<tr>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td><img src='../{$row['imgpath']}' alt='{$row['alt']}' width='250rem'></td>";
                                echo "<td>{$row['alt']}</td>";
                                echo "<td>";
                                echo "<button class='btn btn-primary' data-toggle='modal' data-target='#editModal_{$row['id']}'>Edit</button> | ";
                                echo "<button class='btn btn-danger' data-toggle='modal' data-target='#deleteModal_{$row['id']}'>Delete</button> ";
                               echo "</td>";
                                echo "</tr>";
                                }
                                
                                }
                                
                                ?>
                               
                               
               
                    </tbody>

                    <?php
// Close the statement and connection
                               
$stmt->close();
$conn->close();
?>
                            </table>
                            
            <div class="pagination  d-flex justify-content-center col-md-12"">
            <?php
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<span class="page-number"><a href="?page=' . $i . ' ">' . $i . '</a> ';
            }
            ?>
            
        </div>
        <br>
           <div class="col-md-12 text-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#addSliderModal">Add Slider</button>
            </div>
                            
                        </div>
                    </div>
                </div>
                
            </section>

<?php foreach ($rows as $row) : ?>
<div class="modal fade" id="editModal_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <h5 class="modal-title">Edit Slider</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <!-- Display existing image -->
                        <img src="../<?php echo $row['imgpath']; ?>" alt="<?php echo $row['alt']; ?>" width="250rem"><br><br>
                        
                        <!-- Input for new image -->
                        <label for="fileToUpload">New Image</label><br>
                        <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
                        
                        <!-- Alt Text input -->
                        <label for="edit_alt">Alt Text</label>
                        <input type="text" class="form-control" id="edit_alt" name="edit_alt" value="<?php echo $row['alt']; ?>" required>
               </div>
                    <input type="hidden" name="slider_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="btn_edit_slider" class="btn btn-primary">Update</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

   <?php foreach ($rows as $row) : ?>
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <h5 class="modal-title">Delete Slider</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this slider?</p>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="slider_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="btn_delete_slider" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

    <!-- Add Slider Modal -->
<div class="modal fade" id="addSliderModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: maroon; color: white;">
                <h5 class="modal-title">Add Slider</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fileToUpload">Image</label><br>
                        <img id="imagePreview" src="#" alt="Selected Image" style="max-width: 100%; display: none;"><br><br>

                        <input type="file" name="fileToUpload" id="fileToUpload" required onchange="previewImage(event)">

                    </div>
                    <div class="form-group">
                        <label for="add_alt">Alt Text</label>
                        <input type="text" class="form-control" id="add_alt" name="add_alt" required>
                    </div>
                    <button type="submit" name="btn_add_slider" class="btn btn-primary">Add Slider</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
    
<!-- Success Modal for Edit -->
<div class="modal fade" id="editSuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                 Slider record updated successfully!
            </div>
        </div>
    </div>
</div>
    
   <!-- Error Modal for Edit -->
<div class="modal fade" id="editErrorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                   echo " Image path or alt text existed"
                ?>
               <!-- Error updating Genre record: <?php echo $stmt_update->error; ?>  -->
            </div>
        </div>
    </div>
</div>
    
   
    <!-- Success Modal for Delete -->
<div class="modal fade" id="deleteSuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              Slider record deleted successfully!
            </div>
        </div>
    </div>
</div>
    
     <!-- Error Modal for Delete -->
<div class="modal fade" id="deleteErrorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Error deleting slider record
            </div>
        </div>
    </div>
</div>
     
      <!-- Success Modal for Add Genre -->
<div class="modal fade" id="addSliderSuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                 Slider added successfully!
            </div>
        </div>
    </div>
</div>

<!-- Error Modal for Add Genre -->
<div class="modal fade" id="addSliderErrorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Slider already exists. Please choose a different Slider.
            </div>
        </div>
    </div>
</div>
</body>
<script>
// JavaScript to handle image preview for the file input
document.querySelectorAll('#fileToUpload').forEach(input => {
    input.addEventListener('change', function() {
        const imgPreview = this.parentNode.querySelector('img');
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            imgPreview.src = "../<?php echo $row['imgpath']; ?>"; // Restore original image if no file selected
        }
    });
});
</script>

<script>
function previewImage(event) {
    var input = event.target;
    var imagePreview = document.getElementById("imagePreview");
    
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = "block"; // Show the preview image
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        imagePreview.style.display = "none"; // Hide the preview if no file selected
    }
}
</script>
    <?php
     include("admin_footer.php");
     
     ?>