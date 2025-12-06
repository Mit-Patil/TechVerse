<?php
session_start();
include_once "../educenter-master/user/connection.php";

$sql_faculty="SELECT * FROM Faculty LIMIT 1";
$faculty_details=$conn->query($sql_faculty);
$FirstName = $LastName = $Email = $Profile = null;

if (isset($_SESSION['email']) && isset($_SESSION['table'])) {
    //header("Location: ../user/login.php");
    //exit;

$email=$_SESSION['email'];
$table=$_SESSION['table'];
$sql_user="SELECT * FROM $table WHERE Email='$email'";
$user_details=$conn->query($sql_user);

if($user_details->num_rows===1)
{
  $row=$user_details->fetch_assoc();

  $FirstName=$row['FirstName'];
  $LastName=$row['LastName'];
  $Email=$row['Email'];
  $Profile = $row['Profile'];

}

}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
  <meta charset="utf-8">
  <title>TechVerse</title>

  <!-- mobile responsive meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
  <!-- ** Plugins Needed for the Project ** -->
  <!-- Bootstrap -->
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <!-- slick slider -->
  <link rel="stylesheet" href="plugins/slick/slick.css">
  <!-- themefy-icon -->
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <!-- animation css -->
  <link rel="stylesheet" href="plugins/animate/animate.css">
  <!-- aos -->
  <link rel="stylesheet" href="plugins/aos/aos.css">
  <!-- venobox popup -->
  <link rel="stylesheet" href="plugins/venobox/venobox.css">

  <!-- Main Stylesheet -->
  <link href="css/style.css" rel="stylesheet">
  
  <!--Favicon-->
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">

</head>

<body>
  

<!-- header -->
<header class="fixed-top header">
  <!-- top header -->
  <div class="top-header py-2 bg-white">
    <div class="container">
      <div class="row no-gutters">
        <div class="col-lg-4 text-center text-lg-left">
          <a class="text-color mr-3" href="callto:+443003030266"><strong>CALL</strong>+00 111 222 3334</a>
          <ul class="list-inline d-inline">
            <li class="list-inline-item mx-0"><a class="d-inline-block p-2 text-color" href="#"><i class="ti-facebook"></i></a></li>
            <li class="list-inline-item mx-0"><a class="d-inline-block p-2 text-color" href="#"><i class="ti-twitter-alt"></i></a></li>
            <li class="list-inline-item mx-0"><a class="d-inline-block p-2 text-color" href="#"><i class="ti-linkedin"></i></a></li>
            <li class="list-inline-item mx-0"><a class="d-inline-block p-2 text-color" href="#"><i class="ti-instagram"></i></a></li>
          </ul>
        </div>
        <div class="col-lg-8 text-center text-lg-right">
          <ul class="list-inline">
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="notice.php">notice</a></li>
           <!--  <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="research.php">research</a></li>
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="scholarship.php">SCHOLARSHIP</a></li> -->
                     <!-- After Login details Display -->
                     <?php if (isset($_SESSION['email'])): ?>
        <li class="nav-item dropdown">
        <?php if($table=="Students"):?>
        <a href="../student module/profile.php" class="d-inline-block me-2">
        <img src="../educenter-master . <?php echo $Profile; ?>" alt="Profile" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;" title="Go to Profile">
        </a>
        <?php elseif($table=="Faculty"):?>
          <a href="../faculty module/faculty_profile.php" class="d-inline-block me-2">
        <img src="../educenter-master . <?php echo $Profile; ?>" alt="Profile" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;" title="Go to Profile">
        </a>
          <?php elseif($table=="Admin"):?>
            <a href="../admin/admin_profile.php" class="d-inline-block me-2">
        <img src="../educenter-master . <?php echo $Profile; ?>" alt="Profile" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;" title="Go to Profile">
        </a>
            <?php endif;?>
              <a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <?php echo $FirstName . ' ' . $LastName; ?>             
               </a>
               <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <?php if($table=="Students"):?>
              <a class="dropdown-item" href="../educenter-master/student module/dashboard.php">DashBoard</a>
                <a class="dropdown-item" href="../educenter-master/student module/profile.php">My Profile</a>
                <a class="dropdown-item" href="../educenter-master/user/logout.php">Logout</a>
                <?php elseif($table=="Faculty"):?>
                <a class="dropdown-item" href="../educenter-master/faculty module/faculty_dashboard.php">DashBoard</a>
                <a class="dropdown-item" href="../educenter-master/faculty module/faculty_profile.php">My Profile</a>
                <a class="dropdown-item" href="../educenter-master/user/logout.php">Logout</a>
                <?php elseif($table=="Admin"):?>
                <a class="dropdown-item" href="../educenter-master/admin/admin_dashboard.php">DashBoard</a>
                <a class="dropdown-item" href="../educenter-master/faculty module/admin_profile.php">My Profile</a>
                <a class="dropdown-item" href="../educenter-master/user/logout.php">Logout</a>
                <?php endif;?>
              </div>
  </div>
</li>


        </li>
        <?php else: ?>
          <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="user/login.php" >login</a></li>
            <!-- <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="student module/student.php">register</a></li> -->
            <li class="list-inline-item">
              <a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Register
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="faculty module/faculty.php">Teacher</a>
                <a class="dropdown-item" href="student module/student.php">Student</a>
              </div>
            </li>
          <?php endif; ?>
        <!-- After Login details Display End-->
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- navbar -->
  <div class="navigation w-100">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light p-0">
        <a class="navbar-brand" href="index.php"><img src="images/logofin.png" alt="logo"></a>
        <button class="navbar-toggler rounded-0" type="button" data-toggle="collapse" data-target="#navigation"
          aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navigation">
          <ul class="navbar-nav ml-auto text-center">
            <li class="nav-item @@home">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item @@about">
              <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item @@courses">
              <a class="nav-link" href="courses.php">COURSES</a>
            </li>
            <li class="nav-item dropdown view">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Pages
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="teacher.php">Teacher</a>
                <!-- <a class="dropdown-item" href="teacher-single.php">Teacher Single</a> -->
                <a class="dropdown-item" href="notice.php">Notice</a>
                <!-- <a class="dropdown-item" href="notice-single.php">Notice Details</a> -->
                <a class="dropdown-item" href="research.php">Research</a>
                <!-- <a class="dropdown-item" href="scholarship.php">Scholarship</a> -->
                <a class="dropdown-item" href="courses.php">Course Details</a>
                <a class="dropdown-item" href="event-single.php">Event Details</a>
                <!-- <a class="dropdown-item" href="blog-single.php">Blog Details</a> -->
              </div>
            </li>
            <li class="nav-item @@contact">
              <a class="nav-link" href="contact.php">CONTACT</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>
</header>
<!-- /header -->
<!-- Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content rounded-0 border-0 p-4">
            <div class="modal-header border-0">
                <h3>Register</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="login">
                    <form action="#" class="row">
                        <div class="col-12">
                            <input type="text" class="form-control mb-3" id="signupPhone" name="signupPhone" placeholder="Phone">
                        </div>
                        <div class="col-12">
                            <input type="text" class="form-control mb-3" id="signupName" name="signupName" placeholder="Name">
                        </div>
                        <div class="col-12">
                            <input type="email" class="form-control mb-3" id="signupEmail" name="signupEmail" placeholder="Email">
                        </div>
                        <div class="col-12">
                            <input type="password" class="form-control mb-3" id="signupPassword" name="signupPassword" placeholder="Password">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">SIGN UP</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content rounded-0 border-0 p-4">
            <div class="modal-header border-0">
                <h3>Login</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" class="row">
                    <div class="col-12">
                        <input type="text" class="form-control mb-3" id="loginPhone" name="loginPhone" placeholder="Phone">
                    </div>
                    <div class="col-12">
                        <input type="text" class="form-control mb-3" id="loginName" name="loginName" placeholder="Name">
                    </div>
                    <div class="col-12">
                        <input type="password" class="form-control mb-3" id="loginPassword" name="loginPassword" placeholder="Password">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">LOGIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- page title -->
<section class="page-title-section overlay" data-background="images/backgrounds/page-title.jpg">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <ul class="list-inline custom-breadcrumb">
          <li class="list-inline-item"><a class="h2 text-primary font-secondary" href="courses.php">Our Courses</a></li>
          <li class="list-inline-item text-white h3 font-secondary nasted">M.Sc.(ICT)</li>
        </ul>
        <p class="text-lighten">MScICT stands for Master of Science in Information and Communication Technology. It is a postgraduate degree that combines core
             aspects of Information Technology (IT) and Communication Technology to prepare students for modern tech-driven industries.</p>
      </div>
    </div>
  </div>
</section>
<!-- /page title -->

<!-- section -->
<section class="section-sm">
  <div class="container">
    <div class="row">
      <div class="col-12 mb-4">
        <!-- course thumb -->
        <img src="images/courses/mscict.png" class="img-fluid w-100">
      </div>
    </div>
    <!-- course info -->
    <div class="row align-items-center mb-5">
      <div class="col-xl-3 order-1 col-sm-6 mb-4 mb-xl-0">
        <h2>M.Sc.(ICT)</h2>
      </div>
      <div class="col-xl-6 order-sm-3 order-xl-2 col-12 order-2">
        <ul class="list-inline text-xl-center">
          <li class="list-inline-item mr-4 mb-3 mb-sm-0">
            <div class="d-flex align-items-center">
              <i class="ti-book text-primary icon-md mr-2"></i>
              <div class="text-left">
                <h6 class="mb-0">SEMESTERS</h6>
                <p class="mb-0">04 </p>
              </div>
            </div>
          </li>
          <li class="list-inline-item mr-4 mb-3 mb-sm-0">
            <div class="d-flex align-items-center">
              <i class="ti-alarm-clock text-primary icon-md mr-2"></i>
              <div class="text-left">
                <h6 class="mb-0">DURATION</h6>
                <p class="mb-0">02 Years</p>
              </div>
            </div>
          </li>
          <li class="list-inline-item mr-4 mb-3 mb-sm-0">
            <div class="d-flex align-items-center">
              <i class="ti-wallet text-primary icon-md mr-2"></i>
              <div class="text-left">
                <h6 class="mb-0">FEE</h6>
                <p class="mb-0">From: INR 27,000(per semester)</p>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="col-xl-3 text-sm-right text-left order-sm-2 order-3 order-xl-3 col-sm-6 mb-4 mb-xl-0">
        <a href="student module/student.php" class="btn btn-primary">Apply now</a>
      </div>
      <!-- border -->
      <div class="col-12 mt-4 order-4">
        <div class="border-bottom border-primary"></div>
      </div>
    </div>
    <!-- course details -->
    <div class="row">
      <div class="col-12 mb-4">
        <h3>About Course</h3>
        <p>MSc(ICT), or Master of Science in Information and Communication Technology, is a postgraduate program that focuses on the
             integration of information technology with communication systems. This course is designed to equip students with both theoretical
              knowledge and practical skills in areas such as computer networks, telecommunications, software development, database management,
               cybersecurity, and cloud computing. It emphasizes the role of ICT in solving real-world problems and supporting modern industries,
                governments, and societies through advanced digital solutions. Students learn how to design, develop, and manage complex ICT
                 systems that facilitate effective communication and information processing. The program is ideal for graduates from computer
                  science, IT, electronics, or communication backgrounds who are aiming for careers in software development, IT infrastructure
                   management, network administration, telecom, data analytics, or ICT consulting. MScICT not only prepares students for the
                    technical aspects of the field but also encourages innovation and critical thinking to adapt to the rapidly evolving tech
                     landscape.</p>
      </div>
      <div class="col-12 mb-4">
        <h3 class="mb-3">Requirements</h3>
        <div class="col-12 px-0">
          <div class="row">
            <div class="col-md-6">
              <ul class="list-styled">
                <li>Candidates must have passed 10+2 (Higher Secondary/Intermediate) or equivalent examination.</li>
                <li>The qualifying exam must be from a recognized board (e.g., CBSE, ICSE, State Boards).</li>
                <li>Candidates must have studied Mathematics as one of the subjects in 10+2.</li>
                <li>Candidates requires a minimum of 65-70% aggregate marks in 12th standard and in Bachelors degree.</li>
                <li>Candidates age must be between 19 and 25 at the time of admission.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 mb-4">
        <h3 class="mb-3">How to Apply</h3>
        <ul class="list-styled">
          <li>Check that you fullfill the above requirements.</li>
          <li>Fill the application form.</li>
          <li>Wait for the merit list based on Enterance Examination.</li>
        </ul>
      </div>
      <div class="col-12 mb-5">
        <h3>Fees and Funding</h3>
        <p>Fees(per semester) : INR 27,000 </p>
        <p>One-time Admission Fee : INR 1,500 </p>
        <p>Total Fees(2 years) : INR 1,09,500 </p>
      </div>

      
      <!-- teacher -->
      <div class="col-12">
        <h5 class="mb-3">Teacher</h5>
      <?php while($row=$faculty_details->fetch_assoc()):?>
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <div class="media mb-2 mb-sm-0">
          <img 
  class="rounded-circle mr-4 img-fluid" 
  style="width: 120px; height: 120px; object-fit: cover;" 
  src="../educenter-master<?php echo $row['Profile'] ? $row['Profile'] : 'images/teachers/t1.png'; ?>" 
  alt="Teacher">

            <div class="media-body">
              <h4 class="mt-0"><?php echo $row['FirstName'] . ' ' . $row['LastName'];?></h4>
              IT Professional
            </div>
          </div>
          <div class="social-link">
            <h6 class="d-none d-sm-block">Social Link</h6>
            <ul class="list-inline">
              <li class="list-inline-item"><a class="d-inline-block text-light p-1" href="#"><i class="ti-facebook"></i></a></li>
              <li class="list-inline-item"><a class="d-inline-block text-light p-1" href="#"><i class="ti-twitter-alt"></i></a></li>
              <li class="list-inline-item"><a class="d-inline-block text-light p-1" href="#"><i class="ti-linkedin"></i></a></li>
              <li class="list-inline-item"><a class="d-inline-block text-light p-1" href="#"><i class="ti-instagram"></i></a></li>
            </ul>
          </div>
        </div>
        <?php endwhile;?>
        <div class="border-bottom border-primary mt-4"></div>
      </div>
    </div>
  </div>
</section>
<!-- /section -->

<!-- related course -->
<section class="section pt-0">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="section-title">Related Course</h2>
      </div>
    </div>
    <div class="row justify-content-center">
 <!-- course item -->
 <div class="col-lg-4 col-sm-6 mb-5">
    <div class="card p-0 border-primary rounded-0 hover-shadow">
      <img class="card-img-top rounded-0" src="images/courses/bscit.png" alt="course thumb">
      <div class="card-body">
        <ul class="list-inline mb-2">
          <li class="list-inline-item"><i class="ti-calendar mr-1 text-color"></i>02-14-2018</li>
          <li class="list-inline-item"><a class="text-color" href="#">IT</a></li>
        </ul>
        <a href="course-bscit.php">
          <h4 class="card-title">B.Sc.(IT)</h4>
        </a>
        <p class="card-text mb-4"> Designed to meet the demands of today’s digital world,
           our B.Sc. IT course blends theory with practical learning, helping students master the latest tools and technologies</p>
        <a href="course-bscit.php" class="btn btn-primary btn-sm">Apply now</a>
      </div>
    </div>
  </div>
   <!-- course item -->
   <div class="col-lg-4 col-sm-6 mb-5">
    <div class="card p-0 border-primary rounded-0 hover-shadow">
      <img class="card-img-top rounded-0" src="images/courses/mscit.png" alt="course thumb">
      <div class="card-body">
        <ul class="list-inline mb-2">
          <li class="list-inline-item"><i class="ti-calendar mr-1 text-color"></i>02-14-2018</li>
          <li class="list-inline-item"><a class="text-color" href="#">IT</a></li>
        </ul>
        <a href="course-mscit.php">
          <h4 class="card-title">M.Sc.(IT)</h4>
        </a>
        <p class="card-text mb-4"> With a focus on innovation and research, the M.Sc. IT program prepares students for high-level roles
           in IT management, system architecture, and emerging technologies.</p>
        <a href="course-mscit.php" class="btn btn-primary btn-sm">Apply now</a>
      </div>
    </div>
  </div>
      
    </div>
  </div>
</section>
<!-- /related course -->

<!-- footer -->
<footer>
  <!-- newsletter -->
  <!-- <div class="newsletter">
    <div class="container">
      <div class="row">
        <div class="col-md-9 ml-auto bg-primary py-5 newsletter-block">
          <h3 class="text-white">Subscribe Now</h3>
          <form action="#">
            <div class="input-wrapper">
              <input type="email" class="form-control border-0" id="newsletter" name="newsletter" placeholder="Enter Your Email...">
              <button type="submit" value="send" class="btn btn-primary">Join</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div> -->
  <!-- footer content -->
  <div class="footer bg-footer section border-bottom">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-sm-8 mb-5 mb-lg-0">
          <!-- logo -->
          <a class="logo-footer" href="index.php"><img class="img-fluid mb-4" src="images/logofin.png" alt="logo"></a>
          <ul class="list-unstyled">
            <li class="mb-2">TechVerse
              123 University Avenue
              Cityville, State 12345
              Country</li>
            <li class="mb-2">+00 333 555 777</li>
            <li class="mb-2">+11 444 666 888</li>
            <li class="mb-2">contact@myuni@2025.com</li>
          </ul>
        </div>
        <!-- company -->
        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-5 mb-md-0">
          <h4 class="text-white mb-5">COMPANY</h4>
          <ul class="list-unstyled">
            <li class="mb-3"><a class="text-color" href="about.php">About Us</a></li>
            <li class="mb-3"><a class="text-color" href="teacher.php">Our Teacher</a></li>
            <li class="mb-3"><a class="text-color" href="contact.php">Contact</a></li>
            <!-- <li class="mb-3"><a class="text-color" href="blog.php">Blog</a></li> -->
          </ul>
        </div>
        <!-- links -->
        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-5 mb-md-0">
          <h4 class="text-white mb-5">LINKS</h4>
          <ul class="list-unstyled">
            <li class="mb-3"><a class="text-color" href="courses.php">Courses</a></li>
            <li class="mb-3"><a class="text-color" href="#">Events</a></li>
            <li class="mb-3"><a class="text-color" href="#">Gallary</a></li>
            <li class="mb-3"><a class="text-color" href="#">FAQs</a></li>
          </ul>
        </div>
        <!-- support -->
        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-5 mb-md-0">
          <h4 class="text-white mb-5">SUPPORT</h4>
          <ul class="list-unstyled">
            <li class="mb-3"><a class="text-color" href="#">Forums</a></li>
            <li class="mb-3"><a class="text-color" href="#">Documentation</a></li>
            <li class="mb-3"><a class="text-color" href="#">Language</a></li>
            <li class="mb-3"><a class="text-color" href="#">Release Status</a></li>
          </ul>
        </div>
        <!-- support -->
        <!-- <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-5 mb-md-0">
          <h4 class="text-white mb-5">RECOMMEND</h4>
          <ul class="list-unstyled">
            <li class="mb-3"><a class="text-color" href="#">WordPress</a></li>
            <li class="mb-3"><a class="text-color" href="#">LearnPress</a></li>
            <li class="mb-3"><a class="text-color" href="#">WooCommerce</a></li>
            <li class="mb-3"><a class="text-color" href="#">bbPress</a></li>
          </ul>
        </div> -->
      </div>
    </div>
  </div>
  <!-- copyright -->
  <div class="copyright py-4 bg-footer">
    <div class="container">
      <div class="row">
        <div class="col-sm-7 text-sm-left text-center">
          <!-- <p class="mb-0">Copyright
            <script>
              var CurrentYear = new Date().getFullYear()
              document.write(CurrentYear)
            </script>  -->
            <!-- © Theme By <a href="https://themefisher.com">themefisher.com</a></p> . All Rights Reserved. -->
        </div>
        <div class="col-sm-5 text-sm-right text-center">
          <ul class="list-inline">
            <li class="list-inline-item"><a class="d-inline-block p-2" href="https://www.facebook.com"><i class="ti-facebook text-primary"></i></a></li>
            <li class="list-inline-item"><a class="d-inline-block p-2" href="https://www.twitter.com"><i class="ti-twitter-alt text-primary"></i></a></li>
            <li class="list-inline-item"><a class="d-inline-block p-2" href="https://www.instagram.com"><i class="ti-instagram text-primary"></i></a></li>
            <li class="list-inline-item"><a class="d-inline-block p-2" href="https://dribbble.com/themefisher"><i class="ti-dribbble text-primary"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- /footer -->

<!-- jQuery -->
<script src="plugins/jQuery/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="plugins/bootstrap/bootstrap.min.js"></script>
<!-- slick slider -->
<script src="plugins/slick/slick.min.js"></script>
<!-- aos -->
<script src="plugins/aos/aos.js"></script>
<!-- venobox popup -->
<script src="plugins/venobox/venobox.min.js"></script>
<!-- mixitup filter -->
<script src="plugins/mixitup/mixitup.min.js"></script>
<!-- google map -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcABaamniA6OL5YvYSpB3pFMNrXwXnLwU&libraries=places"></script>
<script src="plugins/google-map/gmap.js"></script>

<!-- Main Script -->
<script src="js/script.js"></script>

</body>
</html>