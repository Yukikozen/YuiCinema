<?php

// Database connection setup
include_once 'conn/config.php';
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

// Get the user's search query from the AJAX request
if (isset($_GET['search_query'])) {
    $searchQuery = $_GET['search_query'];

    // Prepare and execute the statement to search for movies by name
    $query = "SELECT * FROM movie WHERE name LIKE ?";
    $stmt = $conn->prepare($query);
    $searchQuery = '%' . $searchQuery . '%'; // Add wildcard % to search for partial matches
    $stmt->bind_param("s", $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Display the movie information as needed (e.g., name, description, etc.)
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            // Add more table columns as needed
            echo "</tr>";
        }
    } else {
        // No results found
        echo "<tr><td colspan='2'>No results found.</td></tr>";
    }
} else {
    // Handle case when search query is not set
    echo "<tr><td colspan='2'>Enter a search query.</td></tr>";
}

// Close the database connection
mysqli_close($conn);
?>
