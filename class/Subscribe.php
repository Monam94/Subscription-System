<?php
class Subscribe {
    private $conn;
    private $table_name = "subscribers"; // The table for storing subscribers

    // Properties for subscriber data
    public $name;
    public $email;

    // Constructor receives database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to get a subscriber by email
    public function getSubscriber() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        
        // Prepare the query
        $stmt = $this->conn->prepare($query);
        
        // Sanitize the email
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        // Bind the email parameter
        $stmt->bindParam(':email', $this->email);
        
        // Execute the query
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return the subscriber data if found
    }

    // Method to insert a new subscriber
    public function insert() {
        // First, check if the subscriber already exists
        if ($this->getSubscriber()) {
            return false; // Email already exists, return false
        }

        $query = "INSERT INTO " . $this->table_name . " (name, email, modified) VALUES (:name, :email, NOW())"; // Set modified to current timestamp
        
        // Prepare the query
        $stmt = $this->conn->prepare($query);
        
        // Sanitize and bind the form inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        // Bind parameters
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        
        // Execute the query
        if ($stmt->execute()) {
            return true; // Return true if the insert is successful
        }

        return false; // Return false if there was an error
    }

    // Method to get all subscribers
    public function getAllSubscribers() {
        $query = "SELECT name, email, modified FROM " . $this->table_name; // Added modified to query
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all records as associative array
    }
}
?>
