<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    include("../../../config/database.php");
    $id = $_SESSION['id'];
    $eid = $_SESSION['username'];

    // Fetch teacher details
    $stmt = $conn->prepare("SELECT * FROM teachers WHERE eid = ?");
    $stmt->bind_param("s", $eid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $fname = ucfirst($row['fname']);
        $lname = ucfirst($row['lname']);
        $center = $row['center'];
        $course = $row['course'];
        $batchmentor = $row['batchmentor'];
    } else {
        die("Teacher details not found.");
    }

    // Display success/cancel messages
    if (isset($_GET['ret'])) {
        $message = $_GET['ret'] === 'success' ? "Update Successful" : "Cancel Successful";
        echo "<script>alert('$message');</script>";
    }

    $ydate = date('Y-m-d');
    ?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $batchmentor; ?> - Mentor - ISE</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .sidenav {
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }
        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }
        .sidenav a:hover {
            color: #f1f1f1;
        }
        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }
    </style>
</head>
<body>
<h2 align="center" style="color: blue">
    <?php echo ucfirst($center) . ' (' . strtoupper($course) . ')' ?>
</h2>
<div class="header">
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</span>
    <div class="header-right">
        <a href="profile.php">
            <?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?>
        </a>
    </div>
</div>

<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="index.php" class="logo"><span style="color:red;font-size:25px">ISE</span></a>
    <a href="profile.php">Profile</a>
    <a href="index.php">Home</a>
    <a href="search.php">Search Student Information</a>
    <a href="batchmentorstudents.php">Students of <?php echo $batchmentor ?></a>
    <a href="timetable.php">Timetable</a>
    <a href="../../../logout.php">Logout</a>
</div>

<?php if (!isset($_GET['studentid'])) { ?>
    <div align="center" style="background-color:lightgray;padding: 10px;">
        <h4>All Students Information under Mentor: <?php echo $batchmentor; ?></h4>
        <table>
            <tr>
                <th>SID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>PID</th>
                <th>Timings</th>
                <th>Fees</th>
                <th>Scholarship</th>
                <th>Details</th>
            </tr>
            <?php
            // Query to fetch students under this mentor for the specified batch and center
            $stmt_students = $conn->prepare("SELECT * FROM students WHERE mentor = ? AND batch = ? AND center = ? AND course = ?");
            $stmt_students->bind_param("ssss", $batchmentor, $batchmentor, $center, $course);
            $stmt_students->execute();
            $result_students = $stmt_students->get_result();

            if ($result_students->num_rows > 0) {
                while ($result_row = $result_students->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($result_row['sid']); ?></td>
                        <td><?php echo htmlspecialchars($result_row['fname'] . ' ' . $result_row['lname']); ?></td>
                        <td><?php echo htmlspecialchars($result_row['email']); ?></td>
                        <td><?php echo htmlspecialchars($result_row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($result_row['pid']); ?></td>
                        <td><?php echo htmlspecialchars($result_row['timing']); ?></td>
                        <td><?php echo htmlspecialchars($result_row['fee']); ?></td>
                        <td><?php echo htmlspecialchars($result_row['scholarship']) . '%'; ?></td>
                        <td><a href="batchmentorstudents.php?studentid=<?php echo $result_row['sid'] ?>">Details</a></td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="9" align="center">No students found</td></tr>';
            }
            ?>
        </table>
    </div>
<?php } else {
    // Fetching specific student details
    $id_get = mysqli_real_escape_string($conn, $_GET['studentid']);
    $stmt_student = $conn->prepare("SELECT * FROM students WHERE sid = ? AND mentor = ? AND center = ? AND course = ?");
    $stmt_student->bind_param("ssss", $id_get, $batchmentor, $center, $course);
    $stmt_student->execute();
    $result_student = $stmt_student->get_result();

    if ($result_student->num_rows > 0) {
        $result_row = $result_student->fetch_assoc(); ?>
        <div align="center">
            <h4>Student Information of Batch <span style="color:blue;"><?php echo $batchmentor; ?></span></h4>
            <table>
                <tr>
                    <th>SID</th>
                    <td><?php echo htmlspecialchars($result_row['sid']); ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($result_row['fname'] . ' ' . $result_row['lname']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($result_row['email']); ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo htmlspecialchars($result_row['phone']); ?></td>
                </tr>
                <tr>
                    <th>PID</th>
                    <td><?php echo htmlspecialchars($result_row['pid']); ?></td>
                </tr>
                <tr>
                    <th>Timings</th>
                    <td><?php echo htmlspecialchars($result_row['timing']); ?></td>
                </tr>
                <tr>
                    <th>Fees</th>
                    <td><?php echo htmlspecialchars($result_row['fee']); ?></td>
                </tr>
                <tr>
                    <th>Scholarship</th>
                    <td><?php echo htmlspecialchars($result_row['scholarship']) . '%'; ?></td>
                </tr>
                <!-- Add any additional fields you want to display here -->
            </table>
        </div>
    <?php } else {
        echo "<h1 align='center' style='color:red'>No result Found</h1><br><p align='center'><a href='batchmentorstudents.php'>Go Back</a></p>";
    }
} ?>

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
    header("Location: ../../../index.php");
}
