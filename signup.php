<?php
//Author: Solon Pitts
session_start();
// define variables and set to empty values
$username = $password = $email = "";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$username = htmlspecialchars($_POST["username"]);
	$password = htmlspecialchars($_POST["password"]);
	$email = htmlspecialchars($_POST["email"]);
	$phone = htmlspecialchars($_POST["phone"]);
	$address = htmlspecialchars($_POST["address"]);
	
	if(empty($username) || empty($password) || empty($email)){ //if any of the fields are empty, go back
		header('Location: signup.html');
		exit();
	}
	else //all fields non-empty
	{
		$con=mysqli_connect("localhost","Solon","speakeasy","event_photo");
		if (!$con) {
			die("Connection failed: " . mysqli_connect_error());
		}
		
		$query = "SELECT * FROM customer WHERE username = '$username' OR email = '$email';";
		$result = mysqli_query ($con,$query);
		if(mysqli_num_rows($result)==0){
			$hash = hash('sha256', $password);
			$insert = "INSERT INTO customer VALUES ('$username','$hash','$email','$phone','$address',false);";
			$insert_result = mysqli_query($con,$insert);
			session_regenerate_id(); 
			$_SESSION['sess_username'] = $username;
			session_write_close();
			header('Location: photos.php#home');
			exit();
		}
		header('Location: signup.html');
		exit();
	}
}
?>