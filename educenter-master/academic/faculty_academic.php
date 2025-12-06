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

        $passing = '';
        $Education = '';
        $Qualifications = '';

        if (!isset($_SESSION['regform1']) || $_SESSION['regform1'] !== 1 || !$_SESSION['user_id']) {
            header("Location: ../faculty module/faculty.php");
            exit;
        }
        // if (isset($_GET['user_id']))  {
        //     $user_id = $_GET['user_id'];
        // }

        $user_id = $_SESSION['user_id'];
        
        $sql = "SELECT FacultyID,FirstName,LastName FROM Faculty WHERE FacultyID = $user_id";
        $result = $conn->query($sql);
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $FacultyID=$row['FacultyID'];
            $FirstName=$row['FirstName'];
            $LastName=$row['LastName'];

            $sql_enroll="SELECT FacultyID,passing,Education,Qualifications FROM Faculty_Academic  WHERE FacultyID=$FacultyID";
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
        } else {
            echo "Error fetching user data.";
            exit;
        }

        if($_SERVER['REQUEST_METHOD']=='POST')
        {

            $passing=$_POST['passing'];
            $Education=$_POST['Education'];
            $Qualifications=$_POST['Qualifications'];


            $sql_enroll="SELECT FacultyID,passing,Education,Qualifications FROM Faculty_Academic  WHERE FacultyID=$FacultyID";
            $result_enroll=$conn->query($sql_enroll);
            if ($result_enroll->num_rows > 0) {
               
                $update_Faculty_Academic="UPDATE Faculty_Academic SET passing='$passing',Education='$Education',Qualifications='$Qualifications' WHERE FacultyID='$FacultyID'";
                if($conn->query($update_Faculty_Academic)==TRUE)
                {
                        $_SESSION['user_id']=$FacultyID;
                        $_SESSION['regform2'] = 1;
                        header('Location:../faculty module/facultyup.php');
                }
                else{
                    echo "Error";
                }
            }
            else{

            $sql_Faculty_Academic="INSERT INTO Faculty_Academic(FacultyID,passing,Education,Qualifications) VALUES('$FacultyID','$passing','$Education','$Qualifications')";

            if($conn->query($sql_Faculty_Academic)==TRUE)
            {
                $_SESSION['user_id']=$FacultyID;
                $_SESSION['regform2'] = 1;
                header('Location:../faculty module/facultyup.php');
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

    <div class="left-panel">
        <?php include '../student module/reg_phase.php'; ?> 
    </div>

    <div class="form-container">


    <form action="" method="post" id="" enctype="multipart/form-data" onsubmit="return validate_faculty_academic()">
    <h1 class="header">Academic Details</h1>

    <label>First Name:
            <input type="text" id="FirstName" name="FirstName" placeholder="Enter Your FirstName" value="<?php echo $FirstName;?>" readonly>
        </label><br><br>
        
        <label>Last Name:
            <input type="text" id="LastName" name="LastName" placeholder="Enter Your LastName" value="<?php echo $LastName;?>" readonly>
        </label><br><br>

        <label>Are you proficient in more than one language?:
        <select name="passing" id="passing" onchange="validate_passing()">
            <option value="">Select Option</option>
            <option value="Yes" <?php if($passing == 'Yes') echo 'selected'; ?>>Yes</option>
            <option value="No" <?php if($passing == 'No') echo 'selected'; ?>>No</option>
        </select>
        <span id="passing_err" class="error"></span>
        </label><br><br>

        <label>Education:
        <select name="Education" id="Education" onchange="validate_education()">
            <option value="">Select Education</option>
            <option value="UGA" <?php if($Education == 'UGA') echo 'selected'; ?>>UGA</option>
            <option value="PGA" <?php if($Education == 'PGA') echo 'selected'; ?>>PGA</option>
            <option value="GRADUATED" <?php if($Education == 'GRADUATED') echo 'selected'; ?>>GRADUATED</option>
        </select>
        <span id="education_err" class="error"></span>
        </label><br><br>

        
    <label for="Qualifications">Qualifications</label>
    <input type="text" id="Qualifications" name="Qualifications" value="<?php echo $Qualifications; ?>" oninput="validate_qualifications()">
    <span id="qualification_err" class="error"></span><br><br>


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