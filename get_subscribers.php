<?php
header('Content-Type: application/json');
include_once 'config/Database.php';
include_once 'class/Subscribe.php';

$database = new Database();
$db = $database->getConnection();
$subscriber = new Subscribe($db);

// Fetch all subscribers
$subscribers = $subscriber->getAllSubscribers();

// Check if any subscribers were found
if ($subscribers) {
    // Create an array to hold the subscriber data
    $result = [];
    foreach ($subscribers as $sub) {
        $result[] = [
            'name' => $sub['name'],
            'email' => $sub['email'],
            'modified' => $sub['modified'] // Include the modified date
        ];
    }
    // Return as JSON
    echo json_encode($result);
} else {
    // Return an empty array if no subscribers found
    echo json_encode([]);
}

// Close the database connection (optional, but a good practice)
$db = null;
?>
