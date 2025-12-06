<?php
include_once "../user/connection.php";

$StudentID = $_POST['StudentID'] ?? '';

if (!empty($StudentID)) {
    // Simple query without prepared statements
    $query = "SELECT E.CourseID, C.CourseName FROM Enrollments E JOIN Courses C ON E.CourseID = C.CourseID WHERE E.StudentID = $StudentID";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<option value=''>Select Course</option>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['CourseID']}'>{$row['CourseID']} - {$row['CourseName']}</option>";
        }
    } else {
        echo "<option value=''>No courses available</option>";
    }
}
?>
