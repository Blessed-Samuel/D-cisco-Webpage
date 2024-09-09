<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer library files
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load database configuration
$config = include '../sett/config.php';

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
        // Send email with form data
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ciscotraining9@gmail.com'; // Replace with your Gmail address
            $mail->Password = 'gvkyvqvbsjukzgmv'; // Replace with your Gmail password or app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('ciscotraining9@gmail.com', 'dcisco'); // Replace with your name and email
            $mail->addAddress('ciscotraining9@gmail.com', 'dcisco'); // Replace with your receiving email address

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Enrollment Form Submission';
            $mail->Body = "<h1>New Enrollment Form Submission</h1>
                           <p><strong>Full Name:</strong> {$full_name}</p>
                           <p><strong>Email:</strong> {$email}</p>
                           <p><strong>Phone:</strong> {$phone}</p>
                           <p><strong>Gender:</strong> {$gender}</p>
                           <p><strong>Preferred Courses:</strong> {$preferredCoursesString}</p>
                           <p><strong>Preferred Mode:</strong> {$preferredMode}</p>";

            $mail->send();
            header("Location: congrats.html");
            exit(); // Make sure to exit after redirection to prevent further execution
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            echo "There was an issue with your submission. Please try again later.";
        }
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
