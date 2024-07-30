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
    
    $sql= "SELECT seat_detail.id, customer.username, seat_detail.seat_no, cinema.show.id AS 'show_id', movie.name
        FROM seat_detail, customer, cinema.show join movie where seat_detail.cust_id = customer.id AND seat_detail.show_id = cinema.show.id AND movie.id=cinema.show.movie_id;";
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
                            <h5 class="text-center mt-2" style="color:maroon;">Seat Details</h5>
                            <a href="addseat_details.php">Book Seat</a>
                            
                            <table class="table mt-5" border="1">
                                <thead style="background-color:maroon;color:white;">
                                    <tr>
                                        <th>Id</th>
                                        <th>Customer Name</th>
                                        <th>Seat No.</th>
                                         <th>Movie Name</th>
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
                                        <td><?php echo $row["seat_no"]?></td>
                                        <td><?php echo $row["name"] ?></td>
<!--                                        <td><?php echo $row["genre_name"] ?></td>
                                        <td><?php echo $row["lang_name"] ?></td>
                                        <td><?php echo $row["duration"] ?></td>-->
                                        <td><a href="editseat_detail.php?id=<?php echo $row["id"]; ?>" class="btn btn-primary">Edit</a> |
                                            <a href="deleteseat_detail.php?id=<?php echo $row["id"]; ?>" class="btn btn-danger">Delete</a>
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
