<?php
include_once "../user/connection.php";

$StudentID = $_POST['StudentID'] ?? '';
$CourseID = $_POST['CourseID'] ?? '';

if (!empty($StudentID) && !empty($CourseID)) {
    // Query to fetch subjects and marks
    $sql = "SELECT s.SubjectID, s.SubjectName, m.MarksObtained
            FROM Subjects s
            LEFT JOIN SubjectMarks m ON s.SubjectID = m.SubjectID AND m.StudentID = '$StudentID' AND m.CourseID = '$CourseID'
            WHERE s.CourseID = '$CourseID'";

    $result = $conn->query($sql);

    // Keep track of subject IDs to avoid duplicates
    $existing_subjects = [];

    while ($row = $result->fetch_assoc()) {
        // Check if the subject has already been printed
        if (!in_array($row['SubjectID'], $existing_subjects)) {
            $existing_subjects[] = $row['SubjectID'];  // Add to the list of printed subject IDs

            // Output the input field for the subject's mark
            echo "<label>{$row['SubjectName']}:</label><br>";
            echo "<input type='number' name='marks_{$row['SubjectID']}' value='{$row['MarksObtained']}' placeholder='Enter {$row['SubjectName']} Marks'><br><br>";
        }
    }
}

// After form submission, handle the update/insert for marks
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Loop through all subjects and insert/update marks
    foreach ($existing_subjects as $subjectID) {
        $marksObtained = $_POST["marks_$subjectID"] ?? 0;  // Get the marks entered by the user

        // Check if marks already exist in the SubjectMarks table
        $sql_check = "SELECT * FROM SubjectMarks WHERE StudentID = '$StudentID' AND CourseID = '$CourseID' AND SubjectID = '$subjectID'";
        $existingMark = $conn->query($sql_check)->fetch_assoc();

        if ($existingMark) {
            // Update existing mark
            $sql_update = "UPDATE SubjectMarks SET MarksObtained = '$marksObtained' WHERE StudentID = '$StudentID' AND CourseID = '$CourseID' AND SubjectID = '$subjectID'";
            $conn->query($sql_update);
        } else {
            // Insert new mark
            $sql_insert = "INSERT INTO SubjectMarks (StudentID, CourseID, SubjectID, MarksObtained) 
                           VALUES ('$StudentID', '$CourseID', '$subjectID', '$marksObtained')";
            $conn->query($sql_insert);
        }
    }
}
?>
