<?php
//Author: Rupesh Pancholi
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
$admin = $userData['admin_rights'];

//check to update photo
if($admin){
	$tempQuery  = "SELECT * FROM photo;";
	$temps = mysqli_query ($con,$tempQuery);
	while($temp = mysqli_fetch_array($temps)){
		$temp_id = $temp['id'];
		if(isset($_POST['update_photo_'.$temp_id])){
			$imgId = $temp_id;
			$event_user = $temp['username'];
			$event_name = $temp['event_name'];
			$event_date = $temp['event_date'];
		}
	}
}

//check if user has updated photo
if(isset($_POST['save'])){
	$imgId = $_POST['id'];
	$event_user = $_POST['user'];
	$event_name = $_POST['name'];
	$event_date = $_POST['date'];
	$mysqldate = date ("Y/m/d", strtotime($event_date));
	if(isset($_POST['update_id']) && $_POST['update_id'] != 0){
		$prev_id = $_POST['update_id'];
		$query = "UPDATE photo SET id=$imgId, username='$event_user', event_name='$event_name', event_date='$mysqldate' WHERE id = $prev_id;";
		if(!mysqli_query ($con,$query)){ //query failed
			$error = mysqli_error($con);
			echo "<script type='text/javascript'>alert('Error: $error');</script>";
		}
		else
			echo "<script type='text/javascript'>alert('Saved!');</script>";
	}
	else //adding a new photo
	{
		$query = "INSERT INTO photo VALUES ('$event_user', $imgId, '$event_name', '$mysqldate', false, false);";
		if(!mysqli_query ($con,$query)){ //query failed
			$error = mysqli_error($con);
			echo "<script type='text/javascript'>alert('Error: $error');</script>";
			$imgId = 0;
		}
		else
			echo "<script type='text/javascript'>alert('Saved!');</script>";
	}
}

?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/update_photo.css">
    <title>Update Photo</title>
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
                <a href=services.php>Services</a>
            </div>
			<div class = "navbar-item">
                <a >Purchase History</a>
            </div><aside>
            <div class = "navbar-item sign-out">
                <a href=login.html>Sign Out</a>
            </aside></div>
        </div>
    </div>
    <div class="banner">
        <h1>S & R Photography</h1>
        <h1>Frame your heart out</h1>
    </div>
	
	
	<!--Left column-->
	<div class="column">
	</div>
	
	<!--Main page-->
	<form action="" class="main" method="post" id="form">
		<h2><?php echo (isset($imgId) && $imgId!=0) ? 'Update Photo' : 'Add Photo' ?></h2>
		<?php
			if(isset($imgId) && $imgId!=''){
				echo "<div class='form'>";
				echo "<img src = 'resources/$imgId.png' alt='Image Placeholder'/>";			
				echo "</div>";
			}
		?>
		<div class = "form">
			<label for="id" class="black">Resource ID:</label>
			<input type="text" id="id" name="id" value="<?php echo (isset($imgId) && $imgId!=0) ? $imgId : '' ?>" required/>
		</div>
		<div class = "form">
			<label for="name" class="black">Image File Name:</label>
			<input type="text" id="name" name="name" value="<?php echo isset($event_name) ? $event_name : '' ?>" required/>
		</div>
		<div class = "form">
			<label for="price" class="black">Price:</label>
			<input type="text" id="price" name="price" value="<?php echo isset($event_date) ? $event_date : '' ?>" required/>
		</div>
		<div class = "form">
			<label for="inventory" class="black">Inventory:</label>
			<input type="text" id="inventory" name="inventory" value="<?php echo isset($event_date) ? $event_date : '' ?>" required/>
		</div>
		<div class="form">
			<input type="submit" name="save" value="Save">
		</div>
		<input type="hidden" id='update_id' name ="update_id" value=<?php echo (isset($imgId) && $imgId!=0) ? $imgId : ''?> />
	</form>
   </body>
</html>
