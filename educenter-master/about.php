<?php
session_start();
include_once "../educenter-master/user/connection.php";
$count_students = "SELECT COUNT(*) FROM Students";
$result = $conn->query($count_students);
$student_count = $result->fetch_row()[0];

$count_faculty = "SELECT COUNT(*) FROM Faculty";
$result = $conn->query($count_faculty);
$faculty_count = $result->fetch_row()[0];

$count_courses = "SELECT COUNT(*) FROM Courses";
$result = $conn->query($count_courses);
$course_count = $result->fetch_row()[0];


$sql_faculty="SELECT * FROM Faculty LIMIT 3";
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
          <a class="text-color mr-3" href="callto:+443003030266"><strong>CALL</strong>  +00 111 222 3334</a>
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
           <!-- <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="research.php">research</a></li>
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="scholarship.php">SCHOLARSHIP</a></li> -->
                     <!-- After Login details Display -->
                     <?php if (isset($_SESSION['email'])): ?>
        <li class="nav-item dropdown">
        <?php if($table=="Students"):?>
        <a href="../educenter-master/student module/profile.php" class="d-inline-block me-2">
        <img src="../educenter-master . <?php echo $Profile; ?>" alt="Profile" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;" title="Go to Profile">
        </a>
        <?php elseif($table=="Faculty"):?>
          <a href="../educenter-master/faculty module/faculty_profile.php" class="d-inline-block me-2">
        <img src="../educenter-master . <?php echo $Profile; ?>" alt="Profile" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;" title="Go to Profile">
        </a>
          <?php elseif($table=="Admin"):?>
            <a href="../educenter-master/admin/admin_profile.php" class="d-inline-block me-2">
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
            <li class="nav-item active">
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
                <!-- <a class="dropdown-item" href="event-single.php">Event Details</a> -->
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
          <li class="list-inline-item"><a class="h2 text-primary font-secondary" href="about.php">About Us</a></li>
          <li class="list-inline-item text-white h3 font-secondary @@nasted"></li>
        </ul>
        <p class="text-lighten">TechVerse is a leading institution specializing in Information Technology education and research. We offer cutting-edge programs in fields like AI, Cybersecurity, Data Science, and Software Engineering, led by expert faculty and supported by modern labs and real-world industry connections. Our mission is to equip students with the skills and mindset to innovate, lead, and shape the digital future.</p>
      </div>
    </div>
  </div>
</section>
<!-- /page title -->

<!-- about -->
<section class="section">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <img class="img-fluid w-100 mb-4" src="images/about/aboutpg.jpg" alt="about image">
        <h2 class="section-title">ABOUT OUR JOURNEY</h2>
        <p>Welcome to [University Name], a premier institution dedicated exclusively to advancing education, research, and innovation in the field of Information Technology. Established in [Year], our university was founded with a vision to nurture the next generation of tech leaders, innovators, and problem-solvers in a rapidly evolving digital world.</p>
        <p>At [University Name], we believe that technology is not just a tool—it’s a transformative force shaping the future of every industry and every aspect of life. Our programs are carefully designed to equip students with both a deep theoretical foundation and strong practical skills across a wide range of IT disciplines, including Software Engineering, Cybersecurity, Artificial Intelligence, Data Science, Cloud Computing, and more.</p>
        <p>Our world-class faculty brings together a rich blend of academic expertise and real-world experience, ensuring that students learn from leading minds who are actively involved in cutting-edge research and industry collaborations. Through hands-on projects, internships, innovation labs, and startup incubation programs, students gain invaluable exposure to real-world challenges and opportunities from day one.</p>
        <p>We are committed to fostering a dynamic and inclusive learning environment where creativity, curiosity, and collaboration thrive. Our modern campus is equipped with state-of-the-art facilities, high-performance computing centers, and a strong digital infrastructure that supports immersive, technology-driven learning experiences.</p>
        <p>At [University Name], education goes beyond the classroom. We cultivate a vibrant community of learners, thinkers, and doers who are passionate about using technology to drive positive change. Our graduates go on to become software engineers, data analysts, cybersecurity experts, tech entrepreneurs, and thought leaders making a global impact.</p>
        <p>Join us and be part of a future-forward institution where innovation is a way of life—and where your journey in shaping the digital world begins.</p>
      </div>
    </div>
  </div>
</section>
<!-- /about -->

<!-- funfacts -->
<section class="section-sm bg-primary">
  <div class="container">
    <div class="row">
      <!-- funfacts item -->
      <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
        <div class="text-center">
          <h2 class="count text-white" data-count="<?php echo $faculty_count;?>">0</h2>
          <h5 class="text-white">TEACHERS</h5>
        </div>
      </div>
      <!-- funfacts item -->
      <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
        <div class="text-center">
          <h2 class="count text-white" data-count="<?php echo $course_count;?>">0</h2>
          <h5 class="text-white">COURSES</h5>
        </div>
      </div>
      <!-- funfacts item -->
      <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
        <div class="text-center">
          <h2 class="count text-white" data-count="<?php echo $student_count;?>">0</h2>
          <h5 class="text-white">STUDENTS</h5>
        </div>
      </div>
      <!-- funfacts item -->
      <!-- <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
        <div class="text-center">
          <h2 class="count text-white" data-count="3737">0</h2>
          <h5 class="text-white">SATISFIED CLIENT</h5>
        </div>
      </div> -->
    </div>
  </div>
</section>
<!-- /funfacts -->

<!-- success story -->
<section class="section bg-cover" data-background="images/backgrounds/success-story.jpg">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-sm-4 position-relative success-video">
        <!-- <a class="play-btn venobox" href="https://youtu.be/nA1Aqp0sPQo" data-vbtype="video"> -->
          <i class="ti-control-play"></i>
        </a>
      </div>
      <div class="col-lg-6 col-sm-8">
        <div class="bg-white p-5">
          <h2 class="section-title">Success Stories</h2>
          <p>Our students don’t just graduate—they go on to lead, innovate, and make an impact. Each success story is a testament to the power of quality education.</p>
          <p>From classrooms to boardrooms, our alumni are shaping industries, solving real-world problems, and living their dreams.</p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /success story -->

<!-- teachers -->
<section class="section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12">
          <h2 class="section-title">Our Teachers</h2>
        </div>
        <!-- teacher -->
         <!-- teacher -->
      <?php while($row=$faculty_details->fetch_assoc()):?>
      <div class="col-lg-4 col-sm-6 mb-5 mb-lg-0">
        <div class="card border-0 rounded-0 hover-shadow">
          <img class="card-img-top rounded-0" src="../educenter-master<?php echo $row['Profile'] ? $row['Profile'] :images/teachers/teacher.png;?>" alt="teacher">
          <div class="card-body">
            <a href="teacher-single.php">
              <h4 class="card-title"><?php echo $row['FirstName'] . ' ' . $row['LastName'];?></h4>
            </a>
            <p>Teacher</p>
            <ul class="list-inline">
              <li class="list-inline-item"><a class="text-color" href="#"><i class="ti-facebook"></i></a></li>
              <li class="list-inline-item"><a class="text-color" href="#"><i class="ti-twitter-alt"></i></a></li>
              <li class="list-inline-item"><a class="text-color" href="#"><i class="ti-google"></i></a></li>
              <li class="list-inline-item"><a class="text-color" href="#"><i class="ti-linkedin"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
    <?php endwhile;?>
      </div>
    </div>
  </section>
  <!-- /teachers -->

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