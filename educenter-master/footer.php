<!-- Footer with CSS included -->
<style>
  @import url('/educenter-master/plugins/bootstrap/bootstrap.min.css');
  @import url('/educenter-master/plugins/themify-icons/themify-icons.css');

  footer {
    background-color: #343a40;
    color: white;
    padding-top: 3rem;
    padding-bottom: 2rem;
  }

  footer a {
    color: #ffffff;
    text-decoration: none;
  }

  footer a:hover {
    text-decoration: underline;
  }

  footer h5 {
    font-weight: bold;
    margin-bottom: 1.5rem;
  }

  footer .ti-facebook,
  footer .ti-twitter-alt,
  footer .ti-instagram,
  footer .ti-dribbble,
  footer .ti-mobile,
  footer .ti-email {
    margin-right: 8px;
  }

  .bg-secondary {
    background-color: #495057 !important;
  }

  .me-3 {
    margin-right: 1rem;
  }

  .img-fluid {
    max-width: 100%;
    height: auto;
  }
</style>

<footer class="bg-dark text-white pt-5 pb-4">
  <div class="container text-md-left">
    <div class="row text-md-left">

      <!-- Logo and Address -->
      <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-4">
      <img src="../images/logofin.png" class="img-fluid mb-3" alt="TechVerse Logo">
      <p>TechVerse<br>123 University Avenue<br>Cityville, State 12345<br>Country</p>
        <p><i class="ti-mobile text-primary"></i> +00 333 555 777</p>
        <p><i class="ti-email text-primary"></i> contact@myuni2025.com</p>
      </div>

      <!-- Navigation Links -->
      <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
        <h5 class="text-uppercase mb-4">Quick Links</h5>
        <p><a href="about.php" class="text-white">About Us</a></p>
        <p><a href="courses.php" class="text-white">Courses</a></p>
        <p><a href="event.php" class="text-white">Events</a></p>
        <p><a href="contact.php" class="text-white">Contact</a></p>
      </div>

      <!-- Support -->
      <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
        <h5 class="text-uppercase mb-4">Support</h5>
        <p><a href="#" class="text-white">Help Center</a></p>
        <p><a href="#" class="text-white">FAQs</a></p>
        <p><a href="#" class="text-white">Forums</a></p>
        <p><a href="#" class="text-white">Documentation</a></p>
      </div>

      <!-- Social Links -->
      <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
        <h5 class="text-uppercase mb-4">Connect</h5>
        <a href="#" class="text-white me-3"><i class="ti-facebook"></i></a>
        <a href="#" class="text-white me-3"><i class="ti-twitter-alt"></i></a>
        <a href="#" class="text-white me-3"><i class="ti-instagram"></i></a>
        <a href="#" class="text-white"><i class="ti-dribbble"></i></a>
      </div>

    </div>
  </div>

  <!-- Copyright -->
  <div class="text-center py-3 bg-secondary">
    &copy; <span id="year"></span> TechVerse. All Rights Reserved.
  </div>
</footer>

<script>
  // Set the current year dynamically
  document.getElementById("year").textContent = new Date().getFullYear();
</script>
