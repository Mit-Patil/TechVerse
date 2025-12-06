<?php
session_start();

// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit;
// }

$usercheck="";
$_SESSION['currentPhase'] = 3;


        include_once "../user/connection.php";
        require '../vendor/autoload.php'; 

        if (!isset($_SESSION['regform2']) || $_SESSION['regform2'] !== 1 || !$_SESSION['user_id']) {
            header("Location: ../academic/faculty_academic.php");
            exit;
        }

// if (isset($_GET['user_id']))  {
//     $user_id = $_GET['user_id'];
// }

// $sql = "SELECT user_id, first_name, last_name, email, phone_number FROM users WHERE user_id = $user_id";

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM faculty WHERE FacultyID = $user_id";

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
            $new_FirstName=$_POST['new_FirstName'];
            $new_LastName=$_POST['new_LastName'];
            $new_DateOfBirth=$_POST['new_DateOfBirth'];
            $new_Gender=$_POST['new_Gender'];
            $new_ContactNumber=$_POST['new_ContactNumber'];
            $new_Email=$_POST['new_Email'];
            $new_Address=$_POST['new_Address'];
            $new_Department=$_POST['new_Department'];
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
    // Proceed with the update only if username is unique
    $update_sql = "UPDATE faculty SET FirstName = '$new_FirstName', LastName = '$new_LastName',DateOfBirth='$new_DateOfBirth',Gender='$new_Gender',
                   ContactNumber='$new_ContactNumber',Email = '$new_Email',Address='$new_Address',Department='$new_Department',Profile = '$profile_path_db' WHERE FacultyID = $user_id";

    $update_sql1="UPDATE Users SET Username='$Username',Email='$new_Email',Password='$Password',RoleID='$RoleID' WHERE UserID='$UserID'";

    if ($conn->query($update_sql)) {
        if ($conn->query($update_sql1)) {
            $_SESSION['regform3'] = 1;
            include_once "../user/send_mail.php";
            sendConfirmationEmail($new_Email, $new_FirstName . ' ' . $new_LastName);   
            header("Location: " . $_SERVER['PHP_SELF']);
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
<div class="container">

<!-- Main Form Area -->
    <div class="left-panel">
    <?php include '../student module/reg_phase.php'; ?> 
    </div>

    <div class="form-container">

    <form action="" method="post" enctype="multipart/form-data" onsubmit="return validate_faculty_account();">
    <h1>Faculty Profile</h1>
    <h1>Welcome, <?php echo $FirstName . ' ' . $LastName; ?>!</h1>
    <!-- <p>Email: <?php echo $Email; ?></p> -->
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

        <label>Department:
            <input type="text" id="new_Department" name="new_Department" placeholder="Enter Your Department" value="<?php echo $Department;?>" oninput="validate_department_text()"><span id="department_err" class="error"></span>
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

        <label for="HireDate">Hire Date:</label>
        <input type="date" id="HireDate" name="HireDate" value="<?php echo date('Y-m-d'); ?>" readonly><br><br>
        
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
