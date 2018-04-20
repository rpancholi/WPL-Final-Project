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

$con=mysqli_connect($host,$user,$pass,$db);
if (!$con) {
	die("Connection failed: " . mysqli_connect_error());
}
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
            <section class="frame">
                <?php ?>
            </section>
            <section class="mat">
                <?php ?>
            </section>
            <section class="size">
                <?php ?>
            </section>
        </div>
    </div>
</body>
</html>