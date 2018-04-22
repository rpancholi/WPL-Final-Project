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
$photo_count=0;

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

//Check if a search input or filter is set and construct the query
if (isset($_POST['search']) && $_POST['search']!="") {
	$search = $_POST['search'];
	if(isset($_POST['filter']) && $_POST['filter']!=""){
		$filter = $_POST['filter'];
		$query_photo = "SELECT * FROM photo WHERE username = '$username' AND event_name = '$filter' AND (event_name LIKE '%$search%' OR event_date LIKE '%$search%');";
	}
	else
		$query_photo = "SELECT * FROM photo WHERE username = '$username' AND (event_name LIKE '%$search%' OR event_date LIKE '%$search%');";
}
else{
	if(isset($_POST['filter']) && $_POST['filter']!=""){
		$filter = $_POST['filter'];
		$query_photo = "SELECT * FROM photo WHERE username = '$username' AND event_name = '$filter';";
	}
	else
		$query_photo = "SELECT * FROM photo WHERE username = '$username';";
}
$query_filter = "SELECT DISTINCT event_name FROM photo WHERE username = '$username';";
$filter_results = mysqli_query ($con,$query_filter);

$photos = mysqli_query ($con,$query_photo);
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/photos.css">
    <title>Photos</title>
  </head>
 
  <body>
	<a class="anchor" id="home"></a>
	
	<!--Nav Bar-->
	<ul>
		<li><a href="photos.php">Photos</a></li>
		<li><a href="login.html">Sign out</a></li>
	</ul>
	
	<!--Left column-->
	<div class="column">
	</div>
	
	<!--Main page-->
	<form class="main" action="services.php" method="post" id="searchForm">
		<div class = "form">
			<label for="search" class="black">Search for event name or date:</label>
			<input type="text" id="search" name="search" value="<?php echo isset($_POST['search']) ? $_POST['search'] : '' ?>" autofocus/>
			<input type="submit" name="searchButton" value="Search">
			<aside>
			<label for="filter" class="black">Filter by Event:</label>
			<select id="filter" name="filter">
				<option value=''></option>
				<?php //Add filter options to the select box
				while($filter_result = mysqli_fetch_array($filter_results)){
					$event_name = $filter_result['event_name'];
					if(isset($_POST[filter]) && $event_name == $_POST[filter])
						echo "<option value='$event_name' selected>$event_name</option>";
					else
						echo "<option value='$event_name'>$event_name</option>";
				}
				?>
			</select>
			</aside>
		</div>
	<h2>User Photos</h2>
	
	<?php //display the photos from the constructed query
		while($photo = mysqli_fetch_array($photos)){
			$photo_count++;
			if($photo_count>$page_min && $photo_count <= $page_max){
				echo"<div class='photo'>";
				$photo_id = $photo['id'];
				echo "<img src = 'resources/$photo_id.png'/>";
				echo "<h3>ID: $photo_id.</h3>";
				$photo_name = $photo['event_name'];
				echo "<h3>Event Name: $photo_name</h3>";
				$photo_date = $photo['event_date'];
				echo "<h3>Date: $photo_date</h3>";
				$selected = $photo['selected'];
				echo "<label for='$photo_id'>Select Photo:</label>";
				//check if photo previously checked or not
				if(isset($_POST['submit']) && isset($_POST[$photo_id]) && !$selected){
					$query_select = "UPDATE photo SET selected = true WHERE id = $photo_id;";
					mysqli_query ($con,$query_select);	
					$selected=true;
				}
				else if(isset($_POST['submit']) && !isset($_POST[$photo_id]) && $selected){
					$query_deselect = "UPDATE photo SET selected = false WHERE id = $photo_id;";
					mysqli_query ($con,$query_deselect);
					$selected=false;
				}
				//display checkbox as checked or not
				if($selected)
					echo "<input type='checkbox' name = '$photo_id' value = 'select' checked>";
				else
					echo "<input type='checkbox' name = '$photo_id' value = 'select'>";
				echo "<br>";
				
				echo"</div>";
			}
		}
	?>
	<div class="form">
		<label for="submit" class="black">Save selection:</label>
		<input type="submit" action="" name="submit" value="Submit">
	</div>
	<br>
	<div>
		<?php //add prev page or next page buttons contextually
		if($page_min > 0)
			echo "<input type='submit' action='' name='prev_page' value='Previous Page'>";
		echo "Page: $page_num";
		if($photo_count > $page_max)
			echo "<input type='submit' action='' name='next_page' value='Next Page'>";
		?>
	</div>
	<input type="hidden" id='page_num' name ="page_num" value=<?php echo "$page_num"; ?> />
	</form>
	<!--Bottom Bar-->
	<ul>
		<li><a href="#home">Back to top</a></li>
	</ul>
   </body>
</html>
