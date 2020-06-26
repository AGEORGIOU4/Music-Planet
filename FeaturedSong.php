<!--
* Author: Andreas Georgiou
* Email: ageorgiou4@uclan.ac.uk
* Music Planet is an interactive mobile application
  for online music services
-->

<?php session_start();?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Featured Song</title>
    <link rel="stylesheet" href="<?php echo $_SESSION['mode'];?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='icon' href='Images/favicon.ico' type='image/x-icon'>
</head>

<body>

<!--=======================CONNECT TO THE DATABASE=========================-->
<?php
$connection = mysqli_connect("localhost", "ageorgiou4","BgbW8708", "ageorgiou4");
	
if($_SESSION["username"] == "" && $_SESSION["usernameR"] == "") {
	header('Location: Login.php');
	}
	
if (mysqli_connect_errno()) {
	echo "ERROR: could not connect to database: " . mysqli_connect_error();
}
?>

<!--====================HEADER=====================-->
<!--Header with an accordion hamburger dropdown menu-->
<header>
    <img class="musicPlanetLogo" src="Images/MusicPlanetLogo.png" alt="Music Planet Logo">
</header>

<?php 
$productId = $_GET["id"];

//SWITCH SESSION USERNAME VARIABLES
if($_SESSION['username'] == "") {
$username = $_SESSION['usernameR'];
}
else {
$username = $_SESSION['username'];
}

$reviewsQuery = "SELECT * FROM reviews";
$reviewsResult = mysqli_query($connection, $reviewsQuery);

$variable = 0;

while($row = mysqli_fetch_array($reviewsResult)) {
	if ($username == $row['name'] && $productId == $row['product_id'] && isSet ($_POST['SubmitReview'])){
		echo '<script type="text/javascript">alert("You have already voted for this track!");</script>';
		$variable = 2;
		break;
		
	}
}
	
$tracksQuery = "SELECT * FROM tracks";
$tracksResult = mysqli_query($connection, $tracksQuery);

while($rowTracks = mysqli_fetch_array($tracksResult)) {
	if ($rowTracks['track_id'] == $_GET['id']){
		$thumbTrack = $rowTracks['thumb'];
		}
}


if(isSet ($_POST['SubmitReview']) && $username != "guest" && $variable == 0) {
	$reviewRate = $_POST['rating'];
	$reviewComment = $_POST['comment'];
	$reviewTrack = $_GET['trackName'];
	$imageUser = "Images2/avatar.svg";
	
	$sql = "INSERT INTO reviews (product_id, name, rating, review, track, thumb, image) VALUES ('$productId', '$username', '$reviewRate','$reviewComment', '$reviewTrack', '$thumbTrack', '$imageUser')";
	echo '<script type="text/javascript">alert("Thank you for your feedback '. $username.'!");</script>';
	
	if(!mysqli_query($connection, $sql)) {	
	echo "Not inserted!";
	}
}

if(isSet ($_POST['SubmitReview']) && $username == "guest") {
	echo '<script type="text/javascript">alert("Only members can rate!");</script>';
}

if(isSet ($_POST['BrowseMore']) && $username == "guest") {
	echo '<script type="text/javascript">alert("Only members can browse!");</script>';
}


if(isSet ($_POST['BrowseMore']) && $username != "guest") {
	header('Location: MusicSelection.php');
}


?>

<div id="menuHeader" class="menuHeader">
    <input id="tab-1" type="checkbox">
    <label id="menuBtn" for="tab-1"></label>

    <!--Navigation Menu set as hidden till the menu is clicked-->
    <nav id="nav">
        <ul class="hiddenMenuList">
            <li class="hiddenList"><a href="Home.php">HOME</a></li>
            <li class="hiddenList"><a href="About.php">ABOUT</a></li>
            <li class="hiddenList"><a href="MusicSelection.php">MUSIC</a></li>
			<li class="hiddenList"><a href="Plans.php">PLANS</a></li>
			<li class="hiddenList"><a href="Account.php">ACCOUNT</a></li>
            <li class="hiddenList"><a href="Home.php"><img id="musicPlanetLogo2" src="Images/MusicPlanetLogo2.png"  alt="Music Planet Logo"></a></li>
        </ul>
		<!-- Welcome Message on Menu-->
	
		<!-- LARGE BOX-->
		<div class="welcomeMsg">
			<?php
			$loginQuery = "SELECT * FROM login";
			$loginResult = mysqli_query($connection, $loginQuery);
			//BOX 1-->
			echo '<div id="avatarDiv">';
				// Set current session ID  
				while($row = mysqli_fetch_array($loginResult)) {
					if(($_SESSION["username"] == $row["username"])) {
						echo '<a href="Account.php"><img src="'.$row['image'].'" alt="Profile" class="avatarMenu"></a>';
						echo '</div>';
					}
				}
			?>
			<!--BOX 2-->
			<div id="welcomeMsgP">
			
			<?php echo '<p id="welcomeMsg">'.$_SESSION["username"].'</p>';
			$loginQuery = "SELECT * FROM login";
			$loginResult = mysqli_query($connection, $loginQuery);

			echo '</div>';

			// User card
			$offersQuery = "SELECT * FROM offers";
			$offersResult = mysqli_query($connection, $offersQuery);
			
			//BOX 3-->
			echo '<div id="userPlanId">';
			while($row = mysqli_fetch_array($offersResult)) {
				
				if($_SESSION["planR"] == $row["title"]) {
					echo '<a href="Account.php"><img src="'.$row['image'].'" alt="Profile" class="planMenu"></a>';	
				}
					
			}
				if ($username == "guest") {
				echo '<a href="Account.php"><img src="Images2/freeVersion.png" alt="Profile" class="planMenu"></a>';		
				}
				echo '</div>';
		
			?>
			
		</div>
		

		<?php
		
		$signUpIfGuest = "Sign Up";
		if(isSet($_POST['logout'])){
			header('Location: Login.php');
		}
		if(isSet($_POST['myPlaylist']) && $username != "guest"){
			header('Location: MyPlaylist.php');
		}	
		if(isSet($_POST['myPlaylist']) && $username == "guest"){
			header('Location: SignUp.php');
		}
		
		if ($username != "guest") {
			$signUpIfGuest = "&#9738; My Playlist";
		}
		?>
		<div id="menuLinks">
			<form method="post" id="btnsMenu" style="background-color: #f1f1f1; padding: 10px 2px;">
			<?php 
			?>
			<button type="submit" name="logout" style="width: 48%; padding: 6px 0px; font-size: 16px; color: #414042; font-weight: 800; background-color: #f1f1f1;">&#10094; Logout</button>
		
			<button type="submit" name="myPlaylist" style="width: 48%; padding: 6px 0px; font-size: 16px; color: #414042; font-weight: 800; background-color: #f1f1f1;"><?php echo $signUpIfGuest ?></button>

			</form>
		</div>
		<!--DARK MODE-->
		<form method="post" id="darkBtn">
		<button type="submit" name="darkMode" style="background-color:#313131;"><?php echo $_SESSION['buttonLabel']; ?></button>
		</form>
    </nav>
</div>


<?php
if(isSet($_POST['darkMode']) && $_SESSION['cM'] == 1) {
	$_SESSION['mode'] = "styles2.css";
	$_SESSION['cM'] = 2;
	header('Location: darkMode.php');
}	
	else if(isSet($_POST['darkMode']) && $_SESSION['cM'] == 2) {
		$_SESSION['mode'] = "styles.css";
		$_SESSION['cM'] = 1;
		header('Location: darkMode.php');
}
?>


<!--=====================BODY=======================-->
<!--Artist Biography-->
<div id="artistBiographyParagraphSection">
    <h1 id="artistBiography"><?php echo $_GET['trackName'];?></h1>

<?php
	$myQuery = "SELECT * FROM tracks";
	$result = mysqli_query($connection, $myQuery); 	
		
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {	
		//Pass values to track description pages using Get Method
		if($_GET["id"] == $row["track_id"]){
		echo '<br>';
		echo '<p id="artistBiographyParagraph1"></p>'; 
		echo '<br>';
		echo '<div style="text-align:center; height: 140px;"><img class="thbButton" style="height:140px; width: 140px;"src="'.$row["image"].'"></div>';
		echo '<div style="text-align:center; padding: 20px 0 0 0;">';
		echo '<p style="margin: 0px; color:#a7a7a7">Album</p>';
		echo '<td id="moreAlbum"><a href="Album.php?album='.$row['album'].'&artist='.$row['artist'].'&albumPage=true" style="text-decoration: none;">'.$row["album"].'</a></td>';
		echo '<br>';
		echo '<p style="margin: 0px; color: #a7a7a7">Artist</p>';
		echo '<td id="moreAlbum"><a href="Album.php?album='.$row['album'].'&artist='.$row['artist'].'&albumPage=false" style="text-decoration: none;">'.$row["artist"].'</a></td>';
		echo '</div>';
		echo '<br>';
		echo '<div style="text-align:center;"><audio controls class="audioBox" style="height:50px; width: 90%; padding:0px;">'.'<source src="'.$row["sample"].'">'.'type="audio/mpeg"'.'</audio></div>';
		echo '<br>';

		echo '<div style="text-align: center; margin: 0 0 25px;">';
		echo '<td class="addTo" style="text-align:center";><a href="AddToPlaylist.php?id='.$row["track_id"].'&trackName='.$row["name"].'&image='.$row["image"].'&deleteTrack=6" style="text-decoration: none; background-color: #DB53FB; 
		text-decoration: none;color: white; padding: 10px 100px; font-size: 13.3px;">Add to playlist &#43;</a></td>';
		echo '</div>';
		}
	}
?>

<!--TRACK AVERAGE-->
<?php
$avg = 0;
$sum = 0;
$usersVoted = 0;

$reviewsQuery = "SELECT * FROM reviews";
$reviewsResult = mysqli_query($connection, $reviewsQuery);


while($row = mysqli_fetch_array($reviewsResult)) {
		if ($row['track'] == $_GET['trackName']) {
			$usersVoted++;
			
			$sum += $row['rating'];
		}
		
	}
if($sum != 0) {
$avg = round($sum / $usersVoted, 2); 
}	
	echo '<div style="text-align: center; margin: 15px;">';
		echo "Average rating is ";
			echo $avg;
			echo " out of ";
			echo $usersVoted;
			echo " votes";
			echo '<br>';
	echo '</div>';
?>
 

<!--Songs feedback (code obtained from https://codepen.io/hesguru/pen/BaybqXv)-->
<div id="userRating">
<form name="reviewForm" action="" method="post">
	<p style="font-weight: 600; margin: 15px 4px; width: 40%; float: left; font-weight: 600">Do you like the song? Give us a feedback!</p>
	<textarea rows="2" cols="50" name="comment" style=" margin: 0 4px 0 0;" placeholder="Write your comments.. " maxlength="25"></textarea>
	<div class="rate">
  <fieldset style="border: 0px; padding: 0px; margin: 0px 5px">
    <span class="star-cb-group">     
	  <input type="radio" id="rating-5" name="rating" value="5" />
      <label for="rating-5">5</label>
      <input type="radio" id="rating-4" name="rating" value="4" checked="checked" />
      <label for="rating-4">4</label>
      <input type="radio" id="rating-3" name="rating" value="3" />
      <label for="rating-3">3</label>
      <input type="radio" id="rating-2" name="rating" value="2" />
      <label for="rating-2">2</label>
      <input type="radio" id="rating-1" name="rating" value="1" />
      <label for="rating-1">1</label>
      <input type="radio" id="rating-0" name="rating" value="0" class="star-cb-clear" />
      <label for="rating-0">0</label>
    </span>
  </fieldset>
	  </div>
    <br>
    <button type="submit" name="SubmitReview" value="Submit"  style="background-color: green; margin:0 4px 0 0; width: 54%; float: right;">Submit</button>
	<button type="submit" name="BrowseMore" id="browseButton" value="BrowseMore">Browse More</a></button>
	<br>
</form>
</div>

<?php
$reviewsQuery = "SELECT * FROM reviews";
$reviewsResult = mysqli_query($connection, $reviewsQuery);


while($row = mysqli_fetch_array($reviewsResult)) {
	if ($row['track'] == $_GET['trackName']) {
		echo '<div style="border: 1px solid #ccc; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); height: 90px;">';
			
			echo '<div style="width: 25%; float: left; text-align: center; margin: 0px; padding: 10px; height: 90px;">';
			echo '<div style="">'.$row['name'].'</div>';
			echo '<div><img src="'.$row['image'].'" style="border-radius: 50%; width: 30px; height: 30px; padding: 2px; background-color: #ccc;"></div>';
			echo '<div style="color: orange;">';
			for($i=0; $i < $row['rating']; $i++) {
				echo "★";
			}
			echo '</div>';
			echo '</div>';
			
			echo '<div style="margin: 35px 0";>'.$row['review'].'</div>';
		echo '</div>';
		echo '<br>';
	}
}
?>

<!--====================FOOTER=====================-->
<footer>
    <div class="footerMenu">
        <a href="Home.php">HOME</a>
        <br>
        <a href="About.php">ABOUT</a>
        <br>
        <a href="MusicSelection.php">MUSIC</a>
        <br>
        <img class="musicPlanetLogo" src="Images/MusicPlanetLogo.png" alt="Music Planet Logo">
    </div>

    <p id="footerParagraph">Created by Andreas Georgiou</p>
</footer>

</body>

</html>