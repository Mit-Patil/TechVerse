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
        $CourseID=$SubjectName="";

        if($_SERVER['REQUEST_METHOD']=='POST')
        {

            $CourseID=$_POST['CourseID'];
            $SubjectName=$_POST['SubjectName'];

            $check="SELECT * FROM Subjects WHERE CourseID='$CourseID' AND SubjectName='$SubjectName'";
            $result=$conn->query($check);

            if($result->num_rows===1)
            {
                echo "Subject Already In Course";
                header("Location: " . $_SERVER['PHP_SELF']); // This will refresh the page and keep the data intact
                exit;
            }
            else{
                $sql = "INSERT INTO Subjects(CourseID,SubjectName) VALUES('$CourseID','$SubjectName')";

                if($conn->query($sql)==TRUE)
                {
                    echo "Inserted";
                    header("Location: " . $_SERVER['PHP_SELF']); // This will refresh the page and keep the data intact
                    exit;
    
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
    <form action="" method="post" id="" onsubmit="return validate_add_subject_form()">
    <h1 class="header">Add Subject</h1>

    <label>Course:
            <select name="CourseID" id="CourseID" onchange="validate_course()">
                <option value="">Select Course</option>
                <?php
                    include_once "../user/connection.php";

                    $sql="SELECT CourseID,CourseName FROM Courses";
                    $result=$conn->query($sql);

                    if($result->num_rows>0)
                    {
                        while($row=$result->fetch_assoc())
                        {
                            echo "<option value='" . $row['CourseID'] . "'";
                            if ($row['CourseID'] == $CourseID) echo " selected";
                            echo ">" . $row['CourseName'] . "</option>";
                            }
                    }
                    else{
                            echo "<option value=''>No courses available</option>";
                    }
                    $conn->close();
                ?>
            </select>
            <span id="course_err" class="error"></span></label><br/><br/>
        
        <label>SubjectName:
            <Input type="text" id="SubjectName" name="SubjectName" placeholder="Enter SubjectName" value="<?php echo $SubjectName;?>" oninput="validate_subject_name()"></Input><span id="subjectname_err" class="error"></span>
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