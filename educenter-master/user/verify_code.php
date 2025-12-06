<?php
session_start();
require '../user/connection.php';

$code = $error = "";

// Redirect if user tries to access directly
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit;
}

$email = $_SESSION['reset_email'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = trim($_POST['code']); // Remove extra spaces

    if (empty($code)) {
        $error = "Please enter the verification code.";
    } else {
        // Query to match latest code for this email and still valid
        $stmt = $conn->prepare("SELECT * FROM password_reset_codes 
                                WHERE email = ? AND code = ? AND expires_at > NOW() 
                                ORDER BY expires_at DESC LIMIT 1");
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Valid code
            $_SESSION['code_verified'] = true;
            header("Location: reset_password.php");
            exit;
        } else {
            $error = "Invalid or expired code. Please try again.";
        }
    }
}
?>

<!-- HTML Part -->
<!DOCTYPE html>
<html>
<head>
    <title>Verify Code</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
<div class="container">

<div class="left-panel">
</div>

<div class="form-container">
    <form method="POST">
        <h2>Verify Code</h2>
        <p>We sent a 6-digit verification code to your email.</p>

        <label>Enter Code:
            <input type="text" name="code">
        </label><br><br>

        <input type="submit" value="Verify Code"><br><br>

        <span style="color:red"><?= htmlspecialchars($error) ?></span>
    </form>
    </div>
    </div>
</body>
</html>
