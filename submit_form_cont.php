<?php
// Error reporting and display setup
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer library files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load database configuration
$config = include '../config/database.php';

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
    // Time-based verification
    $form_start_time = intval($_POST['form_start_time']);
    $current_time = time() * 1000; // Convert to milliseconds
    $time_taken = $current_time - $form_start_time;

    if ($time_taken < 5000) { // If the form is filled in less than 5 seconds
        die("Spam detected.");
    }

    // Retrieve and sanitize form data
    $full_name = filter_var($_POST["fullName"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $company = filter_var($_POST["company"], FILTER_SANITIZE_STRING);
    $subject = filter_var($_POST["subject"], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO contact_us (fullName, email, company, subject, message) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        die("There was an issue with your submission. Please try again later.");
    }
    $stmt->bind_param("sssss", $full_name, $email, $company, $subject, $message);

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
            $mail->Password = $config['apppassword']; // Replace with your Gmail password or app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('ciscotraining9@gmail.com', 'Your Name'); // Replace with your name and email
            $mail->addAddress('ciscotraining9@gmail.com', 'Your Name'); // Replace with your receiving email address

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Us Form Submission';
            $mail->Body = "<h1>New Contact Us Form Submission</h1>
                           <p><strong>Full Name:</strong> {$full_name}</p>
                           <p><strong>Email:</strong> {$email}</p>
                           <p><strong>Company:</strong> {$company}</p>
                           <p><strong>Subject:</strong> {$subject}</p>
                           <p><strong>Message:</strong> {$message}</p>";

            $mail->send();
            header("Location: thank_you.html");
            exit(); // Make sure to exit after redirection to prevent further execution
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            echo "There was an issue with your submission. Please try again later.";
        }
    } else {
        // Log the error to a file rather than displaying it
        error_log("Execute failed: " . $stmt->error);
        echo "There was an issue with your submission. Please try again later.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
<?php
// Error reporting and display setup
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer library files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

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
    // Time-based verification
    $form_start_time = intval($_POST['form_start_time']);
    $current_time = time() * 1000; // Convert to milliseconds
    $time_taken = $current_time - $form_start_time;

    if ($time_taken < 5000) { // If the form is filled in less than 5 seconds
        die("Spam detected.");
    }

    // Retrieve and sanitize form data
    $full_name = filter_var($_POST["full_name"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $company = filter_var($_POST["company"], FILTER_SANITIZE_STRING);
    $subject = filter_var($_POST["subject"], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO contact_us (full_name, email, company, subject, message) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        die("There was an issue with your submission. Please try again later.");
    }
    $stmt->bind_param("sssss", $full_name, $email, $company, $subject, $message);

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
            $mail->Password = $config['apppassword']; // Replace with your Gmail password or app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('ciscotraining9@gmail.com', 'Your Name'); // Replace with your name and email
            $mail->addAddress('ciscotraining9@gmail.com', 'Your Name'); // Replace with your receiving email address

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Us Form Submission';
            $mail->Body = "<h1>New Contact Us Form Submission</h1>
                           <p><strong>Full Name:</strong> {$full_name}</p>
                           <p><strong>Email:</strong> {$email}</p>
                           <p><strong>Company:</strong> {$company}</p>
                           <p><strong>Subject:</strong> {$subject}</p>
                           <p><strong>Message:</strong> {$message}</p>";

            $mail->send();
            header("Location: thank_you.html");
            exit(); // Make sure to exit after redirection to prevent further execution
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            echo "There was an issue with your submission. Please try again later.";
        }
    } else {
        // Log the error to a file rather than displaying it
        error_log("Execute failed: " . $stmt->error);
        echo "There was an issue with your submission. Please try again later.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
