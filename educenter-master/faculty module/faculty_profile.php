<?php
session_start();

        include_once "../user/connection.php";

        if (!isset($_SESSION['email'])) {
            header("Location: ../user/login.php");
            exit;
        }


        $passing = '';
        $Education = '';
        $Qualifications = '';
        $usercheck=$success="";
$email = $_SESSION['email'];
$sql = "SELECT * FROM faculty WHERE Email = '$email'";//Faculty Personal Details---

$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $FacultyID=$row['FacultyID'];
    $FirstName=$row['FirstName'];
    $LastName=$row['LastName'];
    $DateOfBirth=$row['DateOfBirth'];
    $Gender=$row['Gender'];
    $ContactNumber=$row['ContactNumber'];
    $Email=$row['Email'];
    $Address=$row['Address'];
    $Department=$row['Department'];
    $Profile = $row['Profile'];


    $sql_enroll="SELECT FacultyID,passing,Education,Qualifications FROM Faculty_Academic  WHERE FacultyID='$FacultyID'";
            $result_enroll=$conn->query($sql_enroll);
            if ($result_enroll->num_rows === 1) {
                $row_enroll = $result_enroll->fetch_assoc();
                $FacultyID=$row_enroll['FacultyID'];
                $passing=$row_enroll['passing'];
                $Education=$row_enroll['Education'];
                $Qualifications=$row_enroll['Qualifications'];
            }
            else{
                $row_enroll = null;
            }

    $sql1="SELECT UserID,Username,Password from Users where Email='$Email'";
    $result1 = $conn->query($sql1);
    
    if($result1->num_rows === 1)
    {
        $row1 = $result1->fetch_assoc();
        $UserID=$row1['UserID'];
        $Username=$row1['Username'];
        $Password=$row1['Password'];
    }

} else {
    echo "Error fetching user data.";
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
            $new_Department=$_POST['new_Department'];

            //academic details
            $passing=$_POST['passing'];
            $Education=$_POST['Education'];
            $Qualifications=$_POST['Qualifications'];

            //Account Details
            $Username=$_POST['Username'];
            $Password=$_POST['Password'];

            $profile_path_db = $Profile; // Set to existing image by default

            if (isset($_FILES['new_Profile']) && $_FILES['new_Profile']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $file_type = $_FILES['new_Profile']['type'];
        
                if (in_array($file_type, $allowed_types)) {
                    $upload_dir = '../profile/Faculty_profile/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
        
                    $ext = pathinfo($_FILES['new_Profile']['name'], PATHINFO_EXTENSION);
                    $new_file_name = $FacultyID . "_" . $new_FirstName . "." . $ext;
                    $target_file = $upload_dir . $new_file_name;
        
                    if (move_uploaded_file($_FILES['new_Profile']['tmp_name'], $target_file)) {
                        $profile_path_db = $target_file; // Update with new path
                        echo "Profile image uploaded successfully: $profile_path_db"; // Debugging message
                    } else {
                        echo "Failed to upload new profile image. Keeping old image.";
                    }
                } else {
                    echo "Invalid file type. Keeping old image.";
                }
            }

                        

            $RoleID=2;

// Check if the username already exists for another user
$checkUsernameSQL = "SELECT * FROM Users WHERE Username = '$Username' AND UserID != '$UserID'";
$checkResult = $conn->query($checkUsernameSQL);

if ($checkResult->num_rows > 0) {
    $usercheck="This UserName Already Exist!! Try Unique Username";
} else {
    $update_faculty = "UPDATE faculty SET FirstName = '$new_FirstName', LastName = '$new_LastName',DateOfBirth='$new_DateOfBirth',Gender='$new_Gender',
                   ContactNumber='$new_ContactNumber',Email = '$new_Email',Address='$new_Address',Department='$new_Department',Profile = '$profile_path_db' WHERE FacultyID = '$FacultyID'";

$update_Faculty_Academic="UPDATE Faculty_Academic SET passing='$passing',Education='$Education',Qualifications='$Qualifications' WHERE FacultyID='$FacultyID'";


    $update_user="UPDATE Users SET Username='$Username',Email='$new_Email',Password='$Password',RoleID='$RoleID' WHERE UserID='$UserID'";

    if ($conn->query($update_faculty) && $conn->query($update_Faculty_Academic) && $conn->query($update_user)) {
        $success="Details Updated ✅ ";

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
    <title>Document</title>
  <!-- Form CSS -->
  <link rel="stylesheet" href="../css/profile.css">  

  <!-- Themify Icons for Header -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css" />
  
  <!-- JQuery for Loading Header/Footer -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script></head>

  <script src="../user/reg_validation.js"></script>
<body>
<!-- Header -->
<div class="header-container"></div>

<!-- Main Form Area -->
<div class="container">

<form action="" method="post" enctype="multipart/form-data" onsubmit="return validate_faculty_profile_form()">
<h1>Faculty Profile</h1>
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

      <label for="Department">Department:</label>
        <input type="text" id ="Department" name="new_Department" value="<?php echo $Department; ?>" oninput="validate_department_text()"><span id="department_err" class="error"></span>
      <br><br>
        </div>

        <div class="academic_details">
        <h3>Academic Details</h3>

        <label for="passing">Are you proficient in more than one language?:</label>
        <select name="passing" id="passing" onchange="validate_passing()">
            <option value="">Select Option</option>
            <option value="Yes" <?php if($passing == 'Yes') echo 'selected'; ?>>Yes</option>
            <option value="No" <?php if($passing == 'No') echo 'selected'; ?>>No</option>
        </select>
        <span id="passing_err" class="error"></span>
        <br><br>

        <label for="Education">Education:</label>
        <select name="Education" id="Education" onchange="validate_education()">
            <option value="">Select Education</option>
            <option value="UGA" <?php if($Education == 'UGA') echo 'selected'; ?>>UGA</option>
            <option value="PGA" <?php if($Education == 'PGA') echo 'selected'; ?>>PGA</option>
            <option value="GRADUATED" <?php if($Education == 'GRADUATED') echo 'selected'; ?>>GRADUATED</option>
        </select>
        <span id="education_err" class="error"></span>
        <br><br>

        
    <label for="Qualifications">Qualifications:</label>
    <input type="text" id="Qualifications" name="Qualifications" value="<?php echo $Qualifications; ?>" oninput="validate_qualifications()">
    <span id="qualification_err" class="error"></span><br><br>
        
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
