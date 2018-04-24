<?php
//Author: Solon Pitts
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

$username = $_SESSION['sess_username'];
$query = "SELECT * FROM customer WHERE username = '$username';";
$result = mysqli_query ($con,$query);
$userData = mysqli_fetch_array($result);
$email = $userData['email'];
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/cart.css">
    <title>Shopping Cart</title>
  </head>
 
  <body>
	<a class="anchor" id="home"></a>
	<!--Nav Bar-->
	<div class="header">
        <div id="navbar">
            <div class = "user-welcome">
                <?php echo "Welcome ".ucfirst($_SESSION['sess_username'])."!" ?>
            </div>
			<div class = "navbar-item">
                <a href=photos.php#home>View Photos</a>
            </div>
			<div class = "navbar-item">
                <a href=order_history.php>Purchase History</a>
            </div
			<aside>
            <div class = "navbar-item sign-out">
                <a href=login.html>Sign Out</a>
            </aside></div>
        </div>
    </div>
    <div class="banner">
        <h1>Photo Framing Services</h1>
        <h1>Frame your heart out</h1>
    </div>
	
	<form action="" method="post" id="checkoutForm">
	<!--Left column-->
	<div class="column">
	</div>
	
	<!--Main page-->
	<div class="main">
	<h2>Shopping Cart</h2>
	<?php //display the photos from the session variable
		
	?>
	</div>
	</form>
   </body>
</html>
