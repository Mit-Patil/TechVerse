<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Faculty Registration Form</title>
  
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
  $_SESSION['currentPhase'] = 1;
  $_SESSION['regform1'] = "";
  $_SESSION['regform2'] = "";
  $_SESSION['regform3'] = "";

  include_once '../user/connection.php';

  $FirstName = $LastName = $DateOfBirth = $Gender = $ContactNumber = $Email = $Address = $Department = "";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $FirstName = $_POST['FirstName'];
      $LastName = $_POST['LastName'];
      $DateOfBirth = $_POST['DateOfBirth'];
      $Gender = $_POST['Gender'];
      $ContactNumber = $_POST['ContactNumber'];
      $Email = $_POST['Email'];
      $Address = $_POST['Address'];
      $Department = $_POST['Department'];
      $HireDate = $_POST['HireDate'];
      $RoleID = 2;

      // Check for existing Faculty
      $sql_faculty = "SELECT FacultyID FROM Faculty WHERE Email='$Email'";
      $result_faculty = $conn->query($sql_faculty);

      if ($result_faculty->num_rows == 1) {
          $row = $result_faculty->fetch_assoc();
          $FacultyID = $row['FacultyID'];
          $_SESSION['user_id'] = $FacultyID;
          $_SESSION['regform1'] = 1;
          header('Location:../academic/faculty_academic.php');
          exit();
      } else {
          $sql = "INSERT INTO Faculty (FirstName, LastName, DateOfBirth, Gender, ContactNumber, Email, Address, Department, HireDate)
                  VALUES ('$FirstName', '$LastName', '$DateOfBirth', '$Gender', '$ContactNumber', '$Email', '$Address', '$Department', '$HireDate')";

          if ($conn->query($sql) === TRUE) {
              $sql1 = "INSERT INTO Users(Email, RoleID) VALUES('$Email', '$RoleID')";
              if ($conn->query($sql1) === TRUE) {
                  $sql_faculty = "SELECT FacultyID FROM Faculty WHERE Email='$Email'";
                  $result_faculty = $conn->query($sql_faculty);
                  if ($result_faculty->num_rows == 1) {
                      $row = $result_faculty->fetch_assoc();
                      $FacultyID = $row['FacultyID'];

                     // Profile Upload Handling
                        $profile_dir = "../profile/";
                        $target_dir = $profile_dir . "Faculty_profile/";

                    // Create directories if not exist
                        if (!is_dir($profile_dir)) {
                            mkdir($profile_dir, 0755, true);
                        }
                        if (!is_dir($target_dir)) {
                            mkdir($target_dir, 0755, true);
                        }

                    // Get file info
                        $profile_name = $_FILES['Profile']['name'];
                        $tmp_name = $_FILES['Profile']['tmp_name'];
                        $ext = pathinfo($profile_name, PATHINFO_EXTENSION);

                    // Check for upload errors
                        if ($_FILES['Profile']['error'] !== UPLOAD_ERR_OK) {
                        echo "Error during file upload.";
                        exit();
                    }

                    // Validate file type (allow only images)
                        $allowed_extensions = ['jpg', 'jpeg', 'png'];
                        if (!in_array(strtolower($ext), $allowed_extensions)) {
                            echo "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
                            exit();
                        }

                    // Rename file: FacultyID_FirstName.ext (sanitize FirstName)
                        $new_file_name = $FacultyID . "_" . preg_replace("/[^a-zA-Z0-9]/", "", $FirstName) . "." . $ext;
                        $target_file = $target_dir . $new_file_name;

                    // Move file
                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $profile_path_db = $target_file; // full relative path

                    // Update Profile path in DB
                        $update_profile = "UPDATE Faculty SET Profile='$profile_path_db' WHERE FacultyID=$FacultyID";
                    if (!$conn->query($update_profile)) {
                        echo "Error updating profile image in database.";
                        exit();
                    }
                    } else {
                    echo "Error uploading profile image.";
                        exit();
                    }
            

                      $_SESSION['user_id'] = $FacultyID;
                      $_SESSION['regform1'] = 1;
                      header('Location:../academic/faculty_academic.php');
                      exit();
                  }
              } else {
                  echo "Error inserting into Users table.";
              }
          } else {
              echo "Error inserting into Faculty table.";
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
  <div class="left-panel">
    <?php include '../student module/reg_phase.php'; ?>
  </div>

  <div class="form-container">
  <form action="" method="post" id="" enctype="multipart/form-data" onsubmit="return validate_faculty_feild()">
  <h1>Faculty Registration Form</h1>

      <label>First Name:
            <Input type="text" id="FirstName" name="FirstName" placeholder="Enter Your FirstName" value="<?php echo $FirstName;?>" oninput="validate_firstname()"></Input><span id="firstname_err" class="error"></span>
        </label><br><br>
        
        <label>Last Name:
            <Input type="text" id="LastName" name="LastName" placeholder="Enter Your LastName" value="<?php echo $LastName;?>" oninput="validate_lastname()"></Input><span id="lastname_err" class="error"></span>
        </label><br><br>

        <label>Date of Birth:
            <Input type="date" id="DateOfBirth" name="DateOfBirth" placeholder="Enter Your DateOfBirth" value="<?php echo $DateOfBirth;?>" oninput="validate_dob()"></Input><span id="dob_err" class="error"></span>
        </label><br><br>

        <label>Gender:
        <select id="Gender" name="Gender" onchange="validate_gender()">
            <option value="">Select your gender</option>
            <option value="Male" <?php if ($Gender == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($Gender == 'Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if ($Gender == 'Other') echo 'selected'; ?>>Other</option>
        </select><span id="gender_err" class="error"></span>
        </label><br><br>

        <label>Contact Number:
            <Input type="text" id="ContactNumber" name="ContactNumber" placeholder="Enter Your ContactNumber" value="<?php echo $ContactNumber;?>" oninput="validate_contact()"></Input><span id="contact_err" class="error"></span>
        </label><br><br>

        <label>Email:
            <Input type="text" id="Email" name="Email" placeholder="Enter Your Email" value="<?php echo $Email;?>" oninput="validate_email()"></Input><span id="email_err" class="error"></span>
        </label><br><br>

        <label>Address:
        <textarea id="Address" name="Address" placeholder="Enter your address" value="<?php echo $Address; ?>" oninput="validate_address()"></textarea><span id="address_err" class="error"></span>
        </label><br><br>

      <label>Department:
        <input type="text" id ="Department" name="Department" value="<?php echo $Department; ?>" oninput="validate_department_text()"><span id="department_err" class="error"></span>
      </label><br><br>

      <label>Profile:
        <input type="file" id="Profile" name="Profile" placeholder="Select Your Profile Image" onchange="validate_profile()"><span id="profile_err" class="error"></span>
        </label><br><br>

      <label>Hire Date:
        <input type="date" name="HireDate" value="<?php echo date('Y-m-d'); ?>" readonly>
      </label>

      <input type="submit" value="Submit"><br>
      <span id="error" class="error"></span>
      </form>
  </div>
</div>

<!-- Footer -->
<!-- <div class="footer-container"></div> -->

<!-- Load Header and Footer with JQuery -->
<script>
  $(document).ready(function () {
    $(".header-container").load("../header.php");
    $(".footer-container").load("../footer.php");
  });
</script>

</body>
</html>