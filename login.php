<?php
//Author: Solon Pitts
session_start();

// Database Connection Variables
$host = "localhost";
$port = 3306;
$user = "Solon"; 
$pass = "speakeasy";
$db = "event_photo";

// define variables and set to empty values
$username = $password = "";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$username = htmlspecialchars($_POST["username"]);
	$password = htmlspecialchars($_POST["password"]);
	
	if(empty($username) || empty($password)){ //if any of the fields are empty, go back
		header('Location: login.html');
		exit();
	}
	else //all fields non-empty
	{
		$con=mysqli_connect($host,$user,$pass,$db);
		if (!$con) {
			die("Connection failed: " . mysqli_connect_error());
		}
		
		$query = "SELECT username, password FROM customer WHERE username = '$username';";
		$result = mysqli_query ($con,$query);
		if($userData = mysqli_fetch_array($result)){
			if($password == $userData['password'])
			{
				session_regenerate_id(); 
				$_SESSION['sess_username'] = $username;
				session_write_close();
				header('Location: photos.php#home');
				exit();
			}
		}
		header('Location: login.html');
		exit();
	}
}
?>
