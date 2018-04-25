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
$username = $_SESSION['sess_username'];

// Check connection to event_photo database
$con=mysqli_connect($host,$user,$pass,$db);
if (!$con) {
	die("Connection failed: " . mysqli_connect_error());
}

// SQL query to check if user is admin
$adminQuery = "SELECT * FROM customer WHERE username = '$username';";
$result = mysqli_query ($con,$adminQuery);
$userData = mysqli_fetch_array($result);
$admin = $userData['admin_rights'];

//check if item is deleted
if($admin){
    $tempQuery  = "SELECT image_file_name from frames UNION SELECT image_file_name from mats;";
	$temps = mysqli_query ($con,$tempQuery);
	while($temp = mysqli_fetch_array($temps)){
        $temp_name = $temp['image_file_name'];
		if(isset($_POST['delete_item_'.$temp_name])){
			$deleteFrameQuery = "UPDATE frames SET deleted = true WHERE image_file_name = '$temp_name';";
            mysqli_query($con,$deleteFrameQuery);
            $deleteMatQuery = "UPDATE mats SET deleted = true WHERE image_file_name = '$temp_name';";
            mysqli_query($con,$deleteMatQuery);
            header("Refresh:0");
		}
	}
}

// SQL query to select all frames from frames table
$frames_sql = "SELECT * FROM frames;";
$frames_result = mysqli_query($con, $frames_sql);

// SQL query to select all mats from mats table
$mats_sql = "SELECT * FROM mats;";
$mats_result = mysqli_query($con, $mats_sql);

// SQL query to select all sizes from sizes table
$sizes_sql = "SELECT * FROM sizes;";
$sizes_result = mysqli_query($con, $sizes_sql);
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

<script>
    var chosenFrame = null;
    var chosenFrameName = null;
    var framePrice = null;
    var chosenMat = null;
    var matPrice = null;
    var chosenSize = null;
    var currentPrice = null;
    var sizePrice = parseFloat(15.99);
    var imageID = '<?php echo $_SESSION["selected_id"]; ?>';

    function selectFrame(frameID, frameName, price) {
        chosenFrame = frameID;
        var currentFrame = document.getElementById("currentFrame");
        currentFrame.innerText = frameName;
        chosenFrameName = frameName;
        framePrice = parseFloat(price);
        costCalculator();
        enableSubmit();
    }
    function selectMat(matID, matName, price) {
        chosenMat = matID;
        var currentMat = document.getElementById("currentMat");
        currentMat.innerText = matName;
        matPrice = parseFloat(price);
        costCalculator();
        enableSubmit();
    }
    function selectSize() {
        chosenSize = document.getElementById("sizeSelector");
        var currentSize = document.getElementById("currentSize");
        var selectedPrice = chosenSize.options[chosenSize.selectedIndex];
        currentSize.innerText = chosenSize.value;
        sizePrice = parseFloat(selectedPrice.getAttribute('data-price'));
        costCalculator();
    }
    function costCalculator(){
        var priceLabel = document.getElementById("pricePlaceholder");
        priceLabel.style.visibility = "visible";
        currentPrice = document.getElementById("currentPrice");
        var totalPrice = framePrice + matPrice + sizePrice;
        currentPrice.innerText = "$" + totalPrice.toFixed(2);
    }
    function enableSubmit(){
        if ((chosenFrame !== null) && (chosenMat !== null)){
            var orderButton = document.getElementById("orderButton");
            orderButton.style.visibility = "visible";
        };
    }
    function submitOrder(){
        // Set Cookie with shopping cart data
        document.cookie = "id="+imageID;
        document.cookie = "frame="+chosenFrameName;
        document.cookie = "mat="+ document.getElementById("currentMat").innerText;
        document.cookie = "size="+document.getElementById("currentSize").innerText;
        document.cookie = "price="+currentPrice.innerText;
        window.location.replace("cart.php");
    }
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

<body>
<form action="" method="post" id="searchForm">
    <div class="wrapper">
    <!-- <section class="section-background"></section> -->
        <!-- Navbar for site navigation -->
        <div class="header">
            <div id="navbar">
                <div class = "user-welcome">
                    <?php echo "Welcome ".ucfirst($_SESSION['sess_username'])."!" ?>
                </div>
                <div class = "navbar-item">
                    <a href=photos.php#home>Photos</a>
                </div>
                <div class = "navbar-item sign-out">
                    <a href=login.html>Sign Out</a>
                </div>
            </div>
        </div>
        <div class="banner">
            <h1>Photo Framing Services</h1>
            <h1>Frame your heart out</h1>
            <h3>Choose your favorite frame and matting. We’ll custom cut, craft and build it from scratch.</h3>
        </div>

        <div class="sidebar">
            <h3>Select framing for photo: <?php echo "<img id='selectedPhoto' src = 'resources/".$_SESSION['selected_id'].".jpeg'/>"; ?></h3>
            <h1>You've Selected:</h1>
            <h3>Frame: <span id="currentFrame">Select your frame!</span></h3>
            <h3>Mat:  <span id="currentMat">Select a mat!</span> </h3>
            <h3>Size: <span id="currentSize">A2</span> </h3>
            <h4 id="pricePlaceholder">Price: <span id="currentPrice"></span></h4>
            <button type="button" id="orderButton" onclick="submitOrder();">Submit!</button>            
        </div>
        <div class="content">
            <section class="page-title">
                <!-- <h1>Photo Framing Services</h1> -->
            </section>
            <section class="product-section-title">                
                <h1>Frames</h1>
            </section>
            <section class="frame">
                <?php
                    if($frames_result->num_rows == 0){
                        echo "SOMETHING WENT WRONG!";
                    }
                    while($row = mysqli_fetch_array($frames_result)){
                        $deleted = $row['deleted'];
                        $available = ($row['inventory'] > 0);
                        if(!$deleted && $available){
                            $name = $row['image_file_name'];
                            echo "<article class='product-image'>";
                            echo "<figure>";
                            echo "<img src='resources/frames/".$row['image_file_name'].".jpg"."' alt='Frame Image' title='".$row['name']."'"."data-id=".$row["id"].">";
                            echo "<figcaption onclick=\"selectFrame(".'\''.$row['id'].'\''.",".'\''.$row['name'].'\''.",".'\''.$row['price'].'\''.");\">";
                            echo "<h1>".$row['name']."<br/>"."$".$row['price'];
                            if($admin){
                                echo "<br/><input class='admin' type='submit' id='editButton' onclick=submitForm('update_service_items.php#home') name='update_item_$name' value='Edit'/>";
                                echo "<br/><input class='admin' type='submit' id='deleteButton' onclick='clicked(event)' name='delete_item_$name' value='Delete'/>";
                            }
                            echo "</h1>";
                            echo "</figcaption>";
                            echo "</figure>";
                            echo "</article>";
                        }
                    }
                ?>
            </section>
            <section class="product-section-title">                
                <h1>Mats</h1>
            </section>
            <section class="mat">
                <?php
                    if($mats_result->num_rows == 0){
                        echo "SOMETHING WENT WRONG!";
                    }
                    while($row = mysqli_fetch_array($mats_result)){
                        $deleted = $row['deleted'];
                        $available = ($row['inventory'] > 0);
                        if(!$deleted && $available){
                            $name = $row['image_file_name'];
                            echo "<article class='product-image'>";
                            echo "<figure>";
                            echo "<img src='resources/mats/".$row['image_file_name'].".jpg"."' alt='Mat Image' title='".$row['name']."'"."data-id=".$row["id"].">";
                            echo "<figcaption onclick=\"selectMat(".'\''.$row['id'].'\''.",".'\''.$row['name'].'\''.",".'\''.$row['price'].'\''.");\">";
                            echo "<h1>".$row['name']."<br/>"."$".$row['price'];
                            if($admin){
                                echo "<br/><input class='admin' type='submit' id='editButton' onclick=submitForm('update_service_items.php#home') name='update_item_$name' value='Edit'/>";
                                echo "<br/><input class='admin' type='submit' id='deleteButton' onclick='clicked(event)' name='delete_item_$name' value='Delete'/>";
                            }

                            echo "</h1>";
                            echo "</figcaption>";
                            echo "</figure>";
                            echo "</article>";
                        }
                    }
                ?>
            </section>
            <section class="product-section-title">                
                <h1>Size</h1>
            </section>
            <section class="size">
                <select name="size" id="sizeSelector" onchange="selectSize()">
                    <optgroup label = "Size | Dimension | Price">
                        <?php
                            while($row = mysqli_fetch_array($sizes_result)){
                                echo "<option ". "value=".$row[size]." data-price=".'"'.$row[price].'"'.">".$row["size"]." | ".$row["dimensions"]." | "."$".$row["price"]."</option>";
                            }
                        ?>
                    </optgroup>
                </select> 
            </section>
        </div>
    </div>
    </form>
</body>
</html>