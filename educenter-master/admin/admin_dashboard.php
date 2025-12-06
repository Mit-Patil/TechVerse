
<?php
session_start();
include_once "../user/connection.php";
if (!isset($_SESSION['email'])) {
    header("Location: ../user/login.php");
    exit;
}

$email=$_SESSION['email'];
$table=$_SESSION['table'];

$sql_admin="SELECT * FROM $table WHERE Email='$email'";
$admin_details=$conn->query($sql_admin);

if($admin_details->num_rows===1)
{
  $row=$admin_details->fetch_assoc();

  $FirstName=$row['FirstName'];
  $LastName=$row['LastName'];
  $Email=$row['Email'];
  $Profile = $row['Profile'];
  
$_SESSION['FirstName']=$FirstName;
$_SESSION['LastName']=$LastName;
$_SESSION['table']=$table;
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
  <link rel="stylesheet" href="../plugins/bootstrap/bootstrap.min.css">
  <!-- slick slider -->
  <link rel="stylesheet" href="../plugins/slick/slick.css">
  <!-- themefy-icon -->
  <link rel="stylesheet" href="../plugins/themify-icons/themify-icons.css">
  <!-- animation css -->
  <link rel="stylesheet" href="../plugins/animate/animate.css">
  <!-- aos -->
  <link rel="stylesheet" href="../plugins/aos/aos.css">
  <!-- venobox popup -->
  <link rel="stylesheet" href="../plugins/venobox/venobox.css">

  <!-- Main Stylesheet -->
  <link href="../css/style.css" rel="stylesheet">
  
  
  <!--Favicon-->
  <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../images/favicon.ico" type="image/x-icon">

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
            <!-- <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="notice.php">notice</a></li> -->
           <!-- <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="research.php">research</a></li>
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="scholarship.php">SCHOLARSHIP</a></li> -->
<!-- After Login details Display -->
<?php if (isset($_SESSION['email'])): ?>
        <li class="nav-item dropdown">
        <a href="../admin/admin_profile.php" class="d-inline-block me-2">
        <img src="<?php echo $Profile; ?>" alt="Profile" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;" title="Go to Profile">
        </a>
              <a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <?php echo $FirstName . ' ' . $LastName; ?>             
               </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="../admin/admin_profile.php">My Profile</a>
                <a class="dropdown-item" href="../user/logout.php">Logout</a>
              </div>
  </div>
</li>


        </li>
        <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="../register.php">Register</a></li>
        <li class="nav-item"><a class="nav-link" href="../user/login.php">Login</a></li>
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
        <a class="navbar-brand" href="../index.php"><img src="../images/logofin.png" alt="logo"></a>
        <button class="navbar-toggler rounded-0" type="button" data-toggle="collapse" data-target="#navigation"
          aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navigation">
          <ul class="navbar-nav ml-auto text-center">
            <li class="nav-item @@home">
              <a class="nav-link" href="../index.php">Home</a>
            </li>
            <!-- <li class="nav-item @@about">
              <a class="nav-link" href="../about.php">About</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="../courses.php">COURSES</a>
            </li>
            <li class="nav-item @@events">
              <a class="nav-link" href="../events.php">EVENTS</a>
            </li>
            <li class="nav-item @@blog">
              <a class="nav-link" href="../blog.php">BLOG</a>
            </li> --> 
            <li class="nav-item dropdown view">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <?php echo $Email;?>
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../admin/admin_profile.php">My Profile</a>
              <a class="dropdown-item" href="../user/logout.php">Logout</a>
              </div>
            </li>
            <!-- <li class="nav-item @@contact">
              <a class="nav-link" href="../contact.php">CONTACT</a> -->
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
<section class="page-title-section overlay" data-background="../images/backgrounds/page-title.jpg">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <ul class="list-inline custom-breadcrumb">
          <li class="list-inline-item"><a class="h2 text-primary font-secondary" href="../admin/admin_dashboard.php">Admin Dashboard</a></li>
          <li class="list-inline-item text-white h3 font-secondary "></li>
        </ul>
        <p class="text-lighten">Lead with ease — manage courses, people, and progress through a smart and seamless dashboard.</p>
      </div>
    </div>
  </div>
</section>
<!-- /page title -->


<!-- Admin Dashboard -->
<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      
      <!-- Admin Profile -->
      <div class="col-lg-4 col-sm-6 mb-5">
        <div class="card p-0 border-primary rounded-0 hover-shadow">
          <img class="card-img-top rounded-0" src="../images/courses/bscit.png" alt="Admin Profile">
          <div class="card-body">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a class="text-color" href="../admin/admin_profile.php"><i class="ti-user mr-1 text-color"></i></a>
              </li>
              <li class="list-inline-item"><a class="text-color" href="../admin/admin_profile.php">Profile</a></li>
            </ul>
            <a href="../admin/admin_profile.php">
              <h4 class="card-title">Admin Profile</h4>
            </a>
            <p class="card-text mb-4">
              Keep your profile up-to-date — a well-maintained profile reflects strong leadership.
            </p>
            <a href="../admin/admin_profile.php" class="btn btn-primary btn-sm">Profile</a>
          </div>
        </div>
      </div>

      <!-- Result Creation -->
      <div class="col-lg-4 col-sm-6 mb-5">
        <div class="card p-0 border-primary rounded-0 hover-shadow">
          <img class="card-img-top rounded-0" src="../images/courses/mscit.png" alt="Result">
          <div class="card-body">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a class="text-color" href="../result/create_result.php"><i class="ti-bar-chart mr-1 text-color"></i></a>
              </li>
              <li class="list-inline-item"><a class="text-color" href="../result/create_result.php">Results</a></li>
            </ul>
            <a href="../result/create_result.php">
              <h4 class="card-title">Generate Result</h4>
            </a>
            <p class="card-text mb-4">
              Empower students with clarity — generate and manage academic results with transparency.
            </p>
            <a href="../result/create_result.php" class="btn btn-primary btn-sm">Generate</a>
          </div>
        </div>
      </div>

      <!-- Teachers Details -->
      <div class="col-lg-4 col-sm-6 mb-5">
        <div class="card p-0 border-primary rounded-0 hover-shadow">
          <img class="card-img-top rounded-0" src="../images/courses/mscict.png" alt="Teachers">
          <div class="card-body">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a class="text-color" href="../teacher.php"><i class="ti-blackboard mr-1 text-color"></i></a>
              </li>
              <li class="list-inline-item"><a class="text-color" href="../teacher.php">Teachers</a></li>
            </ul>
            <a href="../teacher.php">
              <h4 class="card-title">Teachers</h4>
            </a>
            <p class="card-text mb-4">
              Great teachers inspire minds — explore faculty profiles to know your academic leaders.
            </p>
            <a href="../teacher.php" class="btn btn-primary btn-sm">Teachers Details</a>
          </div>
        </div>
      </div>

      <!-- Student Details -->
      <div class="col-lg-4 col-sm-6 mb-5">
        <div class="card p-0 border-primary rounded-0 hover-shadow">
          <img class="card-img-top rounded-0" src="../images/courses/mscict.png" alt="Students">
          <div class="card-body">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a class="text-color" href="../student.php"><i class="ti-id-badge mr-1 text-color"></i></a>
              </li>
              <li class="list-inline-item"><a class="text-color" href="../student.php">Students</a></li>
            </ul>
            <a href="../student.php">
              <h4 class="card-title">Students</h4>
            </a>
            <p class="card-text mb-4">
              Every student is a story in progress — stay informed to guide their educational journey.
            </p>
            <a href="../student.php" class="btn btn-primary btn-sm">Students Details</a>
          </div>
        </div>
      </div>

      <!-- Fees Creation -->
      <div class="col-lg-4 col-sm-6 mb-5">
        <div class="card p-0 border-primary rounded-0 hover-shadow">
          <img class="card-img-top rounded-0" src="../images/courses/mscict.png" alt="Fees">
          <div class="card-body">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a class="text-color" href="../fees/fees_reg.php"><i class="ti-wallet mr-1 text-color"></i></a>
              </li>
              <li class="list-inline-item"><a class="text-color" href="../fees/fees_reg.php">Fees</a></li>
            </ul>
            <a href="../fees/fees_reg.php">
              <h4 class="card-title">Fees</h4>
            </a>
            <p class="card-text mb-4">
              Build a seamless finance system — manage and assign fee structures with clarity and ease.
            </p>
            <a href="../fees/fees_reg.php" class="btn btn-primary btn-sm">Set Fees</a>
          </div>
        </div>
      </div>

      <!-- Course Creation -->
      <div class="col-lg-4 col-sm-6 mb-5">
        <div class="card p-0 border-primary rounded-0 hover-shadow">
          <img class="card-img-top rounded-0" src="../images/courses/mscict.png" alt="Courses">
          <div class="card-body">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a class="text-color" href="../academic/course.php"><i class="ti-book mr-1 text-color"></i></a>
              </li>
              <li class="list-inline-item"><a class="text-color" href="../academic/course.php">Courses</a></li>
            </ul>
            <a href="../academic/course.php">
              <h4 class="card-title">Course</h4>
            </a>
            <p class="card-text mb-4">
              Define the roadmap — set and manage courses to shape the learning journey.
            </p>
            <a href="../academic/course.php" class="btn btn-primary btn-sm">Set Course</a>
          </div>
        </div>
      </div>

      <!-- Subject Creation -->
      <div class="col-lg-4 col-sm-6 mb-5">
        <div class="card p-0 border-primary rounded-0 hover-shadow">
          <img class="card-img-top rounded-0" src="../images/courses/mscict.png" alt="Subjects">
          <div class="card-body">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a class="text-color" href="../academic/add_subject.php"><i class="ti-agenda mr-1 text-color"></i></a>
              </li>
              <li class="list-inline-item"><a class="text-color" href="../academic/add_subject.php">Subjects</a></li>
            </ul>
            <a href="../academic/add_subject.php">
              <h4 class="card-title">Subject</h4>
            </a>
            <p class="card-text mb-4">
              Structure the knowledge — create subjects to enrich the curriculum effectively.
            </p>
            <a href="../academic/add_subject.php" class="btn btn-primary btn-sm">Set Subject</a>
          </div>
        </div>
      </div>

      <!-- Manage Teachers and Students -->
      <div class="col-lg-4 col-sm-6 mb-5">
        <div class="card p-0 border-primary rounded-0 hover-shadow">
          <img class="card-img-top rounded-0" src="../images/courses/mscict.png" alt="Manage">
          <div class="card-body">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a class="text-color" href="../admin/admin.php"><i class="ti-settings mr-1 text-color"></i></a>
              </li>
              <li class="list-inline-item"><a class="text-color" href="../admin/admin.php">Manage</a></li>
            </ul>
            <a href="../admin/admin.php">
              <h4 class="card-title">Students & Faculty</h4>
            </a>
            <p class="card-text mb-4">
              Centralized control — manage both students and faculty profiles from a single panel.
            </p>
            <a href="../admin/admin.php" class="btn btn-primary btn-sm">Manage</a>
          </div>
        </div>
      </div>

      <!-- Notices -->
      <div class="col-lg-4 col-sm-6 mb-5">
        <div class="card p-0 border-primary rounded-0 hover-shadow">
          <img class="card-img-top rounded-0" src="../images/courses/mscict.png" alt="Notice">
          <div class="card-body">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a class="text-color" href="../admin/create_notice.php"><i class="ti-announcement mr-1 text-color"></i></a>
              </li>
              <li class="list-inline-item"><a class="text-color" href="../admin/create_notice.php">Notices</a></li>
            </ul>
            <a href="../admin/create_notice.php">
              <h4 class="card-title">Notices</h4>
            </a>
            <p class="card-text mb-4">
              Keep everyone informed — post important announcements to reach all stakeholders swiftly.
            </p>
            <a href="../admin/create_notice.php" class="btn btn-primary btn-sm">Create</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- /Admin Dashboard -->


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
          <a class="logo-footer" href="../index.php"><img class="img-fluid mb-4" src="../images/logo.png" alt="logo"></a>
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
            <li class="mb-3"><a class="text-color" href="../about.php">About Us</a></li>
            <li class="mb-3"><a class="text-color" href="../teacher.php">Our Teacher</a></li>
            <li class="mb-3"><a class="text-color" href="../contact.php">Contact</a></li>
            <li class="mb-3"><a class="text-color" href="#">Blog</a></li>
          </ul>
        </div>
        <!-- links -->
        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-5 mb-md-0">
          <h4 class="text-white mb-5">LINKS</h4>
          <ul class="list-unstyled">
            <li class="mb-3"><a class="text-color" href="../courses.php">Courses</a></li>
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
            </script> 
            © Theme By <a href="https://themefisher.com">themefisher.com</a></p> . All Rights Reserved. -->
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