<?php
// Author: Rupesh Pancholi
session_start();

// Database Connection Variables
$host = "localhost";
$port = 3306;
$user = "Solon"; 
$pass = "speakeasy";
$db = "event_photo";


//make sure someone is logged in
if(!isset($_SESSION['sess_username'])) {
    header("location: login.html");
    exit();
}

// Check connection to event_photo database
$con=mysqli_connect($host,$user,$pass,$db);
if (!$con) {
	die("Connection failed: " . mysqli_connect_error());
}

// SQL query to select all frames from frames table
$frames_sql = "SELECT * FROM frames;";
$frames_result = mysqli_query($con, $frames_sql);

// SQL query to select all frames from frames table
$mats_sql = "SELECT * FROM mats;";
$mats_result = mysqli_query($con, $mats_sql)


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Photo Services</title>
    <link rel="stylesheet" type="text/css" href="css/services.css" />
</head>
<body>
    <div class="wrapper">
        <div class="header">
        </div>
        <div class="content">
            <section>
                <h1>Photo Printing Services</h1>
            </section>
            <section class="frame">
                <h1>Frames</h1>
                <?php
                    if($frames_result->num_rows == 0){
                        echo "SOMETHING WENT WRONG!";
                    }
                    while($row = mysqli_fetch_array($frames_result)){
                        echo "<img src='resources/frames/".$row['image_file_name'].".jpg"."' alt='Frame Image' title='".$row['frame_name']."'>";
                        echo $row['frame_name'];
                    }
                ?>
            </section>
            <section class="mat">
                <h1>Mats</h1>
                <?php
                    if($mats_result->num_rows == 0){
                        echo "SOMETHING WENT WRONG!";
                    }
                    while($row = mysqli_fetch_array($mats_result)){
                        echo "<img src='resources/mats/".$row['image_file_name'].".jpg"."' alt='Mat Image' title='".$row['mat_name']."'>";
                    }
                ?>
            </section>
            <section class="size">
                <?php ?>
            </section>
        </div>
    </div>
</body>
</html>