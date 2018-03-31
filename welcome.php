<?php
//Author: Solon Pitts
session_start();

if(!isset($_SESSION['sess_username'])) {
  header("location: login.html");
  exit();
}

$con=mysqli_connect("localhost","Solon","speakeasy","event_photo");
if (!$con) {
	die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION['sess_username'];
$query = "SELECT * FROM customer WHERE username = '$username';";
$result = mysqli_query ($con,$query);
$userData = mysqli_fetch_array($result);
$email = $userData['email'];
echo "<h1>Welcome, $username.</h1>";

//display images
$query_photo = "SELECT * FROM photo WHERE username = '$username';";
$photos = mysqli_query ($con,$query_photo);
while($photo = mysqli_fetch_array($photos)){
	$photo_id = $photo['id'];
	echo "<h3>Photo ID: $photo_id</h3>";
	echo "<img src = 'resources/$photo_id.png'/>";
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Welcome</title>
  </head>
 
  <body>
  </body>
</html>
