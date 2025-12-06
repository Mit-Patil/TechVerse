<?php
session_start();

        include_once "../user/connection.php";

        if (!isset($_SESSION['email'])) {
            header("Location: ../user/login.php");
            exit;
        }

$usercheck=$success="";
$email = $_SESSION['email'];
$sql = "SELECT * FROM Admin WHERE Email = '$email'";//Admin Personal Details---

$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $AdminID=$row['AdminID'];
    $FirstName=$row['FirstName'];
    $LastName=$row['LastName'];
    $Gender=$row['Gender'];
    $ContactNumber=$row['ContactNumber'];
    $Email=$row['Email'];
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
    //personal details
            $new_FirstName=$_POST['new_FirstName'];
            $new_LastName=$_POST['new_LastName'];
            $new_Gender=$_POST['new_Gender'];
            $new_ContactNumber=$_POST['new_ContactNumber'];
            $new_Email=$_POST['new_Email'];

            //Account Details
            $Username=$_POST['Username'];
            $Password=$_POST['Password'];

            $profile_path_db = $Profile; // fallback set directly

            if (isset($_FILES['new_Profile']) && $_FILES['new_Profile']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $file_type = $_FILES['new_Profile']['type'];
            
                if (in_array($file_type, $allowed_types)) {
                    $upload_dir = '../profile/admin_profile/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
            
                    $ext = pathinfo($_FILES['new_Profile']['name'], PATHINFO_EXTENSION);
                    $new_file_name = $AdminID . "_" . $new_FirstName . "." . $ext;
                    $target_file = $upload_dir . $new_file_name;
            
                    if (move_uploaded_file($_FILES['new_Profile']['tmp_name'], $target_file)) {
                        $profile_path_db = $target_file;
                    } else {
                        echo "Failed to upload new profile image. Keeping old image.";
                    }
                } else {
                    echo "Invalid file type. Keeping old image.";
                }
            }
            
                        

            $RoleID=1;

// Check if the username already exists for another user
$checkUsernameSQL = "SELECT * FROM Users WHERE Username = '$Username' AND UserID != '$UserID'";
$checkResult = $conn->query($checkUsernameSQL);

if ($checkResult->num_rows > 0) {
    $usercheck="This UserName Already Exist!! Try Unique Username";
} else {
    $update_admin = "UPDATE Admin SET FirstName = '$new_FirstName', LastName = '$new_LastName',Gender='$new_Gender',
                   ContactNumber='$new_ContactNumber',Email = '$new_Email',Profile = '$profile_path_db' WHERE AdminID = '$AdminID'";


    $update_user="UPDATE Users SET Username='$Username',Email='$new_Email',Password='$Password',RoleID='$RoleID' WHERE UserID='$UserID'";

    if ($conn->query($update_admin) &&  $conn->query($update_user)) {
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../user/reg_validation.js"></script>
</head>

<body>
<!-- Header -->
<div class="header-container"></div>

<!-- Main Form Area -->
<div class="container">

    <!-- Only the <form> section shown below (rest is unchanged) -->
    <form action="" method="post" enctype="multipart/form-data" onsubmit="return validate_admin_profile_form()">
        <h1>Admin Profile</h1>

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
            <span class="error" id="firstname_err"></span><br><br>
            
            <label for="LastName">Last Name:</label>
            <input type="text" id="LastName" name="new_LastName" placeholder="Enter Your LastName" value="<?php echo $LastName;?>" oninput="validate_lastname()">
            <span class="error" id="lastname_err"></span><br><br>

            <label for="Gender">Gender:</label>
            <select id="Gender" name="new_Gender" onchange="validate_gender()">
                <option value="">Select your gender</option>
                <option value="Male" <?php if ($Gender == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($Gender == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($Gender == 'Other') echo 'selected'; ?>>Other</option>
            </select>
            <span class="error" id="gender_err"></span><br><br>

            <label for="ContactNumber">Contact Number:</label>
            <input type="text" id="ContactNumber" name="new_ContactNumber" placeholder="Enter Your ContactNumber" value="<?php echo $ContactNumber;?>" oninput="validate_contact()">
            <span class="error" id="contact_err"></span><br><br>

            <label for="Email">Email:</label>
            <input type="email" id="Email" name="new_Email" placeholder="Enter Your Email" value="<?php echo $Email;?>" oninput="validate_email()">
            <span class="error" id="email_err"></span><br><br>

        </div>

        <div class="account_details">
            <h3>Account Details</h3>
            <label for="Username">User Name:</label>
            <input type="text" id="Username" name="Username" placeholder="Create Your Username" value="<?php echo $Username;?>" oninput="validate_username()">
            <span class="error" id="username_err"><?php echo $usercheck;?></span><br><br>

            <label for="Password">Password:</label>
            <input type="password" id="Password" name="Password" placeholder="Create Your Password" value="<?php echo $Password;?>" oninput="validate_password()">
            <span class="error" id="password_err"></span><br><br>
        </div>

        <input type="submit" value="Submit">
        <div class="error" id="error"></div>
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
