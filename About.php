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
    <title>About</title>
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
            <li class="hiddenList"><a href="About.php"><span style="color:#DB53FB">ABOUT</span></a></li>
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
<div id="aboutUsParagraphSection">
    <h1 id="aboutUsHeading">About</h1>
    <p id="aboutUsParagraph" style="margin: -30px 0 0 0; width:100%">
        Music Planet by using media queries is a fully responsive (PC, tablet, mobile) on-line music streaming
        application
        that is designed in a minimalistic style, sans serif font and
        <mark id="mark1">#DB53FB</mark>
        as a basic colour.
        By developing a concise and clear interface, navigation menu, and links on footer it satisfies
        <strong>HCI</strong> on a
        high level and provides to its users a friendly interface and a nice experience. The application
        is simple and easy to use on both mobile and desktop,
        <mark>marks</mark>
        the emphasized content, <strong>provides important tags</strong>
        and alt text for accessibility. There is a search bar on homepage for easier searching, which is not fully
        working as it is a prototype version.
    </p>
    <hr class = "aboutUsHR">

    <p id="sentence">
        A piece of code and ideas obtained from:
    </p>

    <hr class = "aboutUsHR">

    <div class="image">
        <img id="headphones" src="Images/headphones.jpg" alt="Headphones">
    </div>
</div>

<hr class = "aboutUsHR">

<p id="references"><span style="font-weight: bold">REFERENCES</span><br><br>

    Toh, W.S. (n.d), "3 Steps Simple Responsive Accordion With Pure CSS", <span
            style="font-style: italic">CODE-BOXX</span> Accessed on
    18/11/19, Available at: https://code-boxx.com/simple-responsive-accordion-pure-css/
    <br><br>
    1) CODE-BOXX https://code-boxx.com/<br>
    2) w3 schools https://www.w3schools.com<br>

</p>

<!--====================FOOTER=====================-->
<footer>
    <div class="footerMenu">
        <a href="Home.php">HOME</a>
        <br>
        <a href="About.php"><span style="color:#DB53FB">ABOUT</span></a>
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