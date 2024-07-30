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

    $industryId = $_POST["industry_id"];
    $newIndustryName = $_POST["industry_name_txt"];

    $tbl = "industry";
    
    $stmt_check = $conn->prepare("SELECT id FROM $tbl WHERE industry_name = ?");
    $stmt_check->bind_param('s', $newIndustryName);
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
        $tbl = "industry";
        $sql_update = "UPDATE $tbl SET industry_name = ? WHERE id = ?";
      
        // Create a prepared statement
        $stmt_update = $conn->prepare($sql_update);
        
        // Bind parameters
        $stmt_update->bind_param('si', $newIndustryName, $industryId);
        
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

    $industryId = $_POST["industry_id"]; // Retrieve the industry ID from the form

    // Rest of your code for database connection

    $tbl = "industry";
    $sql_delete = "DELETE FROM $tbl WHERE id=?";
      
    // Create a prepared statement
    $stmt_delete = $conn->prepare($sql_delete);
        
    // Bind parameter
    $stmt_delete->bind_param('i', $industryId);
        
    
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

    $newIndustryName = $_POST["industry_name_txt"];
    $tbl = "industry";

    // Check if the new genre name already exists in the database
    $stmt_check = $conn->prepare("SELECT id FROM $tbl WHERE industry_name = ?");
    $stmt_check->bind_param('s', $newIndustryName);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // The genre name already exists, show an error
        echo '<script>
            $(document).ready(function() {
                $("#addGenreErrorModal").modal("show");
            });
        </script>';
    } else {
        // Genre name doesn't exist, proceed with insertion
        $sql_insert = "INSERT INTO $tbl (industry_name) VALUES (?)";

        // Create a prepared statement
        $stmt_insert = $conn->prepare($sql_insert);

        // Bind parameter
        $stmt_insert->bind_param('s', $newIndustryName);

        // Execute the prepared statement
        if ($stmt_insert->execute()) {
            echo '<script>
                $(document).ready(function() {
                    $("#addIndustrysSuccessModal").modal("show");
                });
            </script>';
        } else {
            echo '<script>
                $(document).ready(function() {
                    $("#addIndustryErrorModal").modal("show");
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
                            <h5 class="text-center mt-2" style="color:maroon;">Industry Details</h5>
                            <!--<a href="addindustry.php">Add Industry</a>-->
                            
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

                                    $query = "SELECT id,industry_name FROM industry LIMIT ?,?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param('ii', $offset, $records_per_page);
                                    // Execute the statement
                                    $stmt->execute();
                                    
                                    // Bind result variables
                                    $stmt->bind_result($id, $name);
                                    
                                    $rows = [];
                                    while ($stmt->fetch()) {
                                    $rows[] = ["id" => $id, "industry_name" => $name];
                                        }
                                         // Count total records
                                    $total_records = mysqli_query($conn, "SELECT COUNT(*) FROM industry")->fetch_row()[0];

                                    // Calculate total pages
                                    $total_pages = ceil($total_records / $records_per_page);
                                    ?>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                         <th>Action</th>
                                    </tr>
                                    
                                </thead>
                           <tbody>
                                <?php
                            if (empty($rows)) {
                                echo '<tr><td colspan="6">No records found.</td></tr>';
                                }
                                else{
                               foreach ($rows as $row) {
                                echo "<tr>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td>{$row['industry_name']}</td>";
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
                <button class="btn btn-success" data-toggle="modal" data-target="#addIndustryModal">Add Industry</button>
            </div>
                            
                        </div>
                    </div>
                </div>
                
            </section>



<?php
// Generate modal for each industry
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

$query = "SELECT id, industry_name FROM industry";
$stmt = $conn->prepare($query);
$stmt->execute();
$stmt->bind_result($id, $name);
while ($stmt->fetch()) {
    ?>
    <!-- Edit Modal for Industry ID: <?php echo $id; ?> -->
    <div class="modal fade" id="editModal_<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:maroon;color:white;">
                    <h5 class="modal-title">Edit Industry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container" style="color:maroon;">
                        <form method="post">
                            <label for="email"><b>Industry Name</b></label>
                            <input type="text" style="border-radius:30px;" placeholder="Enter Industry Name" name="industry_name_txt" value="<?php echo $name; ?>" required>
                            <input type="hidden" name="industry_id" value="<?php echo $id; ?>">
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

$query = "SELECT id, industry_name FROM industry";
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
                    <h5 class="modal-title">Delete Industry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this industry?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form method="post">
                        <input type="hidden" name="industry_id" value="<?php echo $id; ?>">
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
                Industry record deleted successfully!
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
                Industry record updated successfully!
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
                Error deleting industry record: <?php echo $stmt_delete->error; ?>
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
                   echo "Industry existed"
                ?>
              <!--   Error updating industry record: <?php echo $stmt_update->error; ?> -->
            </div>
        </div>
    </div>
</div>
    
       <!-- Add Genre Modal -->
<div class="modal fade" id="addIndustryModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:maroon;color:white;">
                <h5 class="modal-title">Add Industry</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container" style="color:maroon;">
                    <form method="post">
                        <label for="industry_name_txt"><b>Industry Name</b></label>
                        <input type="text" style="border-radius:30px;" placeholder="Enter Industry Name" name="industry_name_txt" required>
                        <button type="submit" name="btn_add" class="btn btn-primary">Add Industry</button>
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
<div class="modal fade" id="addIndustrySuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Industry added successfully!
            </div>
        </div>
    </div>
</div>

<!-- Error Modal for Add Genre -->
<div class="modal fade" id="addIndustryErrorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Industry name already exists. Please choose a different industry name.
            </div>
        </div>
    </div>
</div>
  
 
</body>

    <?php
     include("admin_footer.php");

?>