<?php
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    include("../../../config/database.php");

    // Fetch user details from the session or database
    $id = $_SESSION['id'];
    $eid = $_SESSION['username'];

    // Handle undefined $position variable
    if (isset($_SESSION['position'])) {
        $position = $_SESSION['position']; // Fetch from session
    } else {
        $position = "guest"; // Assign default value (e.g., "guest")
    }

    // Fetch user details from the database
    $sql = "SELECT * FROM teachers WHERE eid = '$eid'";
    $result = mysqli_query($conn, $sql);
    $resultcheck = mysqli_num_rows($result);

    if ($resultcheck > 0 && $row = mysqli_fetch_assoc($result)) {
        $fname = ucfirst($row['fname']);
        $lname = ucfirst($row['lname']);
        $center = $row['center'];
        $course = $row['course'];
        $status = $row['status'];
    } else {
        echo "Error fetching user details.";
        exit;
    }

    // Check account status
    if ($status === 'yes' || $status === 'Yes') {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Teachers-ISE</title>
            <link rel="stylesheet" type="text/css" href="css/style.css">
        </head>
        <body>
        <h2 align="center" style="color: blue">ISE</h2>
        <div class="header">
            <span style="font-size:30px;cursor:pointer" class="logo" onclick="openNav()">&#9776; Menu </span>
            <div class="header-right">
                <a href="profile.php">
                    <?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?>
                </a>
            </div>
        </div>
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="index.php" class="logo"><span style="color:red;font-size:25px">ISE</span></a>
            <a href="profile.php"><?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?></a>
            <a href="index.php">Home</a>
            <a href="attendance.php">Attendance</a>
            <a href="search.php">Search Student Information</a>
            <a href="timetable.php">TimeTable</a>
            <a href="update_password.php">Update Password</a>
            <a href="../../../logout.php">Logout</a>
        </div>
        <div style="padding-left:20px; float: left;border-left: 6px solid red;background-color: lightgrey;width: 50%">
            <h1 align="center">Welcome</h1>
            <p align="center">
                <?php echo "Today is " . date("l, d-m-Y"); ?>
            </p>
        </div>
        <script>
            function openNav() {
                document.getElementById("mySidenav").style.width = "250px";
            }

            function closeNav() {
                document.getElementById("mySidenav").style.width = "0";
            }
        </script>
        </body>
        </html>
        <?php
    } else {
        echo "<h1>Your account is deactivated by the admin. Please contact Admin for further assistance.</h1>";
    }
} else {
    // Redirect to login if the user is not logged in
    header("Location: ../../index.php");
    exit();
}
?>
