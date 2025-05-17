<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    include("../../../config/database.php");

    $id = $_SESSION['id'];
    $eid = $_SESSION['username'];
    $sql = "SELECT * FROM teachers WHERE eid = '$eid'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $fname = ucfirst($row['fname']);
        $lname = ucfirst($row['lname']);
        $center = $row['center'];
        $course = $row['course'];
        $status = $row['status'];
    }

    $ydate = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
    $selectedBatch = isset($_POST['batch']) ? $_POST['batch'] : '';

    $timestamp = strtotime($ydate);
    $day = date('l', $timestamp);

    if ($status == 'yes' || $status == 'Yes') {
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TimeTable-Teachers</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h2 align="center" style="color: blue"><?php echo ucfirst($center) . ' (' . strtoupper($course) . ')' ?></h2>
<div class="header">
    <span style="font-size:30px;cursor:pointer" class="logo" onclick="openNav()">&#9776; Menu </span>
    <div class="header-right">
        <a href="profile.php">
            <?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?></a>
    </div>
</div>
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="index.php" class="logo"><span style="color:red;font-size:25px">ISE</span></a>
    <a href="profile.php"><?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?></a>
    <a href="index.php">Home</a>
    <a href="search.php">Search Student Information</a>
    <a href="timetable.php">TimeTable</a>
    <a href="update_password.php">Update Password</a>
    <a href="../../../logout.php">Logout</a>
</div>
<div align="center" style="padding: 8px">
    <form action="timetable.php" method="post">
        <h3>Choose date (mm/dd/yyyy)</h3>
        <input type="date" name="date" value="<?php echo $ydate; ?>">
        <h3>Select Batch</h3>
        <select name="batch">
            <option value="">--Select Batch--</option>
            <option value="batch1" <?php if ($selectedBatch == 'batch1') echo 'selected'; ?>>Batch 1</option>
            <option value="batch2" <?php if ($selectedBatch == 'batch2') echo 'selected'; ?>>Batch 2</option>
            <option value="batch3" <?php if ($selectedBatch == 'batch3') echo 'selected'; ?>>Batch 3</option>
            <option value="batch4" <?php if ($selectedBatch == 'batch4') echo 'selected'; ?>>Batch 4</option>
            <option value="batch5" <?php if ($selectedBatch == 'batch5') echo 'selected'; ?>>Batch 5</option>
        </select>
        <input type="submit" name="submit" value="Submit">
    </form>
</div>
<div style="padding-left:20px; float: left;border-left: 6px solid red;background-color: lightgrey;width: 100%">
    <h1 align="center">Time Table</h1>
    <p align="center"><?php echo $ydate . '<br>(' . $day . ')' ?></p>
    <table border="2" align="center" cellpadding="5px">
        <tr>
            <th>S.No</th>
            <th>Timing</th>
            <th>Subject name</th>
            <th>Batch</th>
            <th>Mentor EID</th>
        </tr>
        <?php
        $sql_time = "SELECT * FROM timetable WHERE center = '$center' AND course = '$course' AND day = '$day'";
        if (!empty($selectedBatch)) {
            $sql_time .= " AND batch = '$selectedBatch'";
        }
        $sql_time .= " AND eid = '$eid'";

        $sql_time_result = mysqli_query($conn, $sql_time);
        if (mysqli_num_rows($sql_time_result) > 0) {
            $j = 0;
            while ($rown = mysqli_fetch_assoc($sql_time_result)) {
                $j++;
                $time = $rown['timing'];
                $subject = $rown['subject'];
                $batch = $rown['batch'];

                $sql_find_mentor = "SELECT * FROM batches WHERE batch = '$batch' AND center = '$center'";
                $sql_find_mentor_result = mysqli_query($conn, $sql_find_mentor);
                $mentorid = "N/A";
                if ($rowm = mysqli_fetch_assoc($sql_find_mentor_result)) {
                    $mentorid = $rowm['mentor'];
                }
        ?>
        <tr>
            <td><?php echo $j; ?></td>
            <td><?php echo $time; ?></td>
            <td><?php echo $subject; ?></td>
            <td><?php echo $batch; ?></td>
            <td><?php echo $mentorid; ?></td>
        </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='5' align='center'>No timetable data found for the selected batch and date.</td></tr>";
        }
        ?>
    </table>
</div>
<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>
<style>
    /* Your existing CSS styles */
</style>
</body>
</html>
<?php
    } else {
        echo "<h1>Your account is deactivated by admin. Kindly contact Admin for further assistance.</h1>";
    }
} else {
    header("Location: ../../../index.php");
    exit();
}
?>