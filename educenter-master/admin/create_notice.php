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

session_start();



        include_once "../user/connection.php";
        include_once "../user/send_mail.php";

        $title=$description=$notice_date="";

        if (isset($_SESSION['FirstName']) && ($_SESSION['LastName']) && isset($_SESSION['table'])) {

            $FirstName=$_SESSION['FirstName'];
            $LastName=$_SESSION['LastName'];
            $table=$_SESSION['table'];

            $publisher_name= $FirstName . " " . $LastName . "(" . $table . ")";
        }
        else{
            $publisher_name="";
        }
        if($_SERVER['REQUEST_METHOD']=='POST')
        {

            $title=$_POST['title'];
            $description=$_POST['description'];
            $notice_date=$_POST['notice_date'];

            $sql = "INSERT INTO Notices(title,description,notice_date,publisher_name) VALUES('$title','$description','$notice_date','$publisher_name')";

            if($conn->query($sql)==TRUE)
            {
                echo "<p style='color:green;'>✅ Notice Added Successfully.</p>";

                // Send notice to all students via email
                sendNoticeToAllUsers($conn, $title, $description, $notice_date, $publisher_name);
        
            }else{
                echo "Error";
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
    <form action="" method="post" onsubmit="return validate_notice_form()">
    <h1 class="header">Create Notification Or Annoucement</h1>

            <label>Title:
                <input type="text" id="title" name="title" placeholder="Enter Notification Title" value="<?php echo $title;?>" oninput="validate_title()">
                <span id="titleError" class="error"></span>
            </label><br><br>

            <label>Description:
                <textarea id="description" name="description" placeholder="Enter Notification description" oninput="validate_description()"><?php echo $description; ?></textarea>
                <span id="descriptionError" class="error"></span>
            </label><br><br>

            <label>Date:
                <input type="date" id="notice_date" name="notice_date" placeholder="Enter Your notice date" value="<?php echo $notice_date;?>" oninput="validate_notice_date()">
                <span id="dateError" class="error"></span>
            </label><br>

            <input type="submit" value="Submit"><br>
            <span class="error" id="error"></div>
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