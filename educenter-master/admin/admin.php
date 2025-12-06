<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
  <!-- Form CSS -->
  <link rel="stylesheet" href="../css/display.css">  
  <link rel="stylesheet" href="../css/form.css">  

  <!-- Themify Icons for Header -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css" />
  
  <!-- JQuery for Loading Header/Footer -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script></head>
<body>
<!-- Header -->
<div class="header-container"></div>

<input type="button" class="btn" value="📘 STUDENT DATA" onclick="loadStudentData()">
<input type="button" class="btn" value="👨‍🏫 FACULTY DATA" onclick="loadFacultyData()">
<input type="button" class="btn" value="👨‍🏫 Notices DATA" onclick="loadnotices()">

<br><br>


<div id="data-display"></div>
<script>
    // Function to load student data
    function loadStudentData() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "../student module/studentdis.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("data-display").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    // Function to load faculty data
    function loadFacultyData() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "../faculty module/facultydis.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("data-display").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

        // Function to load Notices data
        function loadnotices() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "../admin/edit_notices.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("data-display").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
    //header
    $(document).ready(function () {
    $(".header-container").load("../header.php");
    $(".footer-container").load("../footer.php");
  });
</script>

</body>
</html>
