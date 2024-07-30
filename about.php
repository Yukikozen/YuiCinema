<!DOCTYPE html>
<html>
    <head>
        <title>About Us</title>
        <style>
            .view-all-link {
                text-align: right; /* Align the "View All Movies" link to the right */
                margin-top: -20px;
                margin-right: 20px; /* Increase the right margin to push it further right */
            }

            .all-movies-container {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                flex-wrap: wrap;
                margin-left: -10px; /* Add negative margin to shift the content to the left */
            }

            .jumbotron {
                flex-basis: 48%;
                margin-bottom: 0;
                margin-left: 10px;
                padding-right: 20px; /* Add some padding to the right to balance the layout */
            }
            /* Default styles for carousel images */
            .carousel-inner img {
                width: 100%; /* Set your desired width */
                height: auto; /* Allow the image to scale naturally */
            }



            .movie-banner {
                width: 10rem; /* Set a fixed width for movie banners */
                height: 15rem; /* Allow the height to adjust proportionally */
                margin-bottom: 1rem;
                /*margin-right: 10rem;  Add right margin to create spacing between movie banners */
            }


            /* Movie card styles */
            .movie-card {
                border: 1px solid #ddd;
                border-radius: 5px;
                margin: 10px;
                padding: 15px;
                text-align: center;
                background-color: #f9f9f9;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s;
            }

            .movie-card:hover {
                transform: scale(1.05);
            }

            .movie-image {
                max-width: 100%; /* Ensure the image is responsive */
                height: auto; /* Allow the image to scale naturally */
                margin-bottom: 10px;
            }

            .movie-title {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 10px;
            }



            @media screen and (max-width: 768px) {
                /* Styles for smaller screens */
                .carousel-inner {
                    max-width: 40rem;
                    max-height: 50rem;
                    margin: 0 auto;
                }

                .carousel-inner img {
                    width: 30%;
                    height: 40%;
                    object-fit: cover;
                }

                .movie-card {
                    flex: 0 0 calc(50% - 20px); /* Adjust the width to have two cards in a row */
                    min-width: calc(50% - 20px); /* Set a minimum width for movie cards in smaller screens */
                    margin: 10px; /* Add margin to separate cards */
                    text-align: center; /* Center-align the content */
                    width:15rem;
                    margin-left:5rem;
                }


            }

            @media (max-width: 576px) {
                .carousel-inner {
                    max-width: 70rem; /* Set your desired max-width */
                    max-height: 40rem; /* Set your desired max-height */
                    margin: 0 auto; /* Center-align the container */
                }


            }


        </style>
    </head>
    <body>
        <?php
        session_start();
        include_once"header.php";
        include_once 'conn/config.php';
        ?>


        <div class="container">
            <h1>About Us</h1>
            <p>Welcome to our platform where you can watch a wide variety of online videos, including movies, TV shows, documentaries, and more. Our goal is to provide you with an entertaining and convenient way to enjoy your favorite content from the comfort of your own home.</p>

            <h2>Our Mission</h2>
            <p>Our mission is to create a user-friendly platform that offers a vast collection of videos spanning different genres and interests. Whether you're a fan of action, romance, comedy, or educational content, we have something for everyone.</p>

            <h2>Features</h2>
            <ul>
                <li>Access to a diverse library of videos.</li>
                <li>High-quality streaming for an immersive experience.</li>
                <li>User-friendly interface for easy navigation.</li>
                <li>Customizable watchlists to keep track of your favorite content.</li>
                <li>Regularly updated content to keep you entertained.</li>
            </ul>

            <h2>Contact Us</h2>
            <p>If you have any questions, feedback, or concerns, feel free to get in touch with our support team. We're here to assist you and enhance your viewing experience.</p>
            <p>Email: support@watchonlinevideos.com</p>

            <h2>Stay Connected</h2>
            <p>Follow us on social media to stay updated on the latest releases, promotions, and news:</p>
            <ul>
                <li><a href="https://www.facebook.com/watchonlinevideos">Facebook</a></li>
                <li><a href="https://www.twitter.com/watchonlinevideos">Twitter</a></li>
                <li><a href="https://www.instagram.com/watchonlinevideos">Instagram</a></li>
            </ul>
        </div>

        <!-- Include your footer here -->
        <?php include_once "footer.php"; ?>
    </body>
</html>
