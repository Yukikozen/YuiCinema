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

 
if (isset($_POST["btn_edit"])) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    $cinemaId = $_POST["cinema_id"];
    $newCinemaName = $_POST["cinema_name_txt"];
    $newLocation = $_POST["location_name_txt"];
    $newCity = $_POST["city_name_txt"];
     $tbl = "cinema";
     // Check if the new genre name already exists in the database
    $stmt_check = $conn->prepare("SELECT id FROM $tbl WHERE name = ?");
    $stmt_check->bind_param('s', $newCinemaName);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // The genre name already exists, show an error
        echo '<script>
            $(document).ready(function() {
                $("#editErrorModal").modal("show");
            });
        </script>';
    }
   
    else {
        $tbl = "cinema";
        $sql_update = "UPDATE $tbl SET name = ? , location =? , city =? WHERE id = ?";
      
        // Create a prepared statement
        $stmt_update = $conn->prepare($sql_update);
        
        // Bind parameters
        $stmt_update->bind_param('sssi', $newCinemaName, $newLocation, $newCity, $cinemaId);
        
        // Execute the prepared statement
        if ($stmt_update->execute()) {
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

        // Close the prepared statement
        $stmt_update->close();
    }

    // Close the check statement
    $stmt_check->close();
}

if(isset($_POST["btn_delete"]))
{
      $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    $cinemaId = $_POST["cinema_id"]; // Retrieve the industry ID from the form

    // Rest of your code for database connection

    $tbl = "cinema";
    $sql_delete = "DELETE FROM $tbl WHERE id=?";
      
    // Create a prepared statement
    $stmt_delete = $conn->prepare($sql_delete);
        
    // Bind parameter
    $stmt_delete->bind_param('i', $cinemaId);
        
    
    // Execute the prepared statement
    
if ($stmt_delete->execute()) {
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

      
    // Close the prepared statement
    $stmt_delete->close();
}


if (isset($_POST["btn_add"])) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    $newCinemaName = $_POST["cinema_name_txt"];
    $newLocation = $_POST["location_name_txt"];
    $newCity = $_POST["city_name_txt"];
    $tbl = "cinema";

    // Check if the new genre name already exists in the database
    $stmt_check = $conn->prepare("SELECT id FROM $tbl WHERE name = ?");
    $stmt_check->bind_param('s', $newCinemaName);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // The genre name already exists, show an error
        echo '<script>
            $(document).ready(function() {
                $("#addCinemaErrorModal").modal("show");
            });
        </script>';
    } else {
        // Genre name doesn't exist, proceed with insertion
        $sql_insert = "INSERT INTO $tbl (name,location, city) VALUES (?,?,?)";

        // Create a prepared statement
        $stmt_insert = $conn->prepare($sql_insert);

        // Bind parameter
        $stmt_insert->bind_param('sss', $newCinemaName,$newLocation,$newCity);

        // Execute the prepared statement
        if ($stmt_insert->execute()) {
            echo '<script>
                $(document).ready(function() {
                    $("#addCinemaSuccessModal").modal("show");
                });
            </script>';
        } else {
            echo '<script>
                $(document).ready(function() {
                    $("#addCinemaErrorModal").modal("show");
                });
            </script>';
        }

        // Close the prepared statement
        $stmt_insert->close();
    }

    // Close the check statement
    $stmt_check->close();

    // Close the database connection
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
                            <h5 class="text-center mt-2" style="color:maroon;">Cinema Details</h5>
                            <!--<a href="addcinema.php">Add Cinema</a>-->
                            
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

                                    $query = "SELECT id,name, location, city FROM cinema LIMIT ?,?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param('ii', $offset, $records_per_page);


                                    // Execute the statement
                                    $stmt->execute();
                                    
                                    // Bind result variables
                                    $stmt->bind_result($id, $name,$location,$city);
                                    
                                    $rows = [];
                                    while ($stmt->fetch()) {
                                    $rows[] = ["id" => $id, "name" => $name, "location" => $location, "city" =>$city];
                                        }
                                        
                                        // Count total records
                                    $total_records = mysqli_query($conn, "SELECT COUNT(*) FROM cinema")->fetch_row()[0];

                                    // Calculate total pages
                                    $total_pages = ceil($total_records / $records_per_page);
                                    ?>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>City</th>
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
                                echo "<td>{$row['name']}</td>";
                                echo "<td>{$row['location']}</td>";
                                echo "<td>{$row['city']}</td>";
                                echo "<td>";
                                echo "<button class='btn btn-primary' data-toggle='modal' data-target='#editModal_{$row['id']}'>Edit</button> | ";
                                echo "<button class='btn btn-danger' data-toggle='modal' data-target='#deleteModal_{$row['id']}'>Delete</button>";
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
                <button class="btn btn-success" data-toggle="modal" data-target="#addCinemaModal">Add Cinema</button>
           </div>
                            
                            
                        </div>
                    </div>
                </div>
                
            </section>



<?php
// Generate modal for each industry
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

$query = "SELECT id, name, location, city FROM cinema";
$stmt = $conn->prepare($query);
$stmt->execute();
$stmt->bind_result($id, $name,$location, $city);
while ($stmt->fetch()) {
    ?>
    <!-- Edit Modal for Industry ID: <?php echo $id; ?> -->
    <div class="modal fade" id="editModal_<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:maroon;color:white;">
                    <h5 class="modal-title">Edit Cinema</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container" style="color:maroon;">
                        <form method="post">
                            <label for="email"><b>Cinema Name</b></label>
                            <input type="text" style="border-radius:30px;" placeholder="Enter Cinema Name" name="cinema_name_txt" value="<?php echo $name; ?>" required>
                            <label for="email"><b>Location</b></label>
                            <input type="text" style="border-radius:30px;" placeholder="Enter Location Name" name="location_name_txt" value="<?php echo $location; ?>" required>
                            <label for="email"><b>City</b></label>
                            <input type="text" style="border-radius:30px;" placeholder="Enter City Name" name="city_name_txt" value="<?php echo $city; ?>" required>
                            <input type="hidden" name="cinema_id" value="<?php echo $id; ?>">
                            <button type="submit" name="btn_edit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>




<?php
// Generate modal for each industry
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

$query = "SELECT id, name FROM cinema";
$stmt = $conn->prepare($query);
$stmt->execute();
$stmt->bind_result($id, $name);
while ($stmt->fetch()) {
    ?>
    <!-- Delete Modal for Industry ID: <?php echo $id; ?> -->
    <div class="modal fade" id="deleteModal_<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:maroon;color:white;">
                    <h5 class="modal-title">Delete Cinema</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Cinema?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form method="post">
                        <input type="hidden" name="cinema_id" value="<?php echo $id; ?>">
                        <button type="submit" name="btn_delete" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
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
                Cinema record deleted successfully!
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
                Cinema record updated successfully!
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
                Error deleting Cinema record: <?php echo $stmt_delete->error; ?>
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
                   echo "Cinema existed"
                ?>
               <!-- Error updating Genre record: <?php echo $stmt_update->error; ?>  -->
            </div>
        </div>
    </div>
</div>
   
   
       <!-- Add Cinema Modal -->
<div class="modal fade" id="addCinemaModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:maroon;color:white;">
                <h5 class="modal-title">Add Cinema</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container" style="color:maroon;">
                    <form method="post">
                      
                            <label for="cinema_name_txt"><b>Cinema Name</b></label>
                        <input type="text" style="border-radius:30px;" placeholder="Enter Cinema Name" name="cinema_name_txt" required>
                            <label for="cinema_name_txt"><b>Location</b></label>
                        <input type="text" style="border-radius:30px;" placeholder="Enter Location Name" name="location_name_txt" required>
                            <label for="email"><b>Location</b></label>
                            <input type="text" style="border-radius:30px;" placeholder="Enter City Name" name="city_name_txt" required>
<!--                            <input type="hidden" name="cinema_id" value="<?php echo $id; ?>">-->
                        <button type="submit" name="btn_add" class="btn btn-primary">Add Cinema</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
  
 <!-- Success Modal for Add Genre -->
<div class="modal fade" id="addCinemaSuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Cinema added successfully!
            </div>
        </div>
    </div>
</div>

<!-- Error Modal for Add Genre -->
<div class="modal fade" id="addCinemaErrorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Cinema name already exists. Please choose a different Cinema name.
            </div>
        </div>
    </div>
</div>
    
    
  
 
</body>

    <?php
     include("admin_footer.php");

?>