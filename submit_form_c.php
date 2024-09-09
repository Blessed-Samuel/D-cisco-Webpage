<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load database configuration
$config = include 'config.php';

// Create connection
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

// Check connection
if ($conn->connect_error) {
    // Log the error to a file rather than displaying it
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $full_name = filter_var($_POST["fullName"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $gender = filter_var($_POST["gender"], FILTER_SANITIZE_STRING);
    $preferredCourses = $_POST["preferredCourse"];
    $preferredMode = filter_var($_POST["preferredMode"], FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Convert the array of preferred courses to a comma-separated string
    $preferredCoursesString = implode(", ", array_map('filter_var', $preferredCourses, array_fill(0, count($preferredCourses), FILTER_SANITIZE_STRING)));

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO enroll_form (full_name, email, phone, gender, preferredCourse, preferredMode) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $full_name, $email, $phone, $gender, $preferredCoursesString, $preferredMode);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: congrats.html");
        exit(); // Make sure to exit after redirection to prevent further execution
    } else {
        // Log the error to a file rather than displaying it
        error_log("Error: " . $stmt->error);
        echo "There was an issue with your submission. Please try again later.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
