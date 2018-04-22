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

// Check connection to event_photo database
$con=mysqli_connect($host,$user,$pass,$db);
if (!$con) {
	die("Connection failed: " . mysqli_connect_error());
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
    function babyNames() {
        var year = document.getElementById("year").value;
        var gender = document.getElementById("gender").value;
        // document.getElementById("one").getAttribute('data-value')
    }

    function displayID(el)
    {
        alert(  el.value  );
    }
</script>

<body>
    <div class="wrapper">
        <!-- Navbar for site navigation -->
        <div class="header">
        <div id="navbar">
            <div class = "user-welcome">
                <?php echo "Welcome ".ucfirst($_SESSION['sess_username'])."!" ?>
            </div>
            <div class = "navbar-item">
                <a href=photos.php>Photos</a>
            </div>
            <div class = "navbar-item sign-out">
                <a href=login.html>Sign Out</a>
            </div>
        </div>
        </div>
        <section class="section-background"></section>
        <div class="content">
            <section class="page-title">
                <h1>Photo Printing Services</h1>
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
                        echo "<article class='product-image'>";
                        echo "<figure>";
                        echo "<img src='resources/frames/".$row['image_file_name'].".jpg"."' alt='Frame Image' title='".$row['frame_name']."'"."data-value=".$row["id"].">";
                        echo "<figcaption>";
                        echo "<h1>".$row['frame_name']."<br/>"."$".$row['price']."</h1>";
                        echo "</figcaption>";
                        echo "</figure>";
                        echo "</article>";
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
                        echo "<article class='product-image'>";
                        echo "<figure>";
                        echo "<img src='resources/mats/".$row['image_file_name'].".jpg"."' alt='Mat Image' title='".$row['mat_name']."'"."data-value=".$row["id"].">";
                        echo "<figcaption>";
                        echo "<h1>".$row['mat_name']."<br/>"."$".$row['price']."</h1>";
                        echo "</figcaption>";
                        echo "</figure>";
                        echo "</article>";
                    }
                ?>
            </section>
            <section class="product-section-title">                
                <h1>Size</h1>
            </section>
            <section class="size">
                <select name="size">
                    <optgroup label = "Size | Dimension | Price">
                        <?php
                            while($row = mysqli_fetch_array($sizes_result)){
                                echo "<option ". "value=".$row["size"].">".$row["size"]." | ".$row["dimensions"]." | "."$".$row["price"]."</option>";
                            }
                        ?>
                    </optgroup>
                </select> 
            </section>
        </div>
    </div>
</body>
</html>