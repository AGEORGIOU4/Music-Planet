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
    <title>Random Playlist</title>
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
	
if (mysqli_connect_errno())
	{
	echo "ERROR: could not connect to database: " . mysqli_connect_error();
	}
	
	// When user refreshes the random list
if(isSet($_POST['generateNew'])) {
$_SESSION['savePlaylist'] = false;
$_SESSION['randomKey'] = 1;
header('Location: GenerateNewRandom.php');	
}
	
$username=$_SESSION['username'];
?>

<!--====================HEADER=====================-->
<!--Header with an accordion hamburger dropdown menu-->
<header>
    <img class="musicPlanetLogo" src="Images/MusicPlanetLogo.png" alt="Music Planet Logo">
</header>

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
		
		// CHANGE THE MENU BTN TO SIGN UP IF USER IS LOGGED AS GUEST
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


<!--=====================BODY======================-->
<button style="	float: left; width: 50%; height: 50px; background-color: #DB53FB;" onclick="window.location.href='MyPlaylist.php'">My Playlist</button>
<button id="generateRandom" style="float: left; padding: 0 0px 0 34px; width: 35%; height: 50px; background-color: #719ece;" onclick="showRandomPlaylist()">Random</button>
<form method="post" action="">
<button type="submit" id="refreshButton" name="generateNew" style="padding: 0; border: 0px solid; background-color: #719ece; width: 15%; height: 50px;">
	<img id="refreshImg" style="width: 40px; height: 40px;" src="Images/refresh.png" alt="Refresh Button" onclick="reloadPage()">
</button>
</form>


<div id="myPlaylist" style="display: none;">
<?php
$connection = mysqli_connect("localhost", "ageorgiou4","BgbW8708", "ageorgiou4");
	
$myPlaylistQuery = "SELECT * FROM playlist WHERE username ='".$username."'";
$playlistResult = mysqli_query($connection, $myPlaylistQuery); 	

$num = 1;
while ($row = mysqli_fetch_array($playlistResult, MYSQLI_ASSOC)) {
	echo '<table class="musicSelectionsTable">';
	echo '<tr>';
	echo '<td class="thumb" style="width: 20%;"><img class="thbButton" style="width: 100%; border-radius: 50%;" src="'.$row["image"].'"     alt="thub Button">'.'</td>'; 
	echo '<td class="num" style="text-align:center; width:22px; padding: 0 15px;">'.$num.'</td>';
	echo '<td class="name" style="padding: 0 20px 0 0; text-align:left;"><a href="FeaturedSong.php?id='.$row["product_id"].'&trackName='.$row["track_name"].'" style="text-decoration: none;">'.$row["track_name"].'</a></td>';
	echo '<td style="text-align:center";><a href="FeaturedSong.php?id='.$row["product_id"].'&trackName='.$row["track_name"].'" style="text-decoration: none; font-size: x-large; color: #719ece;">&#x25b6;</a></td>';
	echo '<td style="text-align:center";><a href="AddToPlaylist.php?playlist_id='.$row["playlist_id"].'&deleteTrack=5" style="text-decoration: none; font-size: x-large; color: #719ece;">&#8722;</a></td>';
	echo '</tr>';
	echo '</table>';
	
	$num++;
		
}

?>
</div>

<div id="randomPlaylist" style="display: block;">
	
	
<?php

$num = 1;

// output session array numbers
for($x = 0; $x < 10; $x++){

// select the tracks from the db
$randomQuery = "SELECT * FROM tracks WHERE track_id ='".$_SESSION['array'][$x]. "'";	

$newResult = mysqli_query($connection, $randomQuery);

// Output the random generated list	
while ($row = mysqli_fetch_array($newResult, MYSQLI_ASSOC)) {
	echo '<table class="musicSelectionsTable">';
	echo '<tr>';
	echo '<td class="thumb" style="width: 20%;"><img class="thbButton" style="width: 100%; border-radius: 50%;" src="'.$row["image"].'"     alt="thub Button">'.'</td>'; 
	echo '<td class="num" style="text-align:center; width:22px; padding: 0 15px;">'.$num.'</td>';
	echo '<td class="genre">'.$row["genre"].'</td>';
	echo '<td class="artist">'.$row["artist"].'</td>';
	echo '<td class="album">'.$row["album"].'</td>';
	echo '<td class="name" style="padding: 0 20px 0 0; text-align:left;"><a href="FeaturedSong.php?id='.$row["track_id"].'&trackName='.$row["name"].'" style="text-decoration: none;">'.$row["name"].'</a></td>';
	echo '<td style="text-align:center";><a href="FeaturedSong.php?id='.$row["track_id"].'&trackName='.$row["name"].'" style="text-decoration: none; font-size: x-large; color: #719ece;">&#x25b6;</a></td>';
	echo '</tr>';
	echo '</table>';
	
	$num++;
	}
}

?>
</div>

<!--====================FOOTER=====================-->
<footer>
    <div class="footerMenu">
        <a href="Home.php">HOME</a>
        <br>
        <a href="About.php">ABOUT</a>
        <br>
        <a href="MusicSelection.php">MUSIC</a>
        <br>
		<a href="Account.php">ACCOUNT</a>
        <br>
        <img class="musicPlanetLogo" src="Images/MusicPlanetLogo.png" alt="Music Planet Logo">
    </div>
    <p id="footerParagraph">Created by Andreas Georgiou</p>
</footer>


<script>
function showRandomPlaylist() {
	var randomPlaylist = document.getElementById("randomPlaylist");
    /*-----------------------------------------------SHOW / HIDE ELEMENTS-----------------------------------------------*/
	if(randomPlaylist.style.display == "none") {
    document.getElementById("randomPlaylist").style.display = "block";
    document.getElementById("myPlaylist").style.display = "none";
	}
}

function showMyPlaylist() {
	var myPlaylist = document.getElementById("myPlaylist");
    /*-----------------------------------------------SHOW / HIDE ELEMENTS-----------------------------------------------*/
	if(myPlaylist.style.display == "none") {
    document.getElementById("randomPlaylist").style.display = "none";
    document.getElementById("myPlaylist").style.display = "block";
	}
}


function reloadPage() {
            location.reload();
        }
</script>

</body>

</html>

<!--References for accordion dropdown-->
<!--Toh, W.S. (n.d), "3 Steps Simple Responsive Accordion 
With Pure CSS", CODE-BOXX Accessed on 18/11/19, 
Available at: 
https://code-boxx.com/simple-responsive-accordion-pure-css/-->