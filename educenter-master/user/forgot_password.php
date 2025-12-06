<?php
session_start();
require '../vendor/autoload.php'; // PHPMailer
require '../user/connection.php'; // Your DB connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $error = $success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if (empty($email)) {
        $error = "Please enter your email.";
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $code = rand(100000, 999999); // 6-digit code
            date_default_timezone_set("Asia/Kolkata"); // or your timezone
            $expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes"));


            // Save code to DB
            $stmt = $conn->prepare("INSERT INTO password_reset_codes (email, code, expires_at) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $code, $expires_at);
            $stmt->execute();

            // Send email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'mitpatil412@gmail.com';
                $mail->Password   = 'wiux chjl aosi poma';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('mitpatil412@gmail.com', 'TechVerse');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Code';
                $mail->Body    = "Your password reset code is: <strong>$code</strong>. It will expire in 10 minutes.";

                $mail->send();
                $success = "Verification code sent to your email.";
                $_SESSION['reset_email'] = $email;
                header("Location: verify_code.php");
                exit();

            } catch (Exception $e) {
                $error = "Email could not be sent. Error: {$mail->ErrorInfo}";
            }
        } else {
            $error = "Email not found in the system.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  <!-- Form CSS -->
  <link rel="stylesheet" href="../css/form.css">  
  <!-- Themify Icons for Header -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css" />
  
  <!-- JQuery for Loading Header/Footer -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script></head>

  <script src="../user/reg_validation.js"></script>
<body>
<!-- Header -->
<div class="header-container"></div>

<!-- Main Form Area -->
<div class="container">

<div class="left-panel">
</div>

<div class="form-container">
<form method="POST" onsubmit="return validate_forgot_password_form()">
    <h2>Forgot Password</h2>
    <label>Email:
        <input type="email" id ="Email" name="email" value="<?= htmlspecialchars($email) ?>" oninput="validate_email()"></Input><span id="email_err" class="error"></span>
    </label><br><br>
    <input type="submit" value="Send Verification Code"><br><br>
    <span style="color:red"><?= $error ?></span>
    <span style="color:green"><?= $success ?></span>
</form>
</div>
</div>
<script>
  $(document).ready(function () {
    $(".header-container").load("../header.php");
    $(".footer-container").load("../footer.php");
  });
</script>
</body>
</html>

