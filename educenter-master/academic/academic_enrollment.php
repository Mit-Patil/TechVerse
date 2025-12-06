
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Validation script -->
    <script src="../user/reg_validation.js"></script>

    <?php
session_start();
$_SESSION['currentPhase'] = 2;
$_SESSION['regform2'] = "";
$_SESSION['regform3'] = "";


        include_once "../user/connection.php";

        $Department = '';
        $CourseID = '';
        $YearOfStudy = date('Y'); // default current year
        $EnrollmentYear = date('Y');
        if (!isset($_SESSION['regform1']) || $_SESSION['regform1'] !== 1 || !$_SESSION['user_id']) {
            header("Location: ../student module/student.php");
            exit;
        }
        // if (isset($_GET['user_id']))  {
        //     $user_id = $_GET['user_id'];
        // }

        $user_id = $_SESSION['user_id'];
        
        $sql = "SELECT StudentID,FirstName,LastName FROM Students WHERE StudentID = $user_id";
        $result = $conn->query($sql);
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $StudentID=$row['StudentID'];
            $FirstName=$row['FirstName'];
            $LastName=$row['LastName'];

            $sql_enroll="SELECT a.StudentID,a.Department,a.YearOfStudy,a.EnrollmentYear,e.CourseID FROM AcademicDetails a,Enrollments e WHERE a.StudentID=e.StudentID AND a.StudentID=$StudentID";
            $result_enroll=$conn->query($sql_enroll);
            if ($result_enroll->num_rows === 1) {
                $row_enroll = $result_enroll->fetch_assoc();
                $StudentID=$row_enroll['StudentID'];
                $Department=$row_enroll['Department'];
                $YearOfStudy=$row_enroll['YearOfStudy'];
                $EnrollmentYear=$row_enroll['EnrollmentYear'];
                $CourseID=$row_enroll['CourseID'];
            }
            else{
                $row_enroll = null;
            }
        } else {
            echo "Error fetching user data.";
            exit;
        }

        if($_SERVER['REQUEST_METHOD']=='POST')
        {

            $Department=$_POST['Department'];
            $YearOfStudy=$_POST['YearOfStudy'];
            $EnrollmentYear=$_POST['EnrollmentYear'];
            $CourseID=$_POST['CourseID'];

            $sql_enroll="SELECT a.StudentID,a.Department,a.YearOfStudy,a.EnrollmentYear,e.CourseID FROM AcademicDetails a,Enrollments e WHERE a.StudentID=e.StudentID AND a.StudentID='$user_id'";
            $result_enroll=$conn->query($sql_enroll);
            if ($result_enroll->num_rows > 0) {
               
                $update_AcademicDetails="UPDATE AcademicDetails SET Department='$Department',YearOfStudy='$YearOfStudy',EnrollmentYear='$EnrollmentYear' WHERE StudentID='$StudentID'";
                $update_Enrollments="UPDATE Enrollments SET CourseID='$CourseID' WHERE StudentID='$StudentID'";
                if($conn->query($update_AcademicDetails)==TRUE)
                {
                    if($conn->query($update_Enrollments)==TRUE)
                    {
                        $_SESSION['user_id']=$StudentID;
                        $_SESSION['regform2'] = 1;
                        header('Location:../student module/studentup.php');
                    }
                }
                else{
                    echo "Error";
                }
            }
            else{

            $sql_AcademicDetails="INSERT INTO AcademicDetails(StudentID,Department,YearOfStudy,EnrollmentYear) VALUES('$StudentID','$Department','$YearOfStudy','$EnrollmentYear')";
            $sql_Enrollments="INSERT INTO Enrollments(StudentID,CourseID) VALUES('$StudentID','$CourseID')";

            if($conn->query($sql_AcademicDetails)==TRUE)
            {
                if($conn->query($sql_Enrollments)==TRUE)
                {
                    $_SESSION['user_id']=$StudentID;
                    $_SESSION['regform2'] = 1;
                    header('Location:../student module/studentup.php');
                }
            }
            else{
                echo "Error";
            }
        }
            $conn->close();

        }

    ?>

</head>
<body>
  <!-- Header -->
<div class="header-container"></div>

<!-- Main Form Area -->
<div class="container">

<div class="container">

    <div class="left-panel">
        <?php include '../student module/reg_phase.php'; ?> 
    </div>

    <div class="form-container">


    <form method="post" action="" onsubmit="return validate_student_academic()">
    <h1 class="header">Academic Details</h1>

    <label>First Name:
            <input type="text" id="FirstName" name="FirstName" placeholder="Enter Your FirstName" value="<?php echo $FirstName;?>" readonly>
        </label><br><br>
        
        <label>Last Name:
            <input type="text" id="LastName" name="LastName" placeholder="Enter Your LastName" value="<?php echo $LastName;?>" readonly>
        </label><br><br>


        <label>Course:
            <select name="CourseID" id="CourseID" onchange="validate_course()">
                <option value="">Select Course</option>
                <?php
                    $sql="SELECT CourseID,CourseName FROM Courses";
                    $result=$conn->query($sql);

                    if($result->num_rows>0)
                    {
                        while($row = $result->fetch_assoc()) {
                            $selected = ($CourseID == $row['CourseID']) ? 'selected' : '';
                            echo "<option value='" . $row['CourseID'] . "' $selected>" . $row['CourseName'] . "</option>";
                        }
                        
                    }
                    else{
                            echo "<option value=''>No courses available</option>";
                    }
                    $conn->close();
                ?>
            </select>
            <span id="course_err" class="error"></span>
            </label><br/><br/>
        

    <label>Department:
        <select name="Department" id="Department" onchange="validate_department()">
            <option value="">Select Department</option>
            <option value="it" <?php if($Department == 'it') echo 'selected'; ?>>IT</option>
            <option value="ict" <?php if($Department == 'ict') echo 'selected'; ?>>ICT</option>
            <option value="other" <?php if($Department == 'other') echo 'selected'; ?>>OTHER</option>
        </select>
        <span id="department_err" class="error"></span>
        </label><br><br>

    <label for="YearOfStudy">Year OF Study:</label>
    <input type="text" id="YearOfStudy" name="YearOfStudy" value="<?php echo date('Y'); ?>" readonly><br><br>

    <label for="EnrollmentYear">Enrollment Year:</label>
    <input type="text" id="EnrollmentYear" name="EnrollmentYear" value="<?php echo date('Y'); ?>" readonly><br><br>

    <input type="submit" value="Submit"><br>
        <span id="error" class="error"></span>

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
