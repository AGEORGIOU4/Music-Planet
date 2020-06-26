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
    <title><?php if($_GET['albumPage'] == "true"){
		echo "Album";
		} else {
			echo "Artist";
		} 
		
		?>
	</title>
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
$album = $_GET["album"];
$artist = $_GET["artist"];

//SWITCH SESSION USERNAME VARIABLES
if($_SESSION['username'] == "") {
$username = $_SESSION['usernameR'];
}
else {
$username = $_SESSION['username'];
}

if(isSet ($_POST['SubmitReview']) && $username == "guest") {
	echo '<script type="text/javascript">alert("Only members can rate!");</script>';
}
if(isSet ($_POST['BrowseMore'])) {
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
<?php
	$myQuery = "SELECT * FROM tracks";
	$result = mysqli_query($connection, $myQuery); 	

if($_GET['albumPage'] == "true") {
	//<!--Album Biography-->
	echo '<div id="artistBiographyParagraphSection">';
    echo '<h1 id="artistBiography">'.$_GET["album"].'</h1>';
	
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {	
		//Pass values to track description pages using Get Method
		if($_GET["album"] == $row["album"]){
		echo ' <h3 id="albums">Description:</h3>';
		echo '<br>';
		echo '<p id="artistBiographyParagraph1">'.$row["description"].'</p>'; 
		echo '<br>';
		echo '<div style="text-align:center; height: 140px;"><img class="thbButton" style="height:140px; width: 140px;"src="'.$row["image"].'"></div>';
		echo '<br>';
		break;
		}
	}
	echo '<div class="tab">';
	echo '<div class="tab-content">';
	echo '<div class="musicTable">';
	echo '<table class="musicSelectionsTable">';

	$num=1;
		
	echo ' <h3 id="albums">Songs of this Album:</h3>';
	echo '<p id="artistBiographyParagraph1"></p>'; 
		
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		if($_GET["album"] == $row["album"]){
		echo '<table class="musicSelectionsTable">';
		echo '<tr>';
		echo '<td class="thumb" style="width: 20%;"><img class="thbButton" style="width: 100%; border-radius: 50%;" src="'.$row["image"].'"     alt="thub Button">'.'</td>'; 
		echo '<td class="num" style="text-align:center;  width:58px; padding: 0 15px;">'.$num.'</td>';
		echo '<td class="artist">'.$row["artist"].'</td>';
		echo '<td class="name" style="padding: 0 20px 0 0; text-align:left;"><a href="FeaturedSong.php?id='.$row["track_id"].'&trackName='.$row["name"].'" style="text-decoration: none;">'.$row["name"].'</a></td>';
		echo '<td class="genreHome">'.$row["genre"].'</td>';
		echo '<td style="text-align:center;"><audio controls style="width: 20px;">'.'<source src="'.$row["sample"].'">'.'type="audio/mpeg"'.'</audio></td>';
		echo '<br>';
		echo '</tr>';
		echo '</table>';
		
		$num++;
		}
	}
}	else {
	//<!--Album Biography-->
	echo '<div id="artistBiographyParagraphSection">';
    echo '<h1 id="artistBiography">'.$_GET["artist"].'</h1>';
	
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {	
		//Pass values to track description pages using Get Method
		if($_GET["artist"] == $row["artist"]){
		echo ' <h3 id="albums">Description:</h3>';
		echo '<br>';
		echo '<p id="artistBiographyParagraph1">'.$row["description"].'</p>'; 
		echo '<br>';
		echo '<div style="text-align:center; height: 140px;"><img class="thbButton" style="height:140px; width: 140px;"src="'.$row["image"].'"></div>';
		echo '<br>';
		break;
		}
	}
	echo '<div class="tab">';
	echo '<div class="tab-content">';
	echo '<div class="musicTable">';
	echo '<table class="musicSelectionsTable">';

	$num=1;
		
	echo ' <h3 id="albums">Songs of this Artist:</h3>';
	echo '<p id="artistBiographyParagraph1"></p>'; 
		
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		if($_GET["artist"] == $row["artist"]){
		echo '<table class="musicSelectionsTable">';
		echo '<tr>';
		echo '<td class="thumb" style="width: 20%;"><img class="thbButton" style="width: 100%; border-radius: 50%;" src="'.$row["image"].'"     alt="thub Button">'.'</td>'; 
		echo '<td class="num" style="text-align:center;  width:58px; padding: 0 15px;">'.$num.'</td>';
		echo '<td class="artist">'.$row["artist"].'</td>';
		echo '<td class="name" style="padding: 0 20px 0 0; text-align:left;"><a href="FeaturedSong.php?id='.$row["track_id"].'&trackName='.$row["name"].'" style="text-decoration: none;">'.$row["name"].'</a></td>';
		echo '<td class="genreHome">'.$row["genre"].'</td>';
		echo '<td style="text-align:center;"><audio controls style="width: 20px;">'.'<source src="'.$row["sample"].'">'.'type="audio/mpeg"'.'</audio></td>';
		echo '<br>';
		echo '</tr>';
		echo '</table>';
		
		$num++;
		}
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