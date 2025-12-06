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

    $tbl_name="Notices";
    $sql = "SELECT * FROM $tbl_name";
    $result = $conn->query($sql);


    echo "<table>
            <tr>
                <th>id</th>
                <th>title</th>
                <th>description</th>
                <th>notice_date</th>
                <th>publisher_name</th>
                <th>Actions</th>
            </tr>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['title'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td>" . $row['notice_date'] . "</td>
                    <td>" . $row['publisher_name'] . "</td>


                     <td class=\"btn-container\">
                        <a href=\"../user/delete.php?table=" . $tbl_name . "&user_id=" . $row['id'] . "\" class=\"btn\" id=\"delete-link\">Delete</a>
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
