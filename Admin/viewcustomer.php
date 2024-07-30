<style>
    /* Add styles for the table container */
    .table-container {
        overflow-x: auto; /* Enable horizontal scrolling */
        max-width: 100%; /* Ensure it doesn't extend beyond the viewport width */
    }

    /* Default styles for larger screens */
    .table th,
    .table td {
        font-size: 16px;
        padding: 10px;
    }

    /* Media query for smaller screens */
    @media (max-width: 768px) {
        .table th,
        .table td {
            font-size: 14px;
            padding: 8px;
        }
    }
</style>





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

if(isset($_POST["btn_delete"]))
{
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    $customerId = $_POST["customer_id"]; // Retrieve the industry ID from the form

    // Rest of your code for database connection

    $tbl = "customer";
    $sql_delete = "DELETE FROM $tbl WHERE id=?";
      
    // Create a prepared statement
    $stmt_delete = $conn->prepare($sql_delete);
        
    // Bind parameter
    $stmt_delete->bind_param('i', $customerId);
        
    
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
}
   ?>
  
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-2" style="background-color:maroon;">
                            <?php include('admin_sidenavbar.php'); ?>
                        </div>
                        <div class="col-md-10">
                            <h5 class="text-center mt-2" style="color:maroon;">Customer Details</h5>
<!--                            <a href="addcustomer.php">Add Customer</a>-->
                            <div class="table-container">
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

                                    $query = "SELECT id,username, email,hpnum,gender FROM customer LIMIT ?,?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param('ii', $offset, $records_per_page);

                                    // Execute the statement
                                    $stmt->execute();
                                    
                                    // Bind result variables
                                    $stmt->bind_result($id, $name,$email,$hp,$gender);
                                    
                                    $rows = [];
                                    while ($stmt->fetch()) {
                                    $rows[] = ["id" => $id, "username" => $name, "email" =>$email, "hpnum" =>$hp ,"gender" => $gender];
                                        }
                                        // Count total records
                                    $total_records = mysqli_query($conn, "SELECT COUNT(*) FROM customer")->fetch_row()[0];

                                    // Calculate total pages
                                    $total_pages = ceil($total_records / $records_per_page);
                                        
                                    ?>
                                    <tr>
                                        <th>Id</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Gender</th>
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
                                echo "<td>{$row['username']}</td>";
                                echo "<td>{$row['email']}</td>";
                                echo "<td>{$row['hpnum']}</td>";
                                echo "<td>{$row['gender']}</td>";

                                echo "<td>";
//                                echo "<button class='btn btn-primary' data-toggle='modal' data-target='#editModal_{$row['id']}'>Edit</button> | ";
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
                            </div>
                             <div class="pagination  d-flex justify-content-center col-md-12"">
            <?php
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<span class="page-number"><a href="?page=' . $i . ' ">' . $i . '</a> ';
            }
            ?>
            
        </div>
                            
                            
                        </div>
                    </div>
                </div>
                
            </section>



<?php
// Generate modal for each industry
//$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
//
//$query = "SELECT id, industry_name FROM industry";
//$stmt = $conn->prepare($query);
//$stmt->execute();
//$stmt->bind_result($id, $name);
//while ($stmt->fetch()) {
    ?>
    <!-- Edit Modal for Industry ID: ////<?php echo $id; ?> -->
<!--    <div class="modal fade" id="editModal_////<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:maroon;color:white;">
                    <h5 class="modal-title">Edit Industry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>-->
<!--                <div class="modal-body">
                    <div class="container" style="color:maroon;">
                        <form method="post">
                            <label for="email"><b>Industry Name</b></label>
                            <input type="text" style="border-radius:30px;" placeholder="Enter Industry Name" name="industry_name_txt" value="////<?php echo $name; ?>" required>
                            <input type="hidden" name="industry_id" value="////<?php echo $id; ?>">
                            <button type="submit" name="btn_edit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>-->
<?php
//}
?>




<?php
// Generate delete for each customer
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

$query = "SELECT id, username, email, hpnum, gender FROM customer";
$stmt = $conn->prepare($query);
$stmt->execute();
$stmt->bind_result($id, $name, $email,$hp,$gender);
while ($stmt->fetch()) {
    ?>
    <!-- Delete Modal for Industry ID: <?php echo $id; ?> -->
    <div class="modal fade" id="deleteModal_<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:maroon;color:white;">
                    <h5 class="modal-title">Delete Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this customer details?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form method="post">
                        <input type="hidden" name="customer_id" value="<?php echo $id; ?>">
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
                Customer record deleted successfully!
            </div>
        </div>
    </div>
</div>

<!-- Success Modal for Edit -->
<!--<div class="modal fade" id="editSuccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
</div>-->

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
                Error deleting customer record: <?php echo $stmt_delete->error; ?>
            </div>
        </div>
    </div>
</div>

   
   <!-- Error Modal for Edit -->
<!--<div class="modal fade" id="editErrorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Error updating industry record: <?php echo $stmt_update->error; ?>
            </div>
        </div>
    </div>
</div>-->
    
    
  
 
</body>

    <?php
     include("admin_footer.php");

?>