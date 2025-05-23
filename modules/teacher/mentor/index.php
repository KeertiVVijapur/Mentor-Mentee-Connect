<?php


session_start();
if(isset($_SESSION['id']) && isset($_SESSION['username'])){
    include("../../../config/database.php");
    $id = $_SESSION['id'];
    $eid = $_SESSION['username'];
    $sql = "SELECT * FROM teachers WHERE eid = '$eid'";
    $result = mysqli_query($conn, $sql);
    $resultcheck = mysqli_num_rows($result);
    if($row = mysqli_fetch_assoc($result)){
        $fname= ucfirst($row['fname']);
        $lname = ucfirst($row['lname']);
        $center = $row['center'];
        $course = $row['course'];
        $status = $row['status'];
        $batchmentor = $row['batchmentor'];
    }
    if($status == 'yes' || $status == 'Yes') {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>ISE</title>
            <link rel="stylesheet" type="text/css" href="css/style.css">
        </head>
        <body>
        <h2 align="center" style="color: black">ISE</h2>
        <div class="header">

            <span style="font-size:30px;cursor:pointer" class="logo" onclick="openNav()">&#9776; Menu </span>

            <div class="header-right">
                <a href="profile.php">
                    <?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?></a>
            </div>
        </div>
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="index.php" class="logo"><span style="color:white;font-size:25px">ISE</span></a>
            <a href="profile.php"><?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?></a>
            <a href="index.php">Home</a>
            
            <a href="search.php">Search Student Information</a>
            <a href="batchmentorstudents.php">Students of <?php echo $batchmentor?></a>
       
            <a href="timetable.php">TimeTable</a>
    
            <a href="../../../logout.php">Logout</a>
        </div>
        
        <div style="padding-left:20px; float: left;border-left: 6px solid red;background-color: lightgrey;width: 50%">
        <div align="center">
            <h1 align="center">Daneshwari Noola(batch5-Mentor)</h1>
            <table border="2" align="center" cellpadding="5px">
               
    
                <?php
                $day = date("l");
                $sql_time = "SELECT * FROM timetable WHERE center = '$center'AND course = '$course' AND day ='$day' AND eid = '$eid'";
                $sql_time_result = mysqli_query($conn, $sql_time);
                $sql_time_result_check = mysqli_num_rows($sql_time_result);
                $j = 0;
                while ($rown = mysqli_fetch_assoc($sql_time_result)){
                $j++;
                $time = $rown['timing'];
                $subject = $rown['subject'];
                $batch = $rown['batch'];

                $sql_find_mentor = "SELECT * from batches WHERE batch = '$batch' AND center = '$center'";
                $sql_find_mentor_result = mysqli_query($conn,$sql_find_mentor);
                $sql_find_mentor_result_check = mysqli_num_rows($sql_find_mentor_result);
                if($sql_find_mentor_result_check>0){
                   if($rowm = mysqli_fetch_assoc($sql_find_mentor_result)){
                       $mentorid = $rowm['mentor'];
                   }
                }


                ?>
                <tr>
                    <td><?php echo $j; ?></td>
                    <td><?php echo $time; ?></td>
                    <td><?php echo $subject ?></td>
                    <td><?php echo $batch; ?></td>
                    <td><?php echo $mentorid ?></td>

                    <?php } ?>

            </table>
        </div>
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
    }else{
        ?>
        <h1>Your account is deactivated by admin due to some reasons. kindly contact Admin for further.</h1>
        <?php
    }
}else{
    header("Location: ../../index.php");
}

?>