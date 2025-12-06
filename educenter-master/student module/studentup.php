<?php
session_start();

$_SESSION['currentPhase'] = 3;

include_once "../user/connection.php";
require '../vendor/autoload.php';

if (!isset($_SESSION['regform2']) || $_SESSION['regform2'] !== 1 || !$_SESSION['user_id']) {
    header("Location: ../academic/academic_enrollment.php");
    exit;
}

// Initialize variables with default values
$FirstName = $LastName = $DateOfBirth = $Gender = $ContactNumber = $Email = $Address = $Username = $Password=$usercheck = "";

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Students WHERE StudentID = $user_id";

$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    
    // Handle empty or null values from the database
    $StudentID = $row['StudentID'];
    $FirstName = isset($row['FirstName']) && !empty(trim($row['FirstName'])) ? htmlspecialchars(trim($row['FirstName'])) : ""; 
    $LastName = isset($row['LastName']) && !empty(trim($row['LastName'])) ? htmlspecialchars(trim($row['LastName'])) : "";
    $DateOfBirth = !empty(trim($row['DateOfBirth'])) ? trim($row['DateOfBirth']) : "";
    $Gender = !empty(trim($row['Gender'])) ? trim($row['Gender']) : "";
    $ContactNumber = !empty(trim($row['ContactNumber'])) ? trim($row['ContactNumber']) : "";
    $Email = !empty(trim($row['Email'])) ? trim($row['Email']) : "";
    $Address = !empty(trim($row['Address'])) ? trim($row['Address']) : "";
    $Profile = !empty(trim($row['Profile'])) ? trim($row['Profile']) : "";

    $sql1 = "SELECT UserID, Username, Password FROM Users WHERE Email='$Email'";
    $result1 = $conn->query($sql1);
    
    if ($result1->num_rows === 1) {
        $row1 = $result1->fetch_assoc();
        $UserID = $row1['UserID'];
        $Username = !empty(trim($row1['Username'])) ? trim($row1['Username']) : ""; // Remove whitespace or set empty
$Password = isset($row1['Password']) && !empty(trim($row1['Password'])) ? htmlspecialchars(trim($row1['Password'])) : "";    
}
} else {
    echo "Error fetching user data.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_FirstName=$_POST['new_FirstName'];
            $new_LastName=$_POST['new_LastName'];
            $new_DateOfBirth=$_POST['new_DateOfBirth'];
            $new_Gender=$_POST['new_Gender'];
            $new_ContactNumber=$_POST['new_ContactNumber'];
            $new_Email=$_POST['new_Email'];
            $new_Address=$_POST['new_Address'];
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
            $new_filename = $user_id . "_" . $new_FirstName . "." . $ext;
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

            $RoleID=3;

// Check if the username already exists for another user
$checkUsernameSQL = "SELECT * FROM Users WHERE Username = '$Username' AND UserID != '$UserID'";
$checkResult = $conn->query($checkUsernameSQL);

if ($checkResult->num_rows > 0) {
    $usercheck="This UserName Already Exist!! Try Unique Username";
} else {

    $update_sql = "UPDATE Students SET FirstName = '$new_FirstName', LastName = '$new_LastName',DateOfBirth='$new_DateOfBirth',Gender='$new_Gender',
                   ContactNumber='$new_ContactNumber',Email = '$new_Email',Address='$new_Address',Profile = '$profilePath' WHERE StudentID = $user_id";

    $update_sql1="UPDATE Users SET Username='$Username',Email='$new_Email',Password='$Password',RoleID='$RoleID' WHERE UserID='$UserID'";

    if ($conn->query($update_sql)) {
        if($conn->query($update_sql1))
        {
            //Student Fees Data Insert--->
            $flag=1;
            $CourseID="";
            $course_details="SELECT CourseID FROM Enrollments WHERE StudentID=$StudentID";
            $result_course=$conn->query($course_details);
            
            if($result_course->num_rows==1)
            {
                while($row=$result_course->fetch_assoc())
                {
                    $CourseID=$row['CourseID'];
                }
            }
            else{
                echo "Error To Fetch Course Details";
                $flag=0;
            
            }
            
            
            $get_fess_details="SELECT FeeAmount FROM FeeStructure WHERE CourseID=$CourseID";
            $result_fess=$conn->query($get_fess_details);
            $FeeAmount="";
            
            
            if($result_fess->num_rows>0)
            {
                while($row=$result_fess->fetch_assoc())
                {
                    $FeeAmount=$row['FeeAmount'];
                }
            }
            else{
            
                echo "Error To Fetch Fees Details";
                $flag=0;
            }
            
            
            
            
            $get_status="SELECT PaymentStatus FROM StudentFees WHERE StudentID=$StudentID";
            $result_studentfess=$conn->query($get_status);
            
            $PaymentStatus="";
            if($result_studentfess->num_rows==1)
            {
                while($row=$result_studentfess->fetch_assoc())
                {
                    $PaymentStatus=$row['PaymentStatus'];
                }
            }
            else if($result_studentfess->num_rows<1){
                $PaymentStatus="Pending";
                $StudentFees="INSERT INTO StudentFees(StudentID,CourseID,FeeAmount,PaymentStatus) VALUES('$StudentID','$CourseID','$FeeAmount','$PaymentStatus')";
            
                if($conn->query($StudentFees)==TRUE)
                {
                    echo "Inserted";
                }
                else{
                        echo "Error To Fetch Or Insert StudentFees Details";
                        $flag=0;
                }
            }
            else{
            
                echo "Error To Fetch Or Insert StudentFees Details";
                $flag=0;
            }
            

            $_SESSION['regform3'] = 1;
            include_once "../user/send_mail.php";//mail For Succesfull Registration-->
            sendConfirmationEmail($new_Email, $new_FirstName . ' ' . $new_LastName);            
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh page to apply session change
            exit;
        }
    } else {
        echo "Error updating details.";
    }
}
    $conn->close();

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
  <!-- Form CSS -->
  <link rel="stylesheet" href="../css/form.css">  
  <!-- Themify Icons for Header -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css" />
  
  <!-- JQuery for Loading Header/Footer -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Validation script -->
    <script src="../user/reg_validation.js"></script>
</head>
<body>
<!-- Header -->
<div class="header-container"></div>

<!-- Main Form Area -->
<div class="container">

    <div class="left-panel">
        <?php include '../student module/reg_phase.php'; ?> 
    </div>

    <div class="form-container">

    <form action="" method="post" enctype="multipart/form-data" onsubmit="return validate_student_account();">
            <h1>Welcome, <?php echo $FirstName . ' ' . $LastName; ?>!</h1>
    <!-- <p>Email: <?php echo $Email; ?></p> -->

    
    <h1>Student Profile</h1>
        <label>First Name:
            <input type="text" id="FirstName" name="new_FirstName" placeholder="Enter Your FirstName" value="<?php echo $FirstName;?>" oninput="validate_firstname()">
            <span id="firstname_err" class="error"></span>
        </label><br><br>
        
        <label>Last Name:
            <input type="text" id="LastName" name="new_LastName" placeholder="Enter Your LastName" value="<?php echo $LastName;?>" oninput="validate_lastname()">
            <span id="lastname_err" class="error"></span>
        </label><br><br>

        <label>Date of Birth:
            <input type="date" id="DateOfBirth" name="new_DateOfBirth" placeholder="Enter Your DateOfBirth" value="<?php echo $DateOfBirth;?>" oninput="validate_dob()">
            <span id="dob_err" class="error"></span>
        </label><br><br>

        <label>Gender:
        <select id="Gender" name="new_Gender" onchange="validate_gender()">
            <option value="">Select your gender</option>
            <option value="Male" <?php if ($Gender == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($Gender == 'Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if ($Gender == 'Other') echo 'selected'; ?>>Other</option>
        </select>
        <span id="gender_err" class="error"></span>
        </label><br><br>

        <label>Contact Number:
            <input type="text" id="ContactNumber" name="new_ContactNumber" placeholder="Enter Your ContactNumber" value="<?php echo $ContactNumber;?>" oninput="validate_contact()">
            <span id="contact_err" class="error"></span>
        </label><br><br>

        <label>Email:
            <input type="email" id="Email" name="new_Email" placeholder="Enter Your Email" value="<?php echo $Email;?>" oninput="validate_email()">
            <span id="email_err" class="error"></span>
        </label><br><br>

        <label>Address:
        <textarea id="Address" name="new_Address" placeholder="Enter your address" oninput="validate_address()"><?php echo $Address; ?></textarea>
        <span id="address_err" class="error"></span>
        </label><br><br>


        <?php if (!empty($Profile)) : ?>
        <label>Current Profile Image:</label><br>
        <img src="<?php echo $Profile; ?>" alt="Profile Image" style="width:120px; height:120px; border-radius:10px;"><br><br>
        <?php endif; ?>  
        <label>Profile:
        <input type="file" id="Profile" name="new_Profile" placeholder="Select Your Profile Image">
        <span id="profile_err" class="error"></span>
        </label><br><br>

        <label>User Name:
        <input type="text" id="Username" name="Username" placeholder="Create Your Username" value="<?php echo $Username;?>" oninput="validate_username()">
        <span id="username_err" class="error"><?php echo $usercheck;?></span></label><br><br>

        <label>Password:
        <input type="password" id="Password" name="Password" placeholder="Create Your Password" value="<?php echo $Password;?>" oninput="validate_password()">
        <span id="password_err" class="error"></span></label><br><br>
        
        <input type="submit" value="Submit"><br>
        <span class="error" id="error"></span>

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
