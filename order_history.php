<?php
//Author: Solon Pitts
session_start();

// Database Connection Variables
$host = "localhost";
$port = 3306;
$user = "Solon"; 
$pass = "speakeasy";
$db = "event_photo";

//page variables
$page_length = 5;
if(isset($_POST['searchButton']) || !isset($_POST['page_num'])){
	$page_num = 1;
	$page_max = $page_length;
}
else if(isset($_POST['next_page'])){
	$page_num = intval($_POST['page_num']);
	$page_num++;
	$page_max = $page_length*$page_num;
}
else if(isset($_POST['prev_page'])){
	$page_num = intval($_POST['page_num']);
	$page_num--;
	$page_max = $page_length*$page_num;
}
else{
	$page_num = intval($_POST['page_num']);
	$page_max = $page_length*$page_num;
	
}
$page_min = $page_max-$page_length;
$order_count=0;

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

if(!$admin)
	$query = "SELECT * FROM purchase_summary WHERE username = '$username';";
else
	$query = "SELECT * FROM purchase_summary;";

$history = mysqli_query($con, $query);
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/order_history.css">
    <title>Order History</title>
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
            </div><aside>
            <div class = "navbar-item sign-out">
                <a href=login.html>Sign Out</a>
            </aside></div>
        </div>
    </div>
    <div class="banner">
        <h1>Photo Framing Services</h1>
        <h1>Frame your heart out</h1>
    </div>
	
	<form action="" method="post" id="searchForm">
	<!--Left column-->
	<div class="column">
	</div>
	
	<!--Main page-->
	<div class="main">
	<h2>Order History</h2>
	<?php //display the photos from the constructed query
		// $summary = mysqli_fetch_array($history);
		// echo ($summary['username']);
		while($summary = mysqli_fetch_array($history)){
			$order_count++;
			if($order_count>$page_min && $order_count <= $page_max){
				$user = $summary['username'];
				$description = $summary['description'];
				$date = $summary['purchase_date'];

				echo "<div class = 'order'>";
				echo "<h3>Order Number: $order_count</h3>";
				echo "<h3>User: $user</h3>";
				echo "<h3>Purchase Date: $date</h3>";
				echo "<h3>Purchase Details: $description</h3>";
				echo "</div>";
			}
		}
	?>
	<br>
	<div>
		<?php //add prev page or next page buttons contextually
		if($page_min > 0)
			echo "<input type='submit' action='' name='prev_page' value='Previous Page'>";
		echo " Page: $page_num ";
		if($order_count > $page_max)
			echo "<input type='submit' action='' name='next_page' value='Next Page'>";
		?>
	</div>
	<input type="hidden" id='page_num' name ="page_num" value=<?php echo "$page_num"; ?> />
	</div>
	</form>
	<!--Bottom Bar-->
	<ul>
		<li><a href="#home">Back to top</a></li>
	</ul>
   </body>
</html>
