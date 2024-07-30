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

}
   ?>
  
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-2" style="background-color:maroon;">
                            <?php include('admin_sidenavbar.php'); ?>
                        </div>
                        <div class="col-md-10">
                            <h5 class="text-center mt-2" style="color:maroon;">Contact Details</h5>
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

                                    $query = "SELECT id,name, email,hpnum,msg, msg_date FROM contact LIMIT ?,?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param('ii', $offset, $records_per_page);

                                    // Execute the statement
                                    $stmt->execute();
                                    
                                    // Bind result variables
                                    $stmt->bind_result($id, $name,$email,$hp,$msg,$date);
                                    
                                    $rows = [];
                                    while ($stmt->fetch()) {
                                    $rows[] = ["id" => $id, "name" => $name, "email" =>$email, "hpnum" =>$hp ,"msg" => $msg,"msg_date" => $msg];
                                        }
                                        
                                        // Count total records
                                    $total_records = mysqli_query($conn, "SELECT COUNT(*) FROM contact")->fetch_row()[0];

                                    // Calculate total pages
                                    $total_pages = ceil($total_records / $records_per_page);
                                    ?>
                                    <tr>
                                        <th>Id</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Message</th>
                                         <th>Message Date</th>
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
                                echo "<td>{$row['name']}</td>";
                                echo "<td>{$row['email']}</td>";
                                echo "<td>{$row['hpnum']}</td>";
                                echo "<td>{$row['msg']}</td>";
                                echo "<td>{$row['msg_date']}</td>";

//                                echo "<td>";
//                                echo "<button class='btn btn-primary' data-toggle='modal' data-target='#editModal_{$row['id']}'>Edit</button> | ";
//                                echo "<button class='btn btn-danger' data-toggle='modal' data-target='#deleteModal_{$row['id']}'>Delete</button>";
//                                echo "</td>";
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
                
            </section>


 
</body>

    <?php
     include("admin_footer.php");

?>