<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $full_name = $_POST["fullName"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $gender = $_POST["gender"];
    $preferredCourses = $_POST["preferredCourse"];
    $preferredMode = $_POST["preferredMode"];

    // Convert the array of preferred courses to a comma-separated string
    $preferredCoursesString = implode(", ", $preferredCourses);

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO enroll_form (full_name, email, phone, gender, preferredCourse, preferredMode) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $full_name, $email, $phone, $gender, $preferredCoursesString, $preferredMode);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: congrats.html");
        exit(); // Make sure to exit after redirection to prevent further execution
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
