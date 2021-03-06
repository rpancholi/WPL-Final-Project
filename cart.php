<?php
//Author: Solon Pitts Rupesh Pancholi
session_start();

// Database Connection Variables
$host = "localhost";
$port = 3306;
$user = "root"; 
$pass = "root";
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

// Get Cookie Variables

if(!isset($_COOKIE['id'])){
	header('Location: photos.php#home');
}

$pictureID =  $_COOKIE['id'];
$frame = $_COOKIE['frame'];
$mat = $_COOKIE['mat'];
$size = $_COOKIE['size'];
$totalPrice = $_COOKIE['price'];   

if(isset($_POST['checkout'])){ //write it to the DB
    // Get purchase quantity
    $quantity = $_POST['quantityInput'];

    // Check if inventory available
    // Frame Inventory
    $getFrameInventoryQuery = "SELECT inventory FROM frames WHERE name = '$frame';";
    $frameInvResult = mysqli_query($con,$getFrameInventoryQuery);
    $fRow = mysqli_fetch_array($frameInvResult);
    $frameInventory = $fRow['inventory'];
    // Mat Inventory
    $getMatInventoryQuery = "SELECT inventory FROM mats WHERE name = '$mat';";
    $matInvResult = mysqli_query($con,$getMatInventoryQuery);
    $mRow = mysqli_fetch_array($matInvResult);
    $matInventory = $mRow['inventory'];


    // Throw error if inventory not available
    if($quantity > $frameInventory){
        echo "<script type='text/javascript'>alert('Error: Not enough inventory of Frame: $frame, Please Select another style!');</script>";
        header("Refresh:0; url=services.php");
        die();
    }
    if($quantity > $matInventory){
        echo "<script type='text/javascript'>alert('Error: Not enough inventory of Mat: $mat, Please Select another style!');</script>";
        header("Refresh:0; url=services.php");
        die();
    }
    
	
    // Decrement Inventory
    $updateFrameInventoryQuery = "UPDATE frames SET inventory = (inventory - $quantity) WHERE name = '$frame';";
    $result = mysqli_query($con,$updateFrameInventoryQuery);
    $updateMatInventoryQuery = "UPDATE mats SET inventory = (inventory - $quantity) WHERE name = '$mat';";
    $result = mysqli_query($con,$updateMatInventoryQuery);

    // Insert Order Summary into database
	$date = date("Y/m/d");
	$price = $_POST['price'];
	$summary = "INSERT INTO purchase_summary (username, description, purchase_date) VALUES ('$username','Photo: $pictureID(x$quantity), Frame: $frame, Mat: $mat, Size: $size, Total Price: $price','$date')";
	if(mysqli_query($con,$summary)){
		echo "<script type='text/javascript'>alert('Purchased!');</script>";
		unset($_COOKIE['id']);
		unset($_COOKIE['frame']);
		unset($_COOKIE['mat']);
		unset($_COOKIE['size']);
        unset($_COOKIE['price']);

        header("location: order_history.php");
        // header("Refresh:1; url=photos.php");
	}
	else{ //query failed
		$error = mysqli_error($con);
		echo "<script type='text/javascript'>alert('Error: $error');</script>";
	}		
}
else if (isset($_POST['delete'])){ //remove from cart
	unset($_COOKIE['id']);
	unset($_COOKIE['frame']);
	unset($_COOKIE['mat']);
	unset($_COOKIE['size']);
	unset($_COOKIE['price']);
}
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/cart.css">
        <title>Shopping Cart</title>
    </head>

    <script>
        var orderSubtotal = null;

        window.onload = function() {
            orderSubtotal = document.getElementById("totalPrice").innerText.substr(1);
            var quantityInputField = document.getElementById("quantityInput");
            quantityInput.defaultValue = "1";
            quantityInputField.setAttribute("min","1");
            quantityInputField.setAttribute("max","100");
            quantityInput.setAttribute("onchange","updatePrice()");
        };

        function updatePrice(){
            var quantityInputField = document.getElementById("quantityInput").value;
            var totalPrice = document.getElementById("totalPrice");
			var price = document.getElementById("price");
            var newTotalPrice = parseInt(quantityInputField) * parseFloat(orderSubtotal); 
            totalPrice.innerText = "$"+String(newTotalPrice.toFixed(2));
			price.value = "$"+String(newTotalPrice.toFixed(2));
        }
		
		function clicked(e)
		{
			if(!confirm('Are you sure you want to place this order?'))e.preventDefault();
		}
    </script>
 
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
        <h1>S & R</h1>
        <h1>Photo Framing Services</h1>
    </div>
	
	<form action="" method="post" id="checkoutForm">
	<!--Left column-->
	<div class="column">
	</div>
	
	<!--Main page-->
	<div class="main">
    <h2>Shopping Cart</h2>
    
    <?php
        echo "<table id='orderConfirmationTable'>
        <caption> Your Order Summary </caption>
        <thead><tr><th>Picture</th><th>Frame</th><th>Mat</th><th>Size</th><th>Quantity</th><th>Total Price</th></tr></thead>";
		if(isset($_COOKIE['id'])){
			echo "<tr>";
			echo "<td align='center'>"."<img id='selectedPhoto' src = 'resources/".$pictureID.".jpeg'/>"."</td>";
			echo "<td align='center'>".$frame."</td>";
			echo "<td align='center'>".$mat."</td>";
			echo "<td align='center'>".$size."</td>";
			echo "<td id='quantitySelector' align='center'><input name='quantityInput' id='quantityInput' type='number' required></td>";
			echo "<td id='totalPrice' align='center'>".$totalPrice."</td>";
			echo "<td id='deleteButton' align='center'><input name='delete' id='delete' type='submit' value='Delete'></td>";
			echo "</tr>";
			echo "<input id='price' name='price' value='$totalPrice' type='hidden'>";
		}
        echo "</table>";
		if(isset($_COOKIE['id']))
			echo "<input type='submit' id='confirmationButton' name='checkout' onclick='clicked(event)' value='Confirm Order!'";
    ?>
    </div>
	</form>
   </body>
</html>
