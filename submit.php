<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters (better to use environment variables or a secure config file)
$servername = getenv('DB_SERVERNAME') ?: "localhost:3306";
$username = getenv('DB_USERNAME') ?: "dcissqey_dcisco";
$password = getenv('DB_PASSWORD') ?: "dcisco100@@";
$dbname = getenv('DB_NAME') ?: "dcissqey_contacts";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $full_name = $conn->real_escape_string(trim($_POST["fullName"]));
    $email = $conn->real_escape_string(trim($_POST["email"]));
    $phone = $conn->real_escape_string(trim($_POST["phone"]));
    $gender = $conn->real_escape_string(trim($_POST["gender"]));
    $preferredCourses = $_POST["preferredCourse"];
    $preferredMode = $conn->real_escape_string(trim($_POST["preferredMode"]));

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Check if preferredCourses is an array
    if (is_array($preferredCourses)) {
        // Convert the array of preferred courses to a comma-separated string
        $preferredCoursesString = implode(", ", array_map(function($course) use ($conn) {
            return $conn->real_escape_string($course);
        }, $preferredCourses));

        // Prepare and bind the INSERT statement
        $stmt = $conn->prepare("INSERT INTO enroll_form (full_name, email, phone, gender, preferred_course, preferred_mode) VALUES (?, ?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            die("Error preparing the statement: " . $conn->error);
        }

        $stmt->bind_param("ssssss", $full_name, $email, $phone, $gender, $preferredCoursesString, $preferredMode);

        if ($stmt->execute()) {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.yourmailserver.com'; // Replace with your SMTP server hostname
                $mail->SMTPAuth = true;
                $mail->Username = 'info@dciscoacademy.com'; // Replace with your email address
                $mail->Password = 'Password100@!'; // Replace with your email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('info@dciscoacademy.com', 'dcisco'); // Replace with your email address and name
                $mail->addAddress('info@dciscoacademy.com', 'dciscoacademy'); // Add a recipient

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'New Course Enrollment';
                $mail->Body = "<p>Name: $full_name</p>
                              <p>Email: $email</p>
                              <p>Phone: $phone</p>
                              <p>Gender: $gender</p>
                              <p>Preferred Courses: $preferredCoursesString</p>
                              <p>Preferred Mode: $preferredMode</p>";

                // Send the email
                $mail->send();
                header("Location: congrats.html");
                exit(); // Make sure to exit after redirection to prevent further execution
            } catch (Exception $e) {
                echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error: Preferred courses data is invalid.";
    }
}

// Close connection
$conn->close();
?>
