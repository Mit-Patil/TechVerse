<?php
include_once "../user/connection.php";
include_once "../user/send_mail.php";

// Initialize variables
$StudentID = $_POST['StudentID'] ?? '';
$CourseID = $_POST['CourseID'] ?? '';
$total_marks = 0;
$total_subject_marks = 0;
$percentage = 0;
$cgpa = 0;
$grade = '';
$published = 1;

// Handle form submission to save subject marks and calculate result
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    // Get subjects only after form is submitted with StudentID and CourseID
    $subjects = [];
    $sql = "SELECT SubjectID, SubjectName FROM Subjects WHERE CourseID = '$CourseID'";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }

    // Loop through each subject to save marks
    foreach ($subjects as $subject) {
        $subjectID = $subject['SubjectID'];
        $marksObtained = $_POST['marks_' . $subjectID] ?? 0;

        // Check if mark already exists for this subject
        $sql_check = "SELECT * FROM SubjectMarks WHERE StudentID = '$StudentID' AND CourseID = '$CourseID' AND SubjectID = '$subjectID'";
        $existingMark = $conn->query($sql_check)->fetch_assoc();

        if ($existingMark) {
            // Update existing mark
            $sql_update = "UPDATE SubjectMarks SET MarksObtained = '$marksObtained' WHERE StudentID = '$StudentID' AND CourseID = '$CourseID' AND SubjectID = '$subjectID'";
            $conn->query($sql_update);
        } else {
            // Insert new mark
            $sql_insert = "INSERT INTO SubjectMarks (StudentID, CourseID, SubjectID, MarksObtained) VALUES ('$StudentID', '$CourseID', '$subjectID', '$marksObtained')";
            $conn->query($sql_insert);
        }

        // Add to totals
        $total_marks += $marksObtained;
        $total_subject_marks += 100; // assuming full marks is 100 per subject
    }

    // Calculate percentage, CGPA, and grade
    if ($total_subject_marks > 0) {
        $percentage = ($total_marks / $total_subject_marks) * 100;
        $cgpa = ($percentage / 100) * 10;
        $grade = determineGrade($percentage);
    }

    // Check if result already exists and update or insert accordingly
    $sql_result = "SELECT * FROM Results WHERE StudentID = '$StudentID' AND CourseID = '$CourseID'";
    $result = $conn->query($sql_result);

    if ($result->num_rows > 0) {
        // Update result
        $sql_update_result = "UPDATE Results SET TotalMarksObtained = '$total_marks', TotalSubjectMarks = '$total_subject_marks', Percentage = '$percentage', CGPA = '$cgpa', Grade = '$grade', Published = '$published' WHERE StudentID = '$StudentID' AND CourseID = '$CourseID'";
        $conn->query($sql_update_result);
    } else {
        // Insert result
        $sql_insert_result = "INSERT INTO Results (StudentID, CourseID, TotalMarksObtained, TotalSubjectMarks, Percentage, CGPA, Grade, Published) VALUES ('$StudentID', '$CourseID', '$total_marks', '$total_subject_marks', '$percentage', '$cgpa', '$grade', '$published')";
        $conn->query($sql_insert_result);
    }
}

// Function to determine grade based on percentage
function determineGrade($percentage) {
    if ($percentage >= 90) return 'A+';
    if ($percentage >= 80) return 'A';
    if ($percentage >= 70) return 'B';
    if ($percentage >= 60) return 'C';
    if ($percentage >= 50) return 'D';
    return 'F';
}

if (isset($_POST['send_result_notification'])) {
    sendResultNotification($conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Result Creation</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <!-- Form CSS -->
      <link rel="stylesheet" href="../css/form.css">  
      <!-- Themify Icons for Header -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css" />
  
  <!-- JQuery for Loading Header/Footer -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../user/other_validation.js"></script>
</head>
<body>
<!-- Header -->
<div class="header-container"></div>

<!-- Main Form Area -->  
<div class="container">

   <div class="left-panel">
        <!-- Optional content -->
    </div>

    <div class="form-container">
    <form method="post" onsubmit="return validateForm()">
    <h1 class="header">Generate Result</h1>

    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <label>
        Student:
        <select name="StudentID" id="StudentID" onchange="loadCourses()">
            <option value="">Select Student</option>
            <?php
            $result_students = $conn->query("SELECT StudentID, FirstName, LastName FROM Students");
            while ($row = $result_students->fetch_assoc()) {
                $selected = ($StudentID == $row['StudentID']) ? 'selected' : '';
                echo "<option value='{$row['StudentID']}' $selected>{$row['StudentID']} - {$row['FirstName']} {$row['LastName']}</option>";
            }
            ?>
        </select>
        <span class="error" id="student-error"></span>
    </label><br><br>

    <label>
        Course:
        <select name="CourseID" id="CourseID" onchange="loadSubjects()">
            <option value="">Select Course</option>
        </select>
        <span class="error" id="course-error"></span>
    </label><br><br>

    <div id="subjects-container">
        <!-- AJAX-loaded subject inputs -->
    </div>

    <label>Total Marks Obtained:
        <input type="text" name="total_marks" value="<?php echo htmlspecialchars($total_marks); ?>" readonly>
        <span class="error" id="marks-error"></span>
    </label><br><br>

    <label>Percentage:
        <input type="text" name="percentage" value="<?php echo htmlspecialchars($percentage); ?>" readonly>
    </label><br><br>

    <label>CGPA:
        <input type="text" name="cgpa" value="<?php echo htmlspecialchars($cgpa); ?>" readonly>
    </label><br><br>

    <label>Grade:
        <input type="text" name="grade" value="<?php echo htmlspecialchars($grade); ?>" readonly>
    </label><br><br>

    <input type="submit" name="submit" value="Generate Result">
</form>
<form method="post">
    <button type="submit" name="send_result_notification">📩 Send Result Published Emails</button>
</form>



    </div>  
</div>

<script>
// Load Courses
function loadCourses() {
    var studentId = $('#StudentID').val();
    if (studentId !== '') {
        $.post('../academic/fetch_courses.php', { StudentID: studentId }, function(response) {
            $('#CourseID').html(response);
            loadSubjects(); // Load subjects when a course loads
        });
    } else {
        $('#CourseID').html('<option value="">Select Course</option>');
    }
}

// Load Subjects
function loadSubjects() {
    var courseId = $('#CourseID').val();
    var studentId = $('#StudentID').val();

    if (courseId !== '' && studentId !== '') {
        $.post('../academic/fetch_subjects.php', { CourseID: courseId, StudentID: studentId }, function(response) {
            $('#subjects-container').html(response);
        });
    } else {
        $('#subjects-container').html('');
    }
}

// Auto load if previously selected
<?php if (!empty($StudentID)): ?>
    $(document).ready(function() {
        loadCourses();
        setTimeout(function() {
            $('#CourseID').val('<?php echo $CourseID; ?>');
            loadSubjects();
        }, 300);
    });
<?php endif; ?>
</script>

<script>
  $(document).ready(function () {
    $(".header-container").load("../header.php");
    $(".footer-container").load("../footer.php");
  });
</script>
</body>
</html>
