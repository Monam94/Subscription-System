<?php
// Database connection (replace with your own credentials)
$servername = "localhost";
$username = "root";  // Replace with your database username
$password = "";  // Replace with your database password
$dbname = "users_db";  // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if name is provided
if (isset($_POST['name'])) {
    $name = $conn->real_escape_string($_POST['name']);
    
    // Prepare the delete statement
    $sql = "DELETE FROM subscribers WHERE name='$name'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Subscriber deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting subscriber: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No name provided."]);
}

$conn->close();
?>
