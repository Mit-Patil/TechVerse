<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fee Registration</title>

  <link rel="stylesheet" href="../css/form.css">  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="../user/other_validation.js"></script>

  <style>
    .success-message {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      padding: 10px;
      margin-top: 15px;
      border-radius: 4px;
    }
    .error-message {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
      padding: 10px;
      margin-top: 15px;
      border-radius: 4px;
    }
  </style>

  <?php
    include_once "../user/connection.php";
    include_once "../user/send_mail.php";

    $CourseID = $FeeAmount = $AcademicYear = "";
    $statusMessage = "";
    $statusClass = "success-message";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Send Reminder Button
        if (isset($_POST['send_reminder'])) {
            sendFeeReminder($conn);
            $statusMessage = "✅ Fee reminders sent successfully.";
        }

        // Fee structure form
        if (isset($_POST['CourseID']) && isset($_POST['FeeAmount']) && isset($_POST['AcademicYear'])) {
            $CourseID = $_POST['CourseID'];
            $FeeAmount = $_POST['FeeAmount'];
            $AcademicYear = $_POST['AcademicYear'];

            $check_fees = "SELECT * FROM FeeStructure WHERE CourseID='$CourseID'";
            $found = $conn->query($check_fees);

            if ($found->num_rows === 1) {
                // Update existing fee
                $sql = "UPDATE FeeStructure SET FeeAmount='$FeeAmount', AcademicYear='$AcademicYear' WHERE CourseID='$CourseID'";
                if ($conn->query($sql) === TRUE) {
                    // Also update StudentFees
                    $updateStudentFees = "UPDATE StudentFees SET FeeAmount='$FeeAmount' WHERE CourseID='$CourseID'";
                    $conn->query($updateStudentFees);

                    $statusMessage = "✅ Fee structure updated and student fees updated.";
                } else {
                    $statusMessage = "❌ Error updating fee structure.";
                    $statusClass = "error-message";
                }
            } else {
                // Insert new fee structure
                $sql = "INSERT INTO FeeStructure(CourseID, FeeAmount, AcademicYear) VALUES('$CourseID','$FeeAmount','$AcademicYear')";
                if ($conn->query($sql) === TRUE) {
                    $statusMessage = "✅ Fee structure inserted.";
                } else {
                    $statusMessage = "❌ Error inserting fee structure.";
                    $statusClass = "error-message";
                }
            }
        }


        $conn->close();
    }
  ?>
</head>
<body>

<!-- Header -->
<div class="header-container"></div>

<div class="container">
  <div class="left-panel"></div>

  <div class="form-container">
  <form action="" method="post" onsubmit="return validate_fee_form()">
  <h1 class="header">Fees Registration</h1>

  <label>Course:
    <select name="CourseID" id="CourseID" oninput="validate_fee_course()">
      <option value="">Select Course</option>
      <?php
        include "../user/connection.php";
        $sql = "SELECT CourseID, CourseName FROM Courses";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $selected = ($row['CourseID'] == $CourseID) ? "selected" : "";
            echo "<option value='{$row['CourseID']}' $selected>{$row['CourseName']}</option>";
          }
        } else {
          echo "<option value=''>No courses available</option>";
        }
        $conn->close();
      ?>
    </select>
    <span id="course_err" class="error"></span>
  </label><br><br>

  <label>Fee Amount:
    <input type="text" name="FeeAmount" id="FeeAmount" placeholder="Enter Fee Amount" value="<?php echo htmlspecialchars($FeeAmount); ?>" oninput="validate_fee_amount()">
    <span id="fee_err" class="error"></span>
  </label><br><br>

  <label>Academic Year:
    <input type="text" name="AcademicYear" id="AcademicYear" placeholder="e.g. 2024-25" value="<?php echo htmlspecialchars($AcademicYear); ?>" oninput="validate_academic_year()">
    <span id="year_err" class="error"></span>
  </label><br><br>

  <span id="error" class="error"></span><br>

  <input type="submit" value="Submit"><br><br>

  <?php if (!empty($statusMessage)): ?>
    <div class="<?php echo $statusClass; ?>"><?php echo $statusMessage; ?></div>
  <?php endif; ?>
</form>

<form method="post" action="">
  <button type="submit" name="send_reminder">Send Fee Reminder</button>
</form>

  </div>
</div>

<!-- Footer Load -->
<script>
  $(document).ready(function () {
    $(".header-container").load("../header.php");
    $(".footer-container").load("../footer.php");
  });
</script>

</body>
</html>
