<link rel="stylesheet" href="../css/result.css">
  <!-- Form CSS -->
  <link rel="stylesheet" href="../css/form.css">  
  <!-- Themify Icons for Header -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css" />
  
  <!-- JQuery for Loading Header/Footer -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Header -->
<div class="header-container"></div>

<script>
  $(document).ready(function () {
    $(".header-container").load("../header.php");
    $(".footer-container").load("../footer.php");
  });
</script>
<?php
include_once "../user/connection.php";
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../user/login.php");
    exit;
} else {
    $email = $_SESSION['email'];

    // Fetch student details
    $sql_student = "SELECT StudentID, FirstName, LastName, Gender, Email FROM Students WHERE Email='$email'";
    $student_detail = $conn->query($sql_student);

    if ($student_detail->num_rows == 1) {
        $row_student = $student_detail->fetch_assoc();

        $StudentID = $row_student['StudentID'];
        $FirstName = $row_student['FirstName'];
        $LastName = $row_student['LastName'];
        $Gender = $row_student['Gender'];
        $Email = $row_student['Email'];

        // Fetch result details
        $sql_results = "SELECT r.TotalMarksObtained, r.TotalSubjectMarks, r.Percentage, r.CGPA, r.Grade, c.CourseName, c.CourseID 
                        FROM Results r
                        JOIN Courses c ON r.CourseID = c.CourseID
                        WHERE r.StudentID = '$StudentID' AND r.Published = 1";

        $result_course_details = $conn->query($sql_results);

        if ($result_course_details->num_rows > 0) {
            while ($row_result = $result_course_details->fetch_assoc()) {
                $TotalMarksObtained = $row_result['TotalMarksObtained'];
                $TotalSubjectMarks = $row_result['TotalSubjectMarks'];
                $Percentage = $row_result['Percentage'];
                $CGPA = $row_result['CGPA'];
                $Grade = $row_result['Grade'];
                $CourseName = $row_result['CourseName'];
                $CourseID = $row_result['CourseID'];

                // Display student result
                echo "
                <div class='result-container'>
                    <h3 class='result-header'>Student Result</h3>
                    <div class='student-info'>
                        <p><strong>Name:</strong> $FirstName $LastName</p>
                        <p><strong>Gender:</strong> $Gender</p>
                        <p><strong>Email:</strong> $Email</p>
                        <p><strong>Course Name:</strong> $CourseName</p>
                    </div>

                    <table class='result-table'>
                        <tr>
                            <th>Total Marks</th>
                            <th>Marks Obtained</th>
                            <th>Percentage</th>
                            <th>CGPA</th>
                            <th>Grade</th>
                        </tr>
                        <tr>
                            <td>$TotalSubjectMarks</td>
                            <td>$TotalMarksObtained</td>
                            <td>$Percentage%</td>
                            <td>$CGPA</td>
                            <td>$Grade</td>
                        </tr>
                    </table>";

                // Fetch subject-wise marks
                $sql_subjects = "SELECT s.SubjectName, sm.MarksObtained 
                                 FROM SubjectMarks sm
                                 JOIN Subjects s ON sm.SubjectID = s.SubjectID
                                 WHERE sm.StudentID = '$StudentID' AND sm.CourseID = '$CourseID'";

                $subject_details = $conn->query($sql_subjects);

                if ($subject_details->num_rows > 0) {
                    echo "<h4 class='subject-header'>Subject-wise Marks</h4>";
                    echo "<table class='subject-table'>
                            <tr>
                                <th>Subject</th>
                                <th>Marks Obtained</th>
                            </tr>";
                    while ($row_sub = $subject_details->fetch_assoc()) {
                        $SubjectName = $row_sub['SubjectName'];
                        $MarksObtained = $row_sub['MarksObtained'];

                        echo "<tr>
                                <td>$SubjectName</td>
                                <td>$MarksObtained</td>
                              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p class='no-subjects'>No subject-wise marks found.</p>";
                }

                echo "</div>"; // End result-container
            }
        } else {
            echo "<p class='error-message'>Result Not Published</p>";
        }
    } else {
        echo "<p class='error-message'>No Student Found</p>";
    }
}
?>
