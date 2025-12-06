<?php
session_start();
require '../user/connection.php';

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['code_verified'])) {
    header("Location: forgot_password.php");
    exit;
}

$email = $_SESSION['reset_email'];
$newPassword = $confirmPassword = "";
$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($newPassword) || empty($confirmPassword)) {
        $error = "Both password fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Update the password in the users table
        $stmt = $conn->prepare("UPDATE users SET Password = ? WHERE Email = ?");
        $stmt->bind_param("ss", $newPassword, $email);

        if ($stmt->execute()) {
            // Optional: delete the code from table
            $del = $conn->prepare("DELETE FROM password_reset_codes WHERE email = ?");
            $del->bind_param("s", $email);
            $del->execute();

            // Destroy session data for reset
            unset($_SESSION['reset_email']);
            unset($_SESSION['code_verified']);

            $success = "Password has been reset successfully. You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Something went wrong. Please try again.";
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
<form method="POST" onsubmit="return validate_reset_password_form()">
    <h2>Reset Password</h2>

    <label>New Password:
        <input type="password" name="new_password" id="Password" oninput="validate_password()">
    </label><br>
    <span id="password_err" class="error"></span></span><br><br>

    <label>Confirm Password:
        <input type="password" name="confirm_password" id="con_Password" oninput="validate_con_password()">
    </label><br>
    <span id="con_password_err" class="error"></span><br><br>

    <input type="submit" value="Reset Password"><br><br>

    <?php if ($error): ?>
        <span style="color:red"><?= $error ?></span>
    <?php elseif ($success): ?>
        <span style="color:green"><?= $success ?></span>
    <?php endif; ?>
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
