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
    <title>Plans</title>
    <link rel="stylesheet" href="<?php echo $_SESSION['mode'];?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='icon' href='Images/favicon.ico' type='image/x-icon'>
</head>

<body>

<!--=====================BODY======================-->

<?php
$connection = mysqli_connect("localhost", "ageorgiou4","BgbW8708", "ageorgiou4");
$_SESSION['cP'] = 1;

if($_SESSION["username"] == "" && $_SESSION["usernameR"] == "") {
	header('Location: Login.php');
}

if (mysqli_connect_errno())
{
echo "ERROR: could not connect to database: " . mysqli_connect_error();
}

if ($_SESSION['restrictedAccess'] == 2) {
	echo '<script type="text/javascript">alert("Guest Version can not access Music Page! Please sign up.");</script>';
	$_SESSION['restrictedAccess'] = 1;
}

//SWITCH SESSION USERNAME VARIABLES
if($_SESSION['username'] == "") {
$username = $_SESSION['usernameR'];
}
else {
$username = $_SESSION['username'];
}

if ($_SESSION['welcomeKey'] == 2) {
echo '<script type="text/javascript">alert("Welcome '.$_SESSION['username'].'!");</script>';
$_SESSION['welcomeKey'] = 1;
}
// Change Plan
if (isSet($_GET['planSubmit'])) {
	
	if ($_SESSION["username"] == "guest") {
		echo '<script type="text/javascript">alert("You are on guest mode! Please sign up.");</script>';
		
	} else if ($_GET['planSubmit'] == $_SESSION["planR"]) {
		echo '<script type="text/javascript">alert("You already subscribe for this plan.");</script>';
		
	} else {
		$_SESSION['changePlan'] = $_GET['planSubmit'];
		$_SESSION['planR'] = $_GET['planSubmit'];
		$_SESSION['cP'] = 2;
	header('Location: Account.php');
	}
}

if (isSet($_POST["logout"])) {
	$_SESSION["username"] = "";
	$_SESSION["usernameR"] = "";
	$_SESSION["currentId"] = "";
	$_SESSION["currentPlan"] = "";
	$_SESSION["planR"] = "";
	$_SESSION["changePlan"] = "";
}
	
$loginQuery = "SELECT * FROM login";
$loginResult = mysqli_query($connection, $loginQuery);

// Set current session ID  
while($row = mysqli_fetch_array($loginResult)) {
	if($_SESSION["username"] == $row["username"]) {
		$_SESSION["currentId"]= $row["id"];
		$_SESSION["planR"] = $row['plan'];
	}
}
		
if($_SESSION["username"] == "" && $_SESSION["usernameR"] == "") {
	header('Location: Login.php');
}

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
            <li class="hiddenList"><a href="Plans.php"><span style="color:#DB53FB">PLANS</span></a></li>
            <li class="hiddenList"><a href="Account.php">ACCOUNT</a></li>
            <li class="hiddenList"><a href="Home.php"><img id="musicPlanetLogo2" src="Images/MusicPlanetLogo2.png" alt="Music Planet Logo"></a></li>
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
				
				if($_SESSION["planR"] == $row["title"] && $username != "guest") {
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


<!--===================PART III=====================-->
<div id="part3">
    <p id="plansBtn">PLANS</a></p>
</div>
<!--=======================CONNECT TO THE DATABASE AND IMPORT OFFERS=========================-->
<?php
$connection = mysqli_connect("localhost", "ageorgiou4","BgbW8708", "ageorgiou4");
if($_SESSION["username"] == "" && $_SESSION["usernameR"] == "") {
	header('Location: Login.php');
	}
	
if (mysqli_connect_errno())
	{
	echo "ERROR: could not connect to database: " . mysqli_connect_error();
	}
	else {
	
	// Offers cards
	$offersQuery = "SELECT * FROM offers";
	$offersResult = mysqli_query($connection, $offersQuery);

	echo '<ol class="offersList">';
		while ($row = mysqli_fetch_array($offersResult, MYSQLI_ASSOC)) {
			echo '<div class="container" id="offersList">';
			echo '<li class="offersList" style="font-weight: 600; border: 0px; font-size: x-large;">' . $row["title"] . '</li>';
			echo '<br>';
			echo '<li style="font-size: small;">' . $row["description"] . '</li>';
			echo '<li style="font-size: small;">' . "£" . $row["price"] . '</li>';
			echo '<td><img style="width:60%; padding: 20px 0 0px;" src="'.$row["image"].'">'.'</td>'; 
			echo '<form method="get" action="";>';
			echo '<button type="submit" name="planSubmit" value="'.$row["title"].'" style="background-color: #DB53FB; width:100%; margin: 20px 0 0; ">Subscribe</button>';
			echo '</form>';	
			echo '</div>';
		}
			echo '</ol>';
	}
?>

</div>
<!--====================FOOTER=====================-->
<footer>
    <div class="footerMenu">
        <a href="Home.php"><span style="color:#DB53FB">HOME</span></a>
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

</body>

</html>