<?php
session_start();

if(empty($_SESSION["admin_username"]))
{
    header("Location:index.php");
}

else
{
  
    include_once("admin_header.php");
   ?>
       
            
            <section>
                <div class="continer">
                    <div class="row">
                        <div class="col-md-2" style="background-color:maroon;">
                            <?php include('admin_sidenavbar.php'); ?>
                        </div>
                        <div class="col-md-10">
                            <h5 class="text-center mt-2" style="color:maroon;">Admin Dashboard</h5>
                        </div>
                    </div>
                </div>
            </section>
                    
    <?php
     include_once("admin_footer.php");
}
?>
