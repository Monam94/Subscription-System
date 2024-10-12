<?php
// Include Composer's autoloader for PHPMailer
require 'vendor/autoload.php';

// Include database connection and subscription class
include_once 'config/Database.php';
include_once 'class/Subscribe.php';

// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to format email content (HTML and plain text)
function format_email($info, $format) {
    if ($format === 'html') {
        return "
            <h1>Welcome, {$info['username']}!</h1>
            <p>Thank you for subscribing! We appreciate your subscription.</p>
        ";
    } else {
        return "
            Welcome, {$info['username']}!
            Thank you for subscribing! We appreciate your subscription.
        ";
    }
}

// Function to send the email using PHPMailer
function send_email($info) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';                 // Specify main SMTP server
        $mail->SMTPAuth   = true;                             // Enable SMTP authentication
        $mail->Username   = 'mm2717463@gmail.com';           // SMTP username
        $mail->Password   = 'gquo ikbe lexb ffsp';              // SMTP password (use app password if using Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
        $mail->Port       = 587;                              // TCP port to connect to

        // Recipients
        $mail->setFrom('noreply@sitename.com', 'Mailing List');
        $mail->addAddress($info['email']);                    // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Welcome to Our Mailing List';
        $mail->Body    = format_email($info, 'html');         // HTML email body
        $mail->AltBody = format_email($info, 'txt');          // Plain text email body

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}

// Database and Subscriber handling
$database = new Database();
$db = $database->getConnection();
$subscriber = new Subscribe($db);

// Initialize message variable
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subscriber->name = $_POST['name'];
    $subscriber->email = $_POST['email'];

    // Check if the email already exists
    if ($subscriber->getSubscriber()) {
        $message = "Email already exists. Please use a different email.";
    } else {
        // Attempt to insert the subscriber
        if ($subscriber->insert()) {
            // Prepare the info array to send the email
            $info = array(
                'username' => $subscriber->name,
                'email' => $subscriber->email
            );

            // Send the welcome email
            if (send_email($info)) {
                $message = "Subscriber added successfully! Please check your email for confirmation.";
            } else {
                $message = "Subscriber added, but email confirmation failed to send.";
            }
        } else {
            $message = "There was an error adding the subscriber. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join the Club</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('./img/bg.png'); /* Set your background image */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: cover; /* Cover the whole page */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* No repeating of the image */
        }

        .container {
            position: relative;
            text-align: center;
            margin-top: 50px;
        }

        .subscribe-image {
            width: 100%;
            max-width: 600px;
            height: auto; /* Maintain aspect ratio */
        }

        .form-overlay {
            position: absolute;
            top: 77%; /* Adjust the top position to fit inside the white letter */
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%; /* Adjust width to fit inside the white letter */
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center; /* Centers the inputs horizontally */
        }

        .form-overlay input[type="text"],
        .form-overlay input[type="email"] {
            padding: 10px;
            width: 100%;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            max-width: 500px;
        }

        .form-overlay button {
            padding: 10px 20px;
            background-color: #FF56BE; /* Button color */
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 16px;
            width: 55%;
        }

        .form-overlay button:hover {
            background-color: #000;
        }

        /* Error message styles */
        .alert {
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 500px;
        }
        .text-danger.hidden { display: none; }
        .status { margin-top: 10px; }
        .success { color: green; }
        .error { color: red; }

        /* Media queries for responsiveness */
        @media (max-width: 576px) {
            .form-overlay {
                top: 75%; /* Adjusted for smaller screens */
            }
            .form-overlay button {
                width: 70%; /* Wider button on small screens */
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Background Image -->
        <img src="img/0c0d4f0b-245f-4fa2-97e2-1a7309dcf1f7-Photoroom.png" alt="Join the Club" class="subscribe-image">

        <!-- Display message -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Form Overlay -->
        <div class="form-overlay">
            <form action="#" id="subscribeForm" method="post">
                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                <span class="text-danger hidden" id="nameError"></span>
                <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                <span class="text-danger hidden" id="emailError"></span>
                <br>
                <button type="submit" id="subscribe">Subscribe</button>
            </form>
            <div id="responseMessage" style="display: none;"></div> <!-- For displaying messages -->
        </div>
    </div>
</body>
</html>
