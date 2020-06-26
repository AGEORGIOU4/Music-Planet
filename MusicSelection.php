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
    <title>Music Selection</title>
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

if($_SESSION["username"] == "guest") {
		$_SESSION['restrictedAccess'] = 2;
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		
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
            <li class="hiddenList"><a href="MusicSelection.php"><span style="color:#DB53FB">MUSIC</span></a></li>
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

<div id="musicSection">

<!--Search Bar-->
	<form method="get">
    <div class="search" style="width: 70%;">
	    <label for="searchBar"></label>
        <input type="text" placeholder="Search artist, album, or track... " style="height: 40px; float: left; margin: 8px 0 30px;" id="searchBar" name="searchBox">
	</div>	
	<div style="width: 15%; float: left;">
         <button type="submit" name="search" style="height: 40px; border: 1px solid #ccc; padding: 0px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); float: left; background: none; ">&#128270;</button>
    </div>
	</form>
	
	<form method="get">
	<div style="width: 15%; float: left;">
         <button type="submit" name="clean" style="height: 40px; border: 1px solid #ccc; padding: 0px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); float: left; background: none; font-size: 20px;
		color: #268029; ">&#9851;</button>
    </div>
	</form>
	
<!--SEARCH RESULTS TABLE-->
<div id="searchResults" style="display">

<?php
$limit = 0;
$type = "name";
if(isSet($_GET['search']) && $username != "guest") {
	$_SESSION['searchQueryVar'] = ucwords($_GET['searchBox']);
	$limit = 0;
	
	$searchQuery = "SELECT * FROM tracks";
	$result = mysqli_query($connection, $searchQuery); 	
	
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			if(ucwords($_GET['searchBox']) == $row['genre']) {
				$type = "genre";
			}
		}	
}

if(isSet($_GET['search']) && $username != "guest") {
	$_SESSION['searchQueryVar'] = $_GET['searchBox'];
	$limit = 130;
	
	$searchQuery = "SELECT * FROM tracks";
	$result = mysqli_query($connection, $searchQuery); 	
	
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			if(ucwords($_GET['searchBox']) == $row['album']) {
				$type = "album";
			}
		}	
}

if(isSet($_GET['search']) && $username == "guest") {
	echo '<script type="text/javascript">alert("Guest Version can not access Music Page! Please sign up.");</script>';
}

if(isSet($_GET['clean'])) {
	$_SESSION['searchQueryVar'] = "";
		$limit = 0;
}

$searchQueryVar = $_SESSION['searchQueryVar'];

$searchQuery = "SELECT * FROM tracks WHERE ".$type." LIKE '%".$searchQueryVar."%' ORDER BY name ASC LIMIT ".$limit."";
$result = mysqli_query($connection, $searchQuery); 	


echo '<div class="tab">';
echo '<div class="tab-content">';
echo '<div class="musicTable">';
echo '<table class="musicSelectionsTable">';

$num = 1;
if((!preg_match('/[^A-Za-z0-9]/', $_SESSION['searchQueryVar']))) {
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	echo '<table class="musicSelectionsTable">';
	echo '<tr>';
	echo '<td class="thumb" style="width: 20%;"><img class="thbButton" style="width: 100%; border-radius: 50%;" src="'.$row["image"].'"     alt="thub Button">'.'</td>'; 
	echo '<td class="num" style="text-align:center; width:58px; padding: 0 15px;">'.$num.'</td>';
	echo '<td class="artist">'.$row["artist"].'</td>';
	echo '<td class="name" style="padding: 0 20px 0 0; text-align:left;"><a href="FeaturedSong.php?id='.$row["track_id"].'&trackName='.$row["name"].'" style="text-decoration: none;">'.$row["name"].'</a></td>';
	echo '<td class="genreHome" style="width: 74px">'.$row["genre"].'</td>';
	echo '<td style="text-align:center;"><audio controls style="width: 20px;">'.'<source src="'.$row["sample"].'">'.'type="audio/mpeg"'.'</audio></td>';
	echo '<br>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="albumSearch" style="font-size: 12px; text-align: center;"><a href="Album.php?album='.$row['album'].'&artist='.$row['artist'].'&albumPage=true" style="text-decoration: none;">'.$row["album"].'</a></td>';
	echo '</tr>';
	
	echo '</table>';
	
	$num++;
		
	}
}
?>
				</table>
			</div>
		</div>
	</div>		

</div>



<!--====================TABLE=====================-->				
<?php 				
$connection = mysqli_connect("localhost", "ageorgiou4","BgbW8708", "ageorgiou4");
	
if (mysqli_connect_errno())
	{
	echo "ERROR: could not connect to database: " . mysqli_connect_error();
	}
	
	else
	$myQuery = "SELECT * FROM tracks WHERE genre = 'Rap'";
	$result = mysqli_query($connection, $myQuery); 	


echo '<div class="tab6">';
echo 	'<input id="tab-2" type="checkbox" name="tab">';
echo 	'<label for="tab-2" id="label2">RAP</label>';
echo 		'<div class="tab-content6">';
echo 			'<div class="musicTable">';
echo 				'<table class="musicSelectionsTable">';


$num = 1;			
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	echo '<table class="musicSelectionsTable">';
	echo '<tr>';
	echo '<td class="thumb" style="width: 20%;"><img class="thbButton" style="width: 100%; border-radius: 50%;" src="'.$row["image"].'"     alt="thub Button">'.'</td>'; 
	echo '<td class="num" style="text-align:center; width:58px; padding: 0 15px;">'.$num.'</td>';
	echo '<td class="artist">'.$row["artist"].'</td>';
	echo '<td class="name" style="padding: 0 20px 0 0; text-align:left;"><a href="FeaturedSong.php?id='.$row["track_id"].'&trackName='.$row["name"].'" style="text-decoration: none;">'.$row["name"].'</a></td>';
	echo '<td class="genreHome" style="width: 74px">'.$row["genre"].'</td>';
	echo '<td style="text-align:center;"><audio controls style="width: 20px;">'.'<source src="'.$row["sample"].'">'.'type="audio/mpeg"'.'</audio></td>';
	echo '<br>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="albumSearch" style="font-size: 12px; text-align: center;"><a href="Album.php?album='.$row['album'].'&artist='.$row['artist'].'&albumPage=true" style="text-decoration: none;">'.$row["album"].'</a></td>';
	echo '</tr>';
	
	echo '</table>';
	
	$num++;
		
	}

?>
				</table>
			</div>
		</div>
	</div>		

</div>
	
<!--====================TABLE=====================-->				
<?php 				
$connection = mysqli_connect("localhost", "ageorgiou4","BgbW8708", "ageorgiou4");
	
if (mysqli_connect_errno())
	{
	echo "ERROR: could not connect to database: " . mysqli_connect_error();
	}
	
	else
	$myQuery = "SELECT * FROM tracks WHERE genre = 'DANCE'";
	$result = mysqli_query($connection, $myQuery); 	


echo '<div class="tab6">';
echo 	'<input id="tab-3" type="checkbox" name="tab">';
echo 	'<label for="tab-3" id="label2">DANCE</label>';
echo 		'<div class="tab-content6">';
echo 			'<div class="musicTable">';
echo 				'<table class="musicSelectionsTable">';


$num = 1;			
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	echo '<table class="musicSelectionsTable">';
	echo '<tr>';
	echo '<td class="thumb" style="width: 20%;"><img class="thbButton" style="width: 100%; border-radius: 50%;" src="'.$row["image"].'"     alt="thub Button">'.'</td>'; 
	echo '<td class="num" style="text-align:center; width:58px; padding: 0 15px;">'.$num.'</td>';
	echo '<td class="artist">'.$row["artist"].'</td>';
	echo '<td class="name" style="padding: 0 20px 0 0; text-align:left;"><a href="FeaturedSong.php?id='.$row["track_id"].'&trackName='.$row["name"].'" style="text-decoration: none;">'.$row["name"].'</a></td>';
	echo '<td class="genreHome" style="width: 74px">'.$row["genre"].'</td>';
	echo '<td style="text-align:center;"><audio controls style="width: 20px;">'.'<source src="'.$row["sample"].'">'.'type="audio/mpeg"'.'</audio></td>';
	echo '<br>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="albumSearch" style="font-size: 12px; text-align: center;"><a href="Album.php?album='.$row['album'].'&artist='.$row['artist'].'&albumPage=true" style="text-decoration: none;">'.$row["album"].'</a></td>';
	echo '</tr>';
	
	echo '</table>';
	
	$num++;
		
	}

?>
				</table>
			</div>
		</div>
	</div>		

</div>

<!--====================TABLE=====================-->				
<?php 				
$connection = mysqli_connect("localhost", "ageorgiou4","BgbW8708", "ageorgiou4");
	
if (mysqli_connect_errno())
	{
	echo "ERROR: could not connect to database: " . mysqli_connect_error();
	}
	
	else
	$myQuery = "SELECT * FROM tracks WHERE genre = 'ROCK'";
	$result = mysqli_query($connection, $myQuery); 	


echo '<div class="tab6">';
echo 	'<input id="tab-4" type="checkbox" name="tab">';
echo 	'<label for="tab-4" id="label2">ROCK</label>';
echo 		'<div class="tab-content6">';
echo 			'<div class="musicTable">';
echo 				'<table class="musicSelectionsTable">';


$num = 1;			
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	echo '<table class="musicSelectionsTable">';
	echo '<tr>';
	echo '<td class="thumb" style="width: 20%;"><img class="thbButton" style="width: 100%; border-radius: 50%;" src="'.$row["image"].'"     alt="thub Button">'.'</td>'; 
	echo '<td class="num" style="text-align:center; width:58px; padding: 0 15px;">'.$num.'</td>';
	echo '<td class="artist">'.$row["artist"].'</td>';
	echo '<td class="name" style="padding: 0 20px 0 0; text-align:left;"><a href="FeaturedSong.php?id='.$row["track_id"].'&trackName='.$row["name"].'" style="text-decoration: none;">'.$row["name"].'</a></td>';
	echo '<td class="genreHome" style="width: 74px">'.$row["genre"].'</td>';
	echo '<td style="text-align:center;"><audio controls style="width: 20px;">'.'<source src="'.$row["sample"].'">'.'type="audio/mpeg"'.'</audio></td>';
	echo '<br>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="albumSearch" style="font-size: 12px; text-align: center;"><a href="Album.php?album='.$row['album'].'&artist='.$row['artist'].'&albumPage=true" style="text-decoration: none;">'.$row["album"].'</a></td>';
	echo '</tr>';
	
	echo '</table>';
	
	$num++;
		
	}

?>
				</table>
			</div>
		</div>
	</div>		

</div>

<!--====================TABLE=====================-->				
<?php 				
$connection = mysqli_connect("localhost", "ageorgiou4","BgbW8708", "ageorgiou4");
	
if (mysqli_connect_errno())
	{
	echo "ERROR: could not connect to database: " . mysqli_connect_error();
	}
	
	else
	$myQuery = "SELECT * FROM tracks WHERE genre = 'INDIE'";
	$result = mysqli_query($connection, $myQuery); 	


echo '<div class="tab6">';
echo 	'<input id="tab-5" type="checkbox" name="tab">';
echo 	'<label for="tab-5" id="label2">INDIE</label>';
echo 		'<div class="tab-content6">';
echo 			'<div class="musicTable">';
echo 				'<table class="musicSelectionsTable">';


$num = 1;			
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	echo '<table class="musicSelectionsTable">';
	echo '<tr>';
	echo '<td class="thumb" style="width: 20%;"><img class="thbButton" style="width: 100%; border-radius: 50%;" src="'.$row["image"].'"     alt="thub Button">'.'</td>'; 
	echo '<td class="num" style="text-align:center; width:58px; padding: 0 15px;">'.$num.'</td>';
	echo '<td class="artist">'.$row["artist"].'</td>';
	echo '<td class="name" style="padding: 0 20px 0 0; text-align:left;"><a href="FeaturedSong.php?id='.$row["track_id"].'&trackName='.$row["name"].'" style="text-decoration: none;">'.$row["name"].'</a></td>';
	echo '<td class="genreHome" style="width: 74px">'.$row["genre"].'</td>';
	echo '<td style="text-align:center;"><audio controls style="width: 20px;">'.'<source src="'.$row["sample"].'">'.'type="audio/mpeg"'.'</audio></td>';
	echo '<br>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="albumSearch" style="font-size: 12px; text-align: center;"><a href="Album.php?album='.$row['album'].'&artist='.$row['artist'].'&albumPage=true" style="text-decoration: none;">'.$row["album"].'</a></td>';
	echo '</tr>';
	
	echo '</table>';
	
	$num++;
		
	}

?>
				</table>
			</div>
		</div>
	</div>		

</div>

<!--====================TABLE=====================-->				
<?php 				
$connection = mysqli_connect("localhost", "ageorgiou4","BgbW8708", "ageorgiou4");
	
if (mysqli_connect_errno())
	{
	echo "ERROR: could not connect to database: " . mysqli_connect_error();
	}
	
	else
	$myQuery = "SELECT * FROM tracks WHERE genre = 'RNB'";
	$result = mysqli_query($connection, $myQuery); 	


echo '<div class="tab6">';
echo 	'<input id="tab-6" type="checkbox" name="tab">';
echo 	'<label for="tab-6" id="label2">RnB</label>';
echo 		'<div class="tab-content6">';
echo 			'<div class="musicTable">';
echo 				'<table class="musicSelectionsTable">';


$num = 1;			
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	echo '<table class="musicSelectionsTable">';
	echo '<tr>';
	echo '<td class="thumb" style="width: 20%;"><img class="thbButton" style="width: 100%; border-radius: 50%;" src="'.$row["image"].'"     alt="thub Button">'.'</td>'; 
	echo '<td class="num" style="text-align:center; width:58px; padding: 0 15px;">'.$num.'</td>';
	echo '<td class="artist">'.$row["artist"].'</td>';
	echo '<td class="name" style="padding: 0 20px 0 0; text-align:left;"><a href="FeaturedSong.php?id='.$row["track_id"].'&trackName='.$row["name"].'" style="text-decoration: none;">'.$row["name"].'</a></td>';
	echo '<td class="genreHome" style="width: 74px">'.$row["genre"].'</td>';
	echo '<td style="text-align:center;"><audio controls style="width: 20px;">'.'<source src="'.$row["sample"].'">'.'type="audio/mpeg"'.'</audio></td>';
	echo '<br>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="albumSearch" style="font-size: 12px; text-align: center;"><a href="Album.php?album='.$row['album'].'&artist='.$row['artist'].'&albumPage=true" style="text-decoration: none;">'.$row["album"].'</a></td>';
	echo '</tr>';
	
	echo '</table>';
	
	$num++;
		
	}

?>
				</table>
			</div>
		</div>
	</div>		

</div>

<!--====================FOOTER=====================-->
<footer style="margin: 80px 0 0 0;">
	<div class="footerMenu">
		<a href="Home.php">HOME</a>
		<br>
		<a href="About.php">ABOUT</a>
		<br>
		<a href="MusicSelection.php"><span style="color:#DB53FB">MUSIC</span></a>
		<br>
		<a href="Account.php">ACCOUNT</a>
        <br>
		<img class="musicPlanetLogo" src="Images/MusicPlanetLogo.png" alt="Music Planet Logo">
	</div>

	<p id="footerParagraph">Created by Andreas Georgiou</p>
</footer>

</div>

</body>

</html>