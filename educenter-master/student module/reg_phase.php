<link rel="stylesheet" href="../css/form.css">


<?php
// Start session if not already started

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set current phase
$currentPhase =$_SESSION['currentPhase'];
 // Change this value as needed for testing

// Debug Output (optional)
// echo "<pre>";
// echo "regform1: " . ($_SESSION['regform1'] ?? 'not set') . "\n";
// echo "regform2: " . ($_SESSION['regform2'] ?? 'not set') . "\n";
// echo "regform3: " . ($_SESSION['regform3'] ?? 'not set') . "\n";
// echo "currentPhase: $currentPhase\n";
// echo "</pre>";
?>

<style>
/* === Sidebar Phase Styles === */
.sidebar {
    width: 100%;
    padding: 20px;
    background-color: #f0f0f0;
    border-radius: 10px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar li {
    margin-bottom: 15px;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 1.05em;
    text-align: left;
    background-color: #d3d3d3;
    color: #333;
    position: relative;
    cursor: default;
    transition: background-color 0.3s ease;
}

/* Active Phase */
.sidebar li.active {
    background-color: #fd7e14; /* orange */
    color: white;
    font-weight: bold;
}

/* Completed Phase */
.sidebar li.completed {
    background-color: #28a745; /* green */
    color: white;
}

/* Checkmark for completed */
.sidebar li.completed::after {
    content: "✔";
    position: absolute;
    right: 15px;
    font-size: 1.2em;
}


</style>

<div class="sidebar">
    <ul>
        <li class="<?php
            if ($currentPhase === 1) {
                echo 'active';
            } elseif (isset($_SESSION['regform1']) && $_SESSION['regform1'] == 1) {
                echo 'completed';
            }
        ?>">
            Phase 1: Basic Details
        </li>

        <li class="<?php
            if ($currentPhase === 2) {
                echo 'active';
            } elseif (isset($_SESSION['regform2']) && $_SESSION['regform2'] == 1) {
                echo 'completed';
            }
        ?>">
            Phase 2: Academic Information
        </li>

        <li class="<?php
            if ($currentPhase === 3 && $_SESSION['regform3']!=1) {
                echo 'active';
            } elseif (isset($_SESSION['regform3']) && $_SESSION['regform3'] == 1) {
                echo 'completed';
            }
        ?>">
            Phase 3: Account Creation And Update Profile
        </li>
    </ul>
</div>
