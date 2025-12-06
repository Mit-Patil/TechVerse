
<?php
session_start();
        include_once "../user/connection.php";

        if (!isset($_SESSION['email'])) {
            header("Location: ../user/login.php");
            exit;
        }
$FirstName=$LastName=$DateOfBirth=$Gender=$ContactNumber=$Email=$Address=$Username=$Password=$usercheck=$success="";


$email = $_SESSION['email'];
$sql = "SELECT * FROM Students WHERE Email = '$email'";//Student Personal Details---

$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $StudentID=$row['StudentID'];
    $FirstName=$row['FirstName'];
    $LastName=$row['LastName'];
    $DateOfBirth=$row['DateOfBirth'];
    $Gender=$row['Gender'];
    $ContactNumber=$row['ContactNumber'];
    $Email=$row['Email'];
    $Address=$row['Address'];
    $Profile = $row['Profile'];


    $sql_enroll="SELECT a.StudentID,a.Department,a.YearOfStudy,a.EnrollmentYear,e.CourseID FROM AcademicDetails a,Enrollments e WHERE a.StudentID=e.StudentID AND a.StudentID=$StudentID";//Student Academic Details
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

    $sql1="SELECT UserID,Username,Password from Users where Email='$Email'";//Student Account Details--
    $result1 = $conn->query($sql1);
    
    if($result1->num_rows === 1)
    {
        $row1 = $result1->fetch_assoc();
        $UserID=$row1['UserID'];
        $Username=$row1['Username'];
        $Password=$row1['Password'];
    }


} else {
    echo "<p style='color:red;'>Unable To fetch User Details</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //personal details
            $new_FirstName=$_POST['new_FirstName'];
            $new_LastName=$_POST['new_LastName'];
            $new_DateOfBirth=$_POST['new_DateOfBirth'];
            $new_Gender=$_POST['new_Gender'];
            $new_ContactNumber=$_POST['new_ContactNumber'];
            $new_Email=$_POST['new_Email'];
            $new_Address=$_POST['new_Address'];

            //academic details
            $Department=$_POST['Department'];
            $YearOfStudy=$_POST['YearOfStudy'];
            $EnrollmentYear=$_POST['EnrollmentYear'];
            $CourseID=$_POST['CourseID'];

            //Account Details
            $Username=$_POST['Username'];
            $Password=$_POST['Password'];

            // Keep old profile by default
            $profilePath = $Profile;

            if (isset($_FILES['new_Profile']) && $_FILES['new_Profile']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $file_type = $_FILES['new_Profile']['type'];

            if (in_array($file_type, $allowed_types)) {
                    $upload_dir = '../profile/student_profile/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
            }

        $ext = pathinfo($_FILES['new_Profile']['name'], PATHINFO_EXTENSION);
        $new_filename = $StudentID . "_" . $new_FirstName . "." . $ext;
        $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['new_Profile']['tmp_name'], $upload_path)) {
                $profilePath = $upload_path; // Only update if upload is successful
            } else {
                echo "Failed to upload new profile image. Keeping old image.";
            }
        } else {
                echo "Invalid file type. Keeping old image.";
            }
        }

            $RoleID=3;//Student Default Role id


// Check if the username already exists for another user
$checkUsernameSQL = "SELECT * FROM Users WHERE Username = '$Username' AND UserID != '$UserID'";
$checkResult = $conn->query($checkUsernameSQL);

if ($checkResult->num_rows > 0) {
    $usercheck = "This UserName Already Exist!! Try Unique Username";
}
else {
    $update_student = "UPDATE Students SET FirstName = '$new_FirstName', LastName = '$new_LastName',DateOfBirth='$new_DateOfBirth',Gender='$new_Gender',
                   ContactNumber='$new_ContactNumber',Email = '$new_Email',Address='$new_Address',Profile = '$profilePath' WHERE StudentID = $StudentID";

    $update_academic="UPDATE AcademicDetails SET Department='$Department',YearOfStudy='$YearOfStudy',EnrollmentYear='$EnrollmentYear' WHERE StudentID='$StudentID'";
    
    $update_enrollment="UPDATE Enrollments SET CourseID='$CourseID' WHERE StudentID='$StudentID'";

    $update_user="UPDATE Users SET Username='$Username',Email='$new_Email',Password='$Password',RoleID='$RoleID' WHERE UserID='$UserID'";

    if ($conn->query($update_student) && $conn->query($update_academic) && $conn->query($update_enrollment) && $conn->query($update_user)) {
    $success="Details Updated ✅ ";
    } else {
        echo "Error updating details.";
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
    <link rel="stylesheet" href="../css/profile.css">
      <!-- Form CSS -->
  <!-- Themify Icons for Header -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css" />
  
  <!-- JQuery for Loading Header/Footer -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="../user/reg_validation.js"></script>
</head>
<body>
<!-- Header -->
<div class="header-container"></div>

<!-- Main Form Area -->
<div class="container">

     <form action="" method="post" enctype="multipart/form-data" onsubmit="return validate_student_profile_form()">
    <h1>Student Profile</h1>
        <div class="personal_details">
        <h3>Personal Details</h3>

    <?php if (!empty($Profile)) : ?>
    <label>Profile:</label><br>
    <img src="<?php echo $Profile; ?>" alt="Profile Image"><br><br>
    <?php endif; ?>  
    <input type="file" id="Profile" name="new_Profile" hidden onchange="validate_profile()">
    <label for="Profile" class="upload-btn">Choose Profile Image</label><br>
    <span class="error" id="profile_err"></span><br><br>

    <label for="FirstName">First Name:</label>
            <input type="text" id="FirstName" name="new_FirstName" placeholder="Enter Your FirstName" value="<?php echo $FirstName;?>" oninput="validate_firstname()">
            <span id="firstname_err" class="error"></span>
        <br><br>
        
        <label for="LastName">Last Name:</label>
            <input type="text" id="LastName" name="new_LastName" placeholder="Enter Your LastName" value="<?php echo $LastName;?>" oninput="validate_lastname()">
            <span id="lastname_err" class="error"></span>
        <br><br>

        <label for="DateOfBirth">Date of Birth:</label>
            <input type="date" id="DateOfBirth" name="new_DateOfBirth" placeholder="Enter Your DateOfBirth" value="<?php echo $DateOfBirth;?>" oninput="validate_dob()">
            <span id="dob_err" class="error"></span>
        <br><br>

        <label for="Gender">Gender:</label>
        <select id="Gender" name="new_Gender" onchange="validate_gender()">
            <option value="">Select your gender</option>
            <option value="Male" <?php if ($Gender == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($Gender == 'Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if ($Gender == 'Other') echo 'selected'; ?>>Other</option>
        </select>
        <span id="gender_err" class="error"></span>
        <br><br>

        <label for="ContactNumber">Contact Number:</label>
            <input type="text" id="ContactNumber" name="new_ContactNumber" placeholder="Enter Your ContactNumber" value="<?php echo $ContactNumber;?>" oninput="validate_contact()">
            <span id="contact_err" class="error"></span>
        <br><br>

        <label for="Email">Email:</label>
            <input type="email" id="Email" name="new_Email" placeholder="Enter Your Email" value="<?php echo $Email;?>" oninput="validate_email()">
            <span id="email_err" class="error"></span>
        <br><br>

        <label for="Address">Address:</label>
        <textarea id="Address" name="new_Address" placeholder="Enter your address" oninput="validate_address()"><?php echo $Address; ?></textarea>
        <span id="address_err" class="error"></span>
        <br><br>


        </div>

        <div class="academic_details">
        <h3>Academic Details</h3>

        <label for="CourseID">Course:</label>
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
                ?>
            </select>
            <span id="course_err" class="error"></span>
            <br/><br/>
        

    <label for="Department">Department:</label>
        <select name="Department" id="Department" onchange="validate_department()">
            <option value="">Select Department</option>
            <option value="it" <?php if($Department == 'it') echo 'selected'; ?>>IT</option>
            <option value="ict" <?php if($Department == 'ict') echo 'selected'; ?>>ICT</option>
            <option value="other" <?php if($Department == 'other') echo 'selected'; ?>>OTHER</option>
        </select>
        <span id="department_err" class="error"></span>
        <br><br>

        <label for="YearOfStudy">Year OF Study:</label>
        <input type="text" id="YearOfStudy" name="YearOfStudy" value="<?php echo date('Y'); ?>" readonly><br><br>

        <label for="EnrollmentYear">Enrollment Year:</label>
        <input type="text" id="EnrollmentYear" name="EnrollmentYear" value="<?php echo date('Y'); ?>" readonly><br><br>
        
        </div>


        <div class="account_details">
        <h3>Account Details</h3>
        <label for="Username">User Name:</label>
        <input type="text" id="Username" name="Username" placeholder="Create Your Username" value="<?php echo $Username;?>" oninput="validate_username()">
        <span id="username_err" class="error"><?php echo $usercheck;?></span><br><br>

        <label for="Password">Password:</label>
        <input type="password" id="Password" name="Password" placeholder="Create Your Password" value="<?php echo $Password;?>" oninput="validate_password()">
        <span id="password_err" class="error"></span><br><br>
        
        </div>

        <input type="submit" value="Submit"><br>
        <span class="error" id="error"></span>
        <p style='color:green;'><?php echo $success; ?></p>
        
    </form>
    </div>
    <script>
  $(document).ready(function () {
    $(".header-container").load("../header.php");
    $(".footer-container").load("../footer.php");
  });
</script>
</body>
</html>
