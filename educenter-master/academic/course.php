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

  <script src="../user/other_validation.js"></script>

    <?php

        include_once "../user/connection.php";
        $CourseName=$CourseCode=$Department="";

        if($_SERVER['REQUEST_METHOD']=='POST')
        {

            $CourseName=$_POST['CourseName'];
            $CourseCode=$_POST['CourseCode'];
            $Department=$_POST['Department'];

            $check="SELECT * FROM Courses WHERE CourseName='$CourseName' AND CourseCode='$CourseCode' AND Department='$Department'";
            $result=$conn->query($check);

            if($result->num_rows===1)
            {
                echo "Course Already Exist";
            }
            else{

            $sql = "INSERT INTO Courses(CourseName,CourseCode,Department) VALUES('$CourseName','$CourseCode','$Department')";

            if($conn->query($sql)==TRUE)
            {
                echo "Inserted";

            }else{
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
    </div>

    <div class="form-container">
    <form action="" method="post" onsubmit="return validate_course()">
    <h1 class="header">Course Registration</h1>

    <label>Course Name:
        <input type="text" id="CourseName" name="CourseName" placeholder="Enter Your CourseName"
               value="<?php echo $CourseName;?>" oninput="validate_course_name()">
        <span id="coursename_err" class="error"></span>
    </label><br><br>

    <label>Course Code:
        <input type="number" id="CourseCode" name="CourseCode" placeholder="Enter Your CourseCode"
               value="<?php echo $CourseCode;?>" oninput="validate_course_code()">
        <span id="coursecode_err" class="error"></span>
    </label><br><br>

    <label>Department:
        <select name="Department" id="Department" onchange="validate_department()">
            <option value="">Select Department</option>
            <option value="it" <?php if ($Department == "it") echo "selected"; ?>>IT</option>
            <option value="ict" <?php if ($Department == "ict") echo "selected"; ?>>ICT</option>
            <option value="other" <?php if ($Department == "other") echo "selected"; ?>>OTHER</option>
        </select>
        <span id="department_err" class="error"></span>
    </label><br><br>

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