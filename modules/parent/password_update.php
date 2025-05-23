<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 22-07-2018
 * Time: 18:12
 */

session_start();
if(isset($_SESSION['id']) && isset($_SESSION['username'])){
    include("../../config/database.php");
    $id = $_SESSION['id'];
    $pid = $_SESSION['username'];
    $sql = "SELECT * FROM students WHERE sid = (SELECT sid FROM students WHERE pid = '$pid')";
    $result = mysqli_query($conn, $sql);
    $resultcheck = mysqli_num_rows($result);
    if ($row = mysqli_fetch_assoc($result)) {
        $fname = ucfirst($row['fname']);
        $lname = ucfirst($row['lname']);
        $center = $row['center'];
        $course = $row['course'];
        $batch = $row['batch'];
    }
    $ydate = date('Y-m-d');
    $day = date("l");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Marks-Parents</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            input,button,select{
                padding: 5px;
                border: 2px solid blue;
                border-radius: 10px;
                margin: 2px;
            }
            input[type=submit],button{
                width: 200px;
            }
            input:hover{
                background-color: lightblue;
            }
            input[type=submit]:hover{
                background-color: green;
                color: white;
            }

        </style>
    </head>
    <body>
    <div class="header">

        <span style="font-size:30px;cursor:pointer" class="logo" onclick="openNav()">&#9776; MENU </span>

        <div class="header-right">
            <a href="../../logout.php">
                <?php echo "Logout" ?></a>
        </div>
    </div>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="index.php" class="logo"><span style="color:red;font-size:70px">ISE</span></a>
        <a href="index.php">Home</a>
        <a href="attendance.php">Attendance</a>
        <a href="timetable.php">TimeTable</a>
        <a href="marks.php">Marks</a>
        <a href="fees.php">Fees</a>
        <a href="complaint.php">Complaint</a>
        <a href="password_update.php">Update Password</a>
        <a href="../../logout.php">Logout</a>
    </div>
    <div align="center">
        <h4>Update Password -<span style="color: blue;"> <?php echo $pid?></span></h4>
        <form  method="post">
            <b>Old Password: </b><input type="password" name="oldpassword" placeholder="Enter Old Password" required><br>
            <b>New Password: </b><input type="password" name="newpassword_one" placeholder="Enter New Password" required><br>
            <b>New Password Again: </b><input type="password" name="newpassword_again" placeholder="Enter New Password Again" required><br>
            <input type="submit" name="changepassword" value="Change Password">
        </form>
    </div>

    <?php
    if(isset($_POST['changepassword'])){
        $get_old_password=$_POST['oldpassword'];
        $get_new_password=$_POST['newpassword_one'];
        $get_new_password_again=$_POST['newpassword_again'];

        $searvh_pass = "SELECT * FROM users WHERE username='$pid' AND password='$get_old_password'";
        $searvh_pass_get = mysqli_query($conn,$searvh_pass);
        $searvh_pass_check = mysqli_num_rows($searvh_pass_get);
        if($searvh_pass_check > 0){
            if($get_new_password===$get_new_password_again){
                $update_users = "UPDATE users SET password='$get_new_password' WHERE username='$pid' AND type='parent'";
                $update_users_q = mysqli_query($conn,$update_users);
                if($update_users_q){
                    echo '<script>alert("Password Update Success")</script>';
                }else{
                    echo '<script>alert("SomeThing Went Wrong. Try Again after some time")</script>';
                }
            }else{
                echo '<p align="center" style="color: red">*password and confirm password does not match</p>';
            }
        }else{
            echo '<p align="center" style="color: red">*old password is wrong</p>';
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
    <style>
        .feepay{
            width: 200px;
            font-size: 20px;
            color: red;
            border-radius: 10px;
            border-color: green;
        }
        .feepay:hover{
            background-color: green;
            color: white;
        }
    </style>
    </body>
    </html>
    <?php
}else{
    header("Location: ../../index.php");
}
?>
