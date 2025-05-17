<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    include("../../config/database.php");
    $id = $_SESSION['id'];
    $eid = $_SESSION['username'];
    $sql = "SELECT * FROM teachers WHERE eid = '$eid'";
    $result = mysqli_query($conn, $sql);
    $resultcheck = mysqli_num_rows($result);
    if ($row = mysqli_fetch_assoc($result)) {
        $fname = ucfirst($row['fname']);
        $lname = ucfirst($row['lname']);
        $center = $row['center'];
        $course = $row['course'];
        $status = $row['status'];
    }
    if ($status == 'yes' || $status == 'Yes') {
        if (isset($_GET['res'])) {
            if ($_GET['res'] == 'success') {
                echo '<script>alert("Successfully done")</script>';
            }
            if ($_GET['res'] == 'fail') {
                echo '<script>alert("Failed Try Again")</script>';
            }
        }
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Admin-ISE</title>
            <link rel="stylesheet" type="text/css" href="css/style.css">
            <style>
                .linking {
                    background-color: #ddffff;
                    padding: 7px;
                    text-decoration: none;
                }

                .linking:hover {
                    background-color: blue;
                    color: white;
                }

                input, button, select {
                    padding: 5px;
                    border: 2px solid blue;
                    border-radius: 10px;
                    margin: 2px;
                }

                input[type=submit], button {
                    width: 200px;
                }

                input:hover {
                    background-color: pink;
                }

                input[type=submit]:hover {
                    background-color: green;
                    color: white;
                }
            </style>
        </head>
        <body>
        <h2 align="center" style="color: black">ADD STUDENT</h2>
        <div class="header">
            <span style="color:white;font-size:30px;cursor:pointer" class="logo" onclick="openNav()">&#9776; MENU </span>
            <div class="header-right">
                <a href="profile.php">
                    <?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?></a>
            </div>
        </div>
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="index.php" class="logo"><span style="color:white;font-size:30px">ISE</span></a>
            <a href="profile.php"><?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?></a>
            <a href="index.php">Home</a>
            <a href="student.php">Student</a>
            <a href="teachers.php">Teachers</a>
            <a href="add.php">Add TimeTable/batch</a>
            <a href="update_password.php">Update Password</a>
            <a href="../../logout.php">Logout</a>
        </div>

        <div align="center" style="background-color: pink;padding: 10px">
            <a href="student.php?addstudent=true" class="linking" style="color:black">Add Student</a>
            <a href="student.php?updatestudent=true" class="linking" style="color:black">Update Student</a>
            <a href="student.php?viewbystudent=true" class="linking" style="color:black">View Students by Batch</a>
        </div>

        <?php
        if (isset($_GET['viewbystudent'])) {
            // Form to select a batch
            ?>
            <div align="center">
                <h4>Select Batch to View Students</h4>
                <form method="get" action="student.php">
                    Batch: <select name="batch">
                             <option value="none">Select Batch</option>
                        <?php
                        // Get batches for the current center and course
                        $sql_get_batch = "SELECT * FROM batches WHERE center='$center' AND course='$course'";
                        $sql_get_batch_query = mysqli_query($conn, $sql_get_batch);
                        while ($rom = mysqli_fetch_assoc($sql_get_batch_query)) { ?>
                            <option value="<?php echo $rom['batch'] ?>"><?php echo $rom['batch'] ?></option>
                        <?php }
                        ?>
                    </select>
                    <input type="submit" name="submit_batch">
                </form>
            </div>
        <?php
        }

        if (isset($_GET['submit_batch']) && $_GET['batch'] != 'none') {
            $batch_selected = mysqli_real_escape_string($conn, $_GET['batch']);
            
            // Fetch students from the selected batch
            $sql_get_students = "SELECT * FROM students WHERE batch='$batch_selected' AND center='$center' AND course='$course'";
            $result_students = mysqli_query($conn, $sql_get_students);
            
            if (mysqli_num_rows($result_students) > 0) {
                ?>
                <div align="center">
                    <h3>Students in Batch: <?php echo $batch_selected; ?></h3>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <tr>
                            <th>SID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Class</th>
                            <th>10th Marks</th>
                            <th>12th Marks</th>
                            <th>Actions</th>
                        </tr>
                        <?php
                        while ($row = mysqli_fetch_assoc($result_students)) {
                            echo "<tr>";
                            echo "<td>" . $row['sid'] . "</td>";
                            echo "<td>" . $row['fname'] . "</td>";
                            echo "<td>" . $row['lname'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['phone'] . "</td>";
                            echo "<td>" . $row['class'] . "</td>";
                            echo "<td>" . $row['10mark'] . "</td>";
                            echo "<td>" . $row['12mark'] . "</td>";
                            echo "<td><a href='student.php?updatestudent=true&studentid=" . $row['sid'] . "'>Update</a></td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
                <?php
            } else {
                echo '<script>alert("No students found in this batch.")</script>';
            }
        }
        ?>

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
        ?>
        <h1>Your account is deactivated by admin due to some reasons. Kindly contact Admin for further assistance.</h1>
        <?php
    }
} else {
    header("Location: ../../index.php");
}
?>
