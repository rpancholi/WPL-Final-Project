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
$admin = $userData['admin_rights'];

//check to delete photo
if($admin){
	$tempQuery  = "SELECT id FROM photo;";
	$temps = mysqli_query ($con,$tempQuery);
	while($temp = mysqli_fetch_array($temps)){
		$temp_id = $temp['id'];
		if(isset($_POST['delete_photo_'.$temp_id])){
			$deleteQuery = "UPDATE photo SET deleted = true WHERE id = $temp_id;";
			mysqli_query($con,$deleteQuery);
		}
	}
	
}

//Check if a search input or filter is set, and if the user is an admin, and construct the query
if (isset($_POST['search']) && $_POST['search']!="") {
	$search = $_POST['search'];
	if(isset($_POST['filter']) && $_POST['filter']!=""){
		$filter = $_POST['filter'];
		if($admin)
			$query_photo = "SELECT * FROM photo WHERE event_name = '$filter' AND (event_name LIKE '%$search%' OR event_date LIKE '%$search%');";
		else
			$query_photo = "SELECT * FROM photo WHERE username = '$username' AND event_name = '$filter' AND (event_name LIKE '%$search%' OR event_date LIKE '%$search%');";
	}
	else{
		if($admin)
			$query_photo = "SELECT * FROM photo WHERE (event_name LIKE '%$search%' OR event_date LIKE '%$search%');";
		else
			$query_photo = "SELECT * FROM photo WHERE username = '$username' AND (event_name LIKE '%$search%' OR event_date LIKE '%$search%');";
	}
}
else{
	if(isset($_POST['filter']) && $_POST['filter']!=""){
		$filter = $_POST['filter'];
		if($admin)
			$query_photo = "SELECT * FROM photo WHERE event_name = '$filter';";
		else
			$query_photo = "SELECT * FROM photo WHERE username = '$username' AND event_name = '$filter';";
	}
	else{
		if($admin)
			$query_photo = "SELECT * FROM photo;";
		else
			$query_photo = "SELECT * FROM photo WHERE username = '$username';";
	}
}
if(!$admin)
	$query_filter = "SELECT DISTINCT event_name FROM photo WHERE username = '$username';";
else
	$query_filter = "SELECT DISTINCT event_name FROM photo;";
$filter_results = mysqli_query ($con,$query_filter);

$tempPhotoQuery = $query_photo;
$photos = mysqli_query ($con,$tempPhotoQuery);
while($photo = mysqli_fetch_array($photos)){
	$deleted = $photo['deleted'];
	$photo_id = $photo['id'];
	if(isset($_POST[$photo_id])){
		$_SESSION['selected_id'] = $photo_id;
		header('Location: services.php');
	}
}

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
	<div class="header">
        <div id="navbar">
            <div class = "user-welcome">
                <?php echo "Welcome ".ucfirst($_SESSION['sess_username'])."!" ?>
            </div>
			<div class = "navbar-item">
                <a href=order_history.php>Purchase History</a>
            </div><aside>
            <div class = "navbar-item sign-out">
                <a href=login.html>Sign Out</a>
            </aside></div>
        </div>
    </div>
    <div class="banner">
		<h1>S & R</h1>
        <h1>Event Photography Services</h1>
        <h3>Look through your photos and choose one you like.</h3>
    </div>
	
	<form action="" method="post" id="searchForm">
	<!--Left column-->
	<div class="column">
		<label for="filter" class="sidebar_item">Filter by Event:</label>
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
	</div>
	
	<!--Main page-->
	<div class="main">
		<div class = "form">
			<label for="search" class="black">Search for event name or date:</label>
			<input type="text" id="search" name="search" value="<?php echo isset($_POST['search']) ? $_POST['search'] : '' ?>" autofocus/>
			<input type="submit" id="searchButton" name="searchButton" value="Search">	
		</div>
	<h2>User Photos</h2>
	<?php
		if($admin)
			echo "<input type='submit' onclick=submitForm('update_photo.php#home') class='admin' name='add_photo' value='Add Photo'>";
	?>
	<?php //display the photos from the constructed query
		while($photo = mysqli_fetch_array($photos)){
			$deleted = $photo['deleted'];
			if(!$deleted){
				$photo_count++;
				if($photo_count>$page_min && $photo_count <= $page_max){
					$photo_id = $photo['id'];
					echo"<div class='photo'>";
					// echo "<img src = 'resources/$photo_id.jpeg'/>";
					echo "<a href='resources/$photo_id.jpeg' target='_blank'><img src='resources/$photo_id.jpeg'/></a>";
					echo "<h3>ID: $photo_id.</h3>";
					$photo_name = $photo['event_name'];
					echo "<h3>Event Name: $photo_name</h3>";
					$photo_date = $photo['event_date'];
					echo "<h3>Date: $photo_date</h3>";
					echo "<label for='$photo_id'>Select Photo: </label>";
					//check if photo previously checked or not
					
					echo "<input id='selectButton' type='submit' name = '$photo_id' value = 'Select'>";
					echo "<br>";
					if($admin){
						echo "<input type='submit' class='admin' onclick=submitForm('update_photo.php#home') name='update_photo_$photo_id' value='Update Photo'/>";
						echo "<input type='submit' class='admin' id='deleteButton' onclick='clicked(event)' name='delete_photo_$photo_id' value='Delete Photo'/>";
					}
					echo"</div>";
				}
			}
		}
	?>
	<br>
	<div>
		<?php //add prev page or next page buttons contextually
		if($page_min > 0)
			echo "<input type='submit'  id='previousPageButton' action='' name='prev_page' value='Previous Page'>";
		echo " Page: $page_num ";
		if($photo_count > $page_max)
			echo "<input type='submit' id='nextPageButton' action='' name='next_page' value='Next Page'>";
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
<script>
function clicked(e)
{
    if(!confirm('Are you sure you want to delete the item?'))e.preventDefault();
}

function submitForm(dest) {
    var form = document.getElementById('searchForm');
    form.action = dest;
    form.submit();
}
</script>
</html>

