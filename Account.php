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
    <title>Account</title>
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

if ($_SESSION['restrictedAccess'] == 2) {
	echo '<script type="text/javascript">alert("Guest Version can not access Music Page! Please sign up.");</script>';
	$_SESSION['restrictedAccess'] = 1;
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
			<li class="hiddenList"><a href="Account.php"><span style="color:#DB53FB">ACCOUNT</span></a></li>
            <li class="hiddenList"><a href="Home.php"><img id="musicPlanetLogo2" src="Images/MusicPlanetLogo2.png"
                                                    alt="Music Planet Logo"></a></li>
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
						$_SESSION['profileImage'] = $row['image'];
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
				
				if($_SESSION["planR"] == $row["title"] && $_SESSION["planR"] == $_SESSION['changePlan'] && $username != "guest") {
					echo '<a href="Account.php"><img src="'.$row['image'].'" alt="Profile" class="planMenu"></a>';	
				} else if ($_SESSION['changePlan'] == $row["title"]) {
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

<!--=====================BODY======================-->

<!-- User Profile Card-->
<div style="border-top: 3px solid #313131;"></div>
<div class="card">
	<div class="imgcontainer">
	<?php echo '<img src="'.$_SESSION['profileImage'].'"; style="margin: 20px 0 0 0; height: 90px; width: 90px;" alt="Avatar" class="avatar">';?>
	<h1><?php echo $_SESSION["username"]?></h1>
  
<?php
$offersQuery = "SELECT * FROM offers";
$offersResult = mysqli_query($connection, $offersQuery);

// CHANGE PLAN
if ($_SESSION["planR"] != $_SESSION['changePlan'] && $_SESSION["cP"] == 2) {
	$_SESSION["planR"] = $_SESSION['changePlan'];
	$changePlan = $_SESSION["changePlan"];

	$sql = "UPDATE login SET plan = '$changePlan' WHERE login.id =".$_SESSION["currentId"]."";
	echo '<script type="text/javascript">alert("Your plan has changed to '.$changePlan.'!");</script>';

	if(!mysqli_query($connection, $sql)) {	
	echo '<script type="text/javascript">alert("Something went wrong.Please try again!");</script>';
	}
	
}

echo '<h2 style="color: #848383">Your Plan: '.$_SESSION["planR"].'</h2>';
while ($row = mysqli_fetch_array($offersResult)) {
	if ($_SESSION["planR"] == $row["title"] && $username != "guest") {
	echo '<td><img style="width:50%; padding: 10px 0;" src="'.$row["image"].'">'.'</td>'; 
	}
}

// ACCOUNT PLAN PHOTO
if ($username == "guest") {
	echo '<td><img style="width:50%; padding: 10px 0;" src="Images2/freeVersion.png">'.'</td>'; 	
}
?> 

	</div>
		<form  method="post" action="">
			<button type="submit" name="deleteAccount" style="background-color:red; width: 95%;">Delete Account</button>
			<button type="submit" name="changePlan" style="width: 95%";>Change Plan</button>
		</form>
</div>

<?php 

// DELETE ACCOUNT
$loginQuery = "SELECT * FROM login";
$loginResult = mysqli_query($connection, $loginQuery);

// Create connection
if (mysqli_connect_errno()) 
{
	echo '<script type="text/javascript">alert("Connection error.");</script>';
}
 
if(isSet($_POST["deleteAccount"]) && $_SESSION["currentId"] == 0) {
	echo '<script type="text/javascript">alert("Guest can not be deleted!");</script>';
}

// Delete Account
if(isSet($_POST["deleteAccount"]) && $_SESSION["currentId"] != 0) {
	$sql = "DELETE FROM login WHERE login.id =".$_SESSION["currentId"]."";
	$_SESSION["username"] == "";
	$_SESSION["usernameR"] == "";
	$_SESSION["password"] == "";
	$_SESSION["currentId"] == 0;
	
	echo '<script type="text/javascript">alert("Deleted!");</script>';
	
	header('Location: SignUp.php');
		
	if(!mysqli_query($connection, $sql)) {	
		echo '<script type="text/javascript">alert("Something went wrong.Please try again!");</script>';
	}
}

if(isSET($_POST["changePlan"])) {
	if ($_SESSION["currentId"] == 0) {
		echo '<script type="text/javascript">alert("You are on guest mode! Please sign up.");</script>';
	} else {
	header('Location: Plans.php');
	}
}	
?>

</body>

</html>