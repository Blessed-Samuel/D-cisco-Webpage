<?php
// Database connection parameters
$servername = "localhost:3306"; // or your database host name
$username = "dcissqey_dcisco"; // your database username
$password = "dcisco100@@"; // your database password
$dbname = "dcissqey_contacts"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $full_name = $_POST["full-name"];
    $email = $_POST["email"];
    $company = $_POST["company"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO contact_us (full_name, email, company, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $email, $company, $subject, $message);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: thank_you.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
