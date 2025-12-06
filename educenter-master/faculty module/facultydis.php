<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data</title>
    <link rel="stylesheet" href="../css/display.css">
      <!-- Form CSS -->
      <link rel="stylesheet" href="../css/form.css">  

      <!-- Themify Icons for Header -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css" />
  
  <!-- JQuery for Loading Header/Footer -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <!-- Header -->
<div class="header-container"></div>

<!-- Main Form Area -->
    <?php
    include_once '../user/connection.php';

    $tbl_name="faculty";
    $sql = "SELECT * FROM $tbl_name";
    $result = $conn->query($sql);


    echo "<table>
            <tr>
                <th>Faculty ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date Of Birth</th>
                <th>Gender</th>
                <th>Contact Number</th>
                <th>Email</th>
                <th>Address</th>
                <th>Department</th>
                <th>Profile</th>
                <th>Actions</th>
            </tr>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['FacultyID'] . "</td>
                    <td>" . $row['FirstName'] . "</td>
                    <td>" . $row['LastName'] . "</td>
                    <td>" . $row['DateOfBirth'] . "</td>
                    <td>" . $row['Gender'] . "</td>
                    <td>" . $row['ContactNumber'] . "</td>
                    <td>" . $row['Email'] . "</td>
                    <td>" . $row['Address'] . "</td>
                    <td>" . $row['Department'] . "</td>
                    <td><img src=\"" . $row['Profile'] . "\" alt=\"Profile Image\" width=\"60\" height=\"60\"></td>



                     <td class=\"btn-container\">
                        <a href=\"../user/delete.php?table=" . $tbl_name . "&user_id=" . $row['FacultyID'] . "\" class=\"btn\" id=\"delete-link\">Delete</a>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No users found.</td></tr>";
    }

    echo "</table>";
    $conn->close();
    ?>
    <script>
  $(document).ready(function () {
    $(".header-container").load("../header.php");
    $(".footer-container").load("../footer.php");
  });
</script>
</body>
</html>
