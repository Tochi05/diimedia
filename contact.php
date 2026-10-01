<?php
require 'includes/db.php';
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = "";
$error   = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = trim($_POST["name"]);
    $phone   = trim($_POST["phone"]);
    $email   = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    if (empty($name) || empty($phone) || empty($email) || empty($message)) {
        $error = "Please fill in all required fields.";
    } else {
        $sql  = "INSERT INTO messages (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $name, $phone, $email, $subject, $message);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Thank you! Your message has been received. We will get back to you shortly.";

            // Send email notification
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = MAIL_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = MAIL_USERNAME;
                $mail->Password   = MAIL_PASSWORD;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = MAIL_PORT;

                $mail->setFrom(MAIL_FROM, MAIL_NAME);
                $mail->addAddress(MAIL_FROM);
                $mail->addReplyTo($email, $name);

                $mail->isHTML(true);
                $mail->Subject = "New Contact Form Submission" . (!empty($subject) ? ": " . $subject : "");
                $mail->Body    = "
                    <strong>Name:</strong> $name<br>
                    <strong>Phone:</strong> $phone<br>
                    <strong>Email:</strong> $email<br>
                    <strong>Subject:</strong> " . (!empty($subject) ? $subject : "N/A") . "<br>
                    <strong>Message:</strong><br>$message
                ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Mailer Error: {$mail->ErrorInfo}");
            }

        } else {
            $error = "Something went wrong. Please try again.";
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <title>DiiQUBE MEDIA</title>
</head>

<body>

    <nav id="navbar">
        <div class="container">
            <a href="index.html">
                <div class="logo">
                    <img src="assets/img/logo.png" class="logo-dark" alt="Logo">
                    <img src="assets/img/logo-wh.png" class="logo-light" alt="Logo-wh">
                </div>
            </a>

            <div class="nav-links">
                <span class="nav-close" id="navClose"><i class="fa-solid fa-xmark"></i></span>
                <a href="index.html">Home</a>
                <a href="about.html">About Us</a>
                <a href="service.html">Works</a>
                <a href="contact.php">Contact</a>
            </div>

            <div class="icon hamburger" id="hamburger">
                <a href="#" aria-label="Menu"><i class="fa-solid fa-bars"></i></a>
            </div>
        </div>
    </nav>


    <div class="contact">
        <div class="contact-head">
            <p data-reveal="fade-up">Contact Us</p>
            <h1 data-reveal="fade-up" data-delay="0.1">Get In Touch With Us</h1>

            <div class="contact-grid">

                <div class="contact-cards">
                    <div class="contact-info">
                        <h3>Contact Information</h3>

                        <div class="contact-list">
                            <p><i class="fa-solid fa-phone"></i> +234-913-422-6014</p>
                            <p><i class="fa-regular fa-envelope"></i> contact.diiqubemetro@gmail.com</p>
                            <p><i class="fa-brands fa-linkedin-in"></i> <a href="#">LinkedIn</a></p>
                            <p><i class="fa-brands fa-whatsapp"></i> <a href="#">WhatsApp</a></p>
                        </div>
                    </div>

                    <form class="contact-form" action="contact.php" method="POST">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name*</label>
                                <input type="text" id="name" name="name"
                                    placeholder="Enter your full name"
                                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number*</label>
                                <input type="tel" id="phone" name="phone"
                                    placeholder="Enter your phone number"
                                    value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address*</label>
                            <input type="email" id="email" name="email"
                                placeholder="Enter your email address"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject"
                                placeholder="What is this about?"
                                value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="message">Your Message*</label>
                            <textarea id="message" name="message" rows="5"
                                placeholder="How can we help you?"
                                required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="form-btn">Submit</button>
                            <p class="form-note">*Required fields</p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="assets/js/script.js"></script>
    </body>
    </html>