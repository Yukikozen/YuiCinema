<?php
session_start();
if(empty($_SESSION["admin_username"]))
{
    header("Location:index.php");
}

else
{
  
    include_once("admin_header.php");
    
    $con=new connec();
    
    $sql=" SELECT seat_reserved.id , seat_reserved.show_id, customer.username, seat_reserved.seat_number, seat_reserved.reserved from seat_reserved, customer where seat_reserved.cust_id = customer.id;";
        $result=$con->select_by_query($sql);
}
   ?>
       
            
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-2" style="background-color:maroon;">
                            <?php include('admin_sidenavbar.php'); ?>
                        </div>
                        <div class="col-md-10">
                            <h5 class="text-center mt-2" style="color:maroon;">Seat Reserved Details</h5>
                            <!--<a href="addseat_details.php">Book Seat</a>-->
                            
                            <table class="table mt-5" border="1">
                                <thead style="background-color:maroon;color:white;">
                                    <tr>
                                        <th>Id</th>
                                        <th>Customer Name</th>
                                        <th>Seat Number</th>
                                         <th>Status</th>
                                         <th>Action</th>
                                    </tr>
                                    
                                </thead>
                                <tbody>
                                    <?php
                                    if($result->num_rows>0)
                                    {
                                        while($row=$result->fetch_assoc())
                                        {
                                            ?>
                                    
                                     <tr>
                                         <!--<td><img src="../<?php echo $row["movie_banner"]?>" style="height:20rem;"></td>-->
                                        <td><?php echo $row["id"]?></td>
                                        <td><?php echo $row["username"]?></td>
                                        <td><?php echo $row["seat_number"]?></td>
                                        <td>
                                            <?php
                                            if($row["reserved"]==0)
                                            {
                                                   echo "<p style='color:blue;'>Already Booked</p>";
                                            }
                                            else
                                            {
                                                    echo "<p style='color:green;'>Available</p>";
                                            }
                                                ?>
                                        </td>
                 
                                        <td><a href="editseat_reserved.php?id=<?php echo $row["id"]; ?>" class="btn btn-primary">Edit</a> |
                                            <a href="deleteseat_reserved.php?id=<?php echo $row["id"]; ?>" class="btn btn-danger">Delete</a>
                                        </td>
                                        
                                    </tr>
                                    
                                    <?php
                                        }
                                    }
                                    ?>
                                   
                                </tbody>
                            </table>
                            
                            
                            
                        </div>
                    </div>
                </div>
            </section>
                    
    <?php
     include("admin_footer.php");

?>
