<?php
// Database connection
$servername = "localhost";  // Replace with your database server name
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "mentor_connect"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch all records from marks table
$sql = "SELECT id, sid, course, subject, examname, marksobtain, totalmarks, eid, center, batch, dateofexam FROM marks";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks Table</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Marks Records</h1>

    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>SID</th>
                    <th>Course</th>
                    <th>Subject</th>
                    <th>Exam Name</th>
                    <th>Marks Obtained</th>
                    <th>Total Marks</th>
                    <th>EID</th>
                    <th>Center</th>
                    <th>Batch</th>
                    <th>Date of Exam</th>
                </tr>";

        // Fetch and display each row of data
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["sid"] . "</td>
                    <td>" . $row["course"] . "</td>
                    <td>" . $row["subject"] . "</td>
                    <td>" . $row["examname"] . "</td>
                    <td>" . $row["marksobtain"] . "</td>
                    <td>" . $row["totalmarks"] . "</td>
                    <td>" . $row["eid"] . "</td>
                    <td>" . $row["center"] . "</td>
                    <td>" . $row["batch"] . "</td>
                    <td>" . $row["dateofexam"] . "</td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No records found.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>

</body>
</html>
