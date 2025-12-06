

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
<?php
        
        include_once '../user/connection.php';
    
        session_start();
      
        $Username=$Password="";
        $userErr=$passErr=$captchaErr=$someErr="";
       // $user="";
          
        if(!isset($_SESSION['captcha']))
        {
            $_SESSION['captcha']=rand(1000,9999);
        }
    
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
        // $userErr=$passErr=$captchaErr=$someErr="";
          
            $Username=$_POST['Username'];
            $Password=$_POST['Password'];
            $captchaInput=$_POST['captcha'];
        
                if(empty($Username))
                {
                    $userErr="Please enter your username or Email";
                }
    
                if(empty($Password))
                {
                    $passErr="Please enter your password";
                }
                
            if($captchaInput!==$_SESSION['captcha'])
            {
                $captchaErr="INVALID CAPTCHA !!! PLEASE TRY AGAIN !!!";
            }
                if($userErr=='' && $passErr=='' && $captchaErr=='')
                {
                    $check_user="SELECT * FROM users WHERE Username='$Username' OR Email='$Username'";
                    $result_found=$conn->query($check_user);

                    if($result_found->num_rows===1)
                    {

                        $sql="SELECT * FROM users WHERE (Username='$Username' OR Email='$Username') AND Password='$Password'";
                        $result=$conn->query($sql);
        
                        //$user=$_POST["user"];
        
                        if($result->num_rows==1)
                        {
        
                            $row=mysqli_fetch_assoc($result);
                            $RoleID=$row['RoleID'];
    
                            if($RoleID==1)
                            {
        
                                $sql="SELECT Email FROM Users Where Username='$Username' OR Email='$Username'";
                                $result=$conn->query($sql);
                                if($result->num_rows===1)
                                {
                                    $row=$result->fetch_assoc();
                                    $email=$row['Email'];
                                    $_SESSION['email']=$email;
                                    $_SESSION['table']="Admin";
                                    header("Location:../admin/admin_dashboard.php");
                                    exit();
                                }
                                else{
                                    echo"error";
                                }
        
                            exit();
                            }
                            else if($RoleID==2){
                                
                                $sql="SELECT Email FROM Users Where Username='$Username' OR Email='$Username'";
                                $result=$conn->query($sql);
                                if($result->num_rows===1)
                                {
                                    $row=$result->fetch_assoc();
                                    $email=$row['Email'];
                                    $_SESSION['email']=$email;
                                    $_SESSION['table']="Faculty";
                                    header("Location:../faculty module/faculty_dashboard.php");
                                    exit();
                                }
                                else{
                                    echo"error";
                                }
        
                            exit();
                            }
                            else if($RoleID==3){
                                $sql="SELECT Email FROM Users Where Username='$Username' OR Email='$Username'";
                                $result=$conn->query($sql);
                                if($result->num_rows===1)
                                {
                                    $row=$result->fetch_assoc();
                                    $email=$row['Email'];
                                    $_SESSION['email']=$email;
                                    $_SESSION['table']="Students";
                                    header("Location:../student module/dashboard.php");
                                    exit();
                                }
                                else{
                                    echo"error";
                                }
            
                                }
        
                        }
                        else{
                            $someErr="Invalid ID or PASSWORD";
                        }
                    }else{
                        $someErr="User not found !!!!!";
                    }

                }
            }
            unset($_SESSION['captcha']);
            $conn->close();
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
    <form class="loginform" method="POST" enctype="multipart/form-data">
    <h1 class="header">LOGIN</h1>

        <label>USERNAME:
        <Input type="text" id="Username" name="Username" placeholder="Enter Your Username Or Email" value="<?php echo $Username;?>"></Input>
        </label>
        <span class="error"><?php echo $userErr; ?></span><br>
        

        
        <label>PASSWORD:
        <input type="Password" id="Password" name="Password" placeholder="Enter Your Password" value="<?php echo $Password;?>">
        </label>
        <span class="error"><?php echo $passErr; ?></span><br>
        
        

        <!-- <label>USER:
        <select id="user" name="user" required>
            <option value="">Select your user</option>
            <option value="admin">ADMIN</option>
            <option value="faculty">FACULTY</option>
            <option value="student">STUDENT</option>
        </select>
        </label><br><br> -->

       

        <label for="captcha">CAPTCHA:
        <br><br>
        <img src="../user/captcha.php" alt="CAPTCHA Image"><br>
        <input type="text" id="captcha" name="captcha" placeholder="Enter Captcha Code:"></label>
        <span class="error"><?php echo $captchaErr; ?></span>

        

        <input type="submit" name="submit" value="submit"><br>
        <span class="error"><?php echo $someErr; ?></span>
        <a href="forgot_password.php" style="font-size: 14px; color: blue;">Forgot Password?</a>

        

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

