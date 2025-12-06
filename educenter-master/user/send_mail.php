<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once "../user/connection.php";
require '../vendor/autoload.php'; 


function sendMail($toEmail, $toName, $subject, $bodyHTML) {
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
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHTML;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: {$mail->ErrorInfo}");
        return false;
    }
}

function sendConfirmationEmail($toEmail, $toName) {
    $subject = 'Application Submitted Successfully';
    $body = "
        <h2>Hello $toName,</h2>
        <p>Your application has been <strong>successfully submitted</strong>.</p>
        <p>Thank you for registering with us.</p>
        <br><p>Best regards,<br>TechVerse</p>
    ";
    return sendMail($toEmail, $toName, $subject, $body);
}

function sendFeeReminder($conn) {

        // 🔹 Fee Reminder Send to Students
        $query = "SELECT s.Email, s.FirstName, sf.FeeAmount, sf.CourseID
              FROM StudentFees sf
              JOIN Students s ON sf.StudentID = s.StudentID
              WHERE sf.PaymentStatus = 'Pending'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $to = $row['Email'];
            $name = $row['FirstName'];
            $amount = $row['FeeAmount'];

            // Send fee reminder email
            $subject = "Fee Payment Reminder";
            $body = "Dear $name,<br><br>This is a gentle reminder that your fee payment of <b>₹$amount</b> is still pending.<br>
                     Kindly complete the payment at your earliest convenience.<br><br>Thank you.";

            sendMail($to, $name, $subject, $body);
        }
        // echo "<script>alert('Fee reminders sent to pending students.');</script>";
    } else {
        echo "<script>alert('No pending payments found.');</script>";
    }
    
}

function sendNoticeToAllUsers($conn, $title, $description, $notice_date, $publisher_name) {
    $subject = "New Notice: $title";
    $body = "
        <h3>📢 New Notification</h3>
        <p><strong>Title:</strong> $title</p>
        <p><strong>Description:</strong><br>" . nl2br($description) . "</p>
        <p><strong>Date:</strong> $notice_date</p>
        <p><strong>Published by:</strong> $publisher_name</p>
        <br><p>Check your dashboard for more details.</p>
    ";

    // 🔹 Send to Students
    $studentQuery = "SELECT s.Email, s.FirstName, sf.FeeAmount, sf.CourseID
              FROM StudentFees sf
              JOIN Students s ON sf.StudentID = s.StudentID
              WHERE sf.PaymentStatus = 'Pending'";
    $studentResult = $conn->query($studentQuery);

    if ($studentResult && $studentResult->num_rows > 0) {
        while ($row = $studentResult->fetch_assoc()) {
            sendMail($row['Email'], $row['FirstName'], $subject, $body);
        }
    }

    // 🔹 Send to Faculty
    $facultyQuery = "SELECT FirstName, Email FROM Faculty";
    $facultyResult = $conn->query($facultyQuery);

    if ($facultyResult && $facultyResult->num_rows > 0) {
        while ($row = $facultyResult->fetch_assoc()) {
            sendMail($row['Email'], $row['FirstName'], $subject, $body);
        }
    }
}

function sendResultNotification($conn) {
    // 🔹 Fetch students with Published = 1 (i.e., result is published)
    $query = "SELECT s.Email, s.FirstName, r.Percentage, r.Grade, r.CourseID
              FROM Results r
              JOIN Students s ON r.StudentID = s.StudentID
              WHERE r.Published = 1";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $to = $row['Email'];
            $name = $row['FirstName'];
            $percentage = round($row['Percentage'], 2);
            $grade = $row['Grade'];
            $courseID = $row['CourseID'];

            // Email Subject & Body
            $subject = "Result Published ";
            $body = "
                Dear $name,<br><br>
                We are pleased to inform you that your result for <strong>Course ID: $courseID</strong> has been published.<br><br>
                <strong>Percentage:</strong> $percentage%<br>
                <strong>Grade:</strong> $grade<br><br>
                You can view your detailed result by logging into the student portal.<br><br>
                Best regards,<br>
                TechVerse
            ";

            sendMail($to, $name, $subject, $body);
        }
        echo "<script>alert('Result published emails have been sent.');</script>";
    } else {
        echo "<script>alert('No students with published results found.');</script>";
    }
}


