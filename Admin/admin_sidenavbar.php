<style>
    /* CSS to style the sidenav and its container */
    .sidenav {
        height: 100vh; /* Set sidenav height to 100% of viewport height */
        background-color: maroon;
    }

    .sidenav-container {
        height: 100vh;  /*Set container height to 100% of viewport height */
/*        overflow-y: auto;  Add vertical scrollbar if content overflows */
    }

/*     Style for sidenav links (adjust as needed) 
    .sidenav a {
        padding: 15px;
        text-decoration: none;
        font-size: 18px;
        color: white;
        display: block;
    }*/

    /* Style for sidenav links on hover (adjust as needed) */
/*    .sidenav a:hover {
        background-color: #ff6f61;  Change background color on hover 
        color: white;
    }*/

/* Media query for screens with a maximum width of 768px (adjust as needed) */
    @media (max-width: 768px) {
         /*Reduce the height of the sidenav for smaller screens*/ 
         .sidenav-container {
        height: 40vh;  /*Set container height to 100% of viewport height */
/*        overflow-y: auto;  Add vertical scrollbar if content overflows */
    }
    }
</style>

<div class="col-md-2 sidenav-container">

<ul class="navbar-nav mr-auto mt-2 mt-lg-0 ml-5" >
<!--    <li class="nav-item">
        <a class="nav-link" href="#" style="color:White;"> Movie Booking</a>
    </li>         -->
<!--    <li class="nav-item">
        <a class="nav-link" href="viewcinema.php" style="color:White;"> Cinema</a>
    </li>-->
    <li class="nav-item">
        <a class="nav-link" href="viewcontact.php" style="color:White;"> Contact</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="viewcustomer.php" style="color:White;">Customer</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="viewgenre.php" style="color:White;"> Movie Genre</a>
    </li>
<!--    <li class="nav-item">
        <a class="nav-link" href="viewindustry.php" style="color:White;"> Movie Industry</a>
    </li>-->
    <li class="nav-item">
        <a class="nav-link" href="viewlanguage.php" style="color:White;"> Movie Language</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="viewmovie.php" style="color:White;"> Movie</a>
    </li>
<!--    <li class="nav-item">
        <a class="nav-link" href="viewseat_details.php" style="color:White;"> Seat Details</a>
    </li>-->
<!--    <li class="nav-item">
        <a class="nav-link" href="viewseat_reserved.php" style="color:White;"> Seat Reserved</a>
    </li>-->
<!--    <li class="nav-item">
        <a class="nav-link" href="#" style="color:White;"> Movie Show</a>
    </li>-->
<!--    <li class="nav-item">
        <a class="nav-link" href="#" style="color:White;"> Movie Show Time</a>
    </li>-->
    <li class="nav-item">
        <a class="nav-link" href="viewslider.php" style="color:White;"> Slider</a>
    </li>
</ul>
    </div>
