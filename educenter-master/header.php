<?php
// Define base URL for assets
$base_url = '/educenter-master/educenter-master/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Header</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>plugins/bootstrap/bootstrap.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            margin: 0;
            padding-top: 70px; /* Offset height of fixed header */
        }

        .simple-header {
            background-color: #004c99;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            color: white;
        }

        .simple-header .logo img {
            height: 40px;
        }

        .simple-header .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            margin-left: 20px; /* Add spacing between links */
        }

        .simple-header .nav-links a:hover {
            color: #ffcc00; /* Example hover effect */
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header class="simple-header">
        <!-- Logo Left -->
        <div class="logo">
            <a href="<?php echo $base_url; ?>index.php">
                <img src="<?php echo $base_url; ?>images/logofin.png" alt="Logo">
            </a>
        </div>

        <!-- Home Link Right -->
        <div class="nav-links">
            <a href="<?php echo $base_url; ?>index.php">Home</a>
        </div>
    </header>
</body>
</html>