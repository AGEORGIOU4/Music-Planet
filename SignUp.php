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
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="styles.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='icon' href='Images/favicon.ico' type='image/x-icon'>
</head>

<body>

<!--====================HEADER=====================-->
<!--Header with an accordion hamburger dropdown menu-->
<header>
    <img class="musicPlanetLogo" src="Images/MusicPlanetLogo.png" alt="Music Planet Logo">
</header> 

<?php

if($_SESSION['signUpFormKey'] == "") {
		header('Location: Login.php');
}

if(isSet($_POST['planSubmit'])) {
	$_SESSION['usernameRauto'] = $_POST['usernameR'];
	$_SESSION['passwordRauto'] = $_POST['passwordR'];
	$_SESSION['planRauto'] = $_POST['planSubmit'];
		header("Location: RegistrationPage.php"); 
		$_SESSION['autofill'] = 2;
}

$_SESSION['autofill'] = 1;

if($_SESSION['planRauto'] == "") {
	$_SESSION['planRauto'] = "Choose a plan";
}

$connection = mysqli_connect("localhost", "ageorgiou4", "BgbW8708", "ageorgiou4");

$offersQuery = "SELECT * FROM offers";
$offersResult = mysqli_query($connection, $offersQuery);

$_SESSION['username'] = "";
$_SESSION['changePlan'] = "";
$_SESSION['mode'] = "styles.css";
$_SESSION['buttonLabel'] = "Dark Mode";
$_SESSION['cM'] = 1;
$_SESSION['restrictedAccess'] = 1;
$_SESSION["currentId"] = "";
$_SESSION['savePlaylist'] = false;
$_SESSION['searchQueryVar'] = "or";

?>

<!-- SIGN UP FORM -->
<form class="signUpForm" action="" method="post">
<div class="imgcontainer">
  <img src="Images2/avatar.svg" alt="Avatar" class="avatar">
  <div id="uploadFile">
   <input type="file" id="file" name="uploadImage" accept="image/*">
  <label for="file" id="uploadLabel"><span class="material-icons">add_photo_alternate</span>Choose photo</label>
</div>
</div>

<div class="container">
  <label for="usernameR"><b>Username</b></label>
  <input type="text" placeholder="Enter Username" value="<?php echo $_SESSION['usernameRauto']?>" id="usernameR" name="usernameR">

  <label for="passwordR"><b>Password</b></label>
  <input type="password" placeholder="Enter Password" value="<?php echo $_SESSION['passwordRauto']?>" id="passwordR" name="passwordR">
	<label for="planChoices"><b>Choose a plan</b></label>
	<select id = "planChoices" class="planChoices" value="<?php echo $_SESSION['planRauto']?>" name="planR">
	<option class="planChoices" value="<?php echo $_SESSION['planRauto']?>"><?php echo $_SESSION['planRauto'];?></option>

<?php
// Create plans box on sign up form
while ($row = mysqli_fetch_array($offersResult, MYSQLI_ASSOC)) {
  echo '<option class="planChoices" value="'. $row["title"].'">'. $row["title"]." - £". $row["price"].'</option>';
}

?>  
	</select>
	
	<p style="color:red; text-align:center;">*Check the plans below</p>
	
	<button type="submit" name="submitR" style="background-color:#f44336;">Sign Up</button>
</div>

<div class="container" style="background-color:#f1f1f1; text-align:right;">
  <span>Already a member?<button type="button" onclick="window.location.href = 'https://vesta.uclan.ac.uk/~ageorgiou4/Login.php';" class="cancelbtn" style=" margin-left: 35px; background-color:#268029;">Login</button></a></span>
</div>

<div id="plans" style="border-radius: 40px;">

<!--=======================CONNECT TO THE DATABASE AND IMPORT LOGIN INFORMATION & PLANS=========================-->
<?php
$connection = mysqli_connect("localhost", "ageorgiou4", "BgbW8708", "ageorgiou4");

// Login data to checj if username is available
$loginQuery = "SELECT * FROM login";
$loginResult = mysqli_query($connection, $loginQuery);

$_SESSION['welcomeKey'] = 1;
$idNum = 0;

// Offers cards
$offersQuery = "SELECT * FROM offers";
$offersResult = mysqli_query($connection, $offersQuery);

while($row = mysqli_fetch_array($loginResult)) {
	  if($row['id'] >= $idNum) {
	  $idNum = $row['id'];
	  }
   }
  
// Create connection
if (mysqli_connect_errno()) {
	echo '<script type="text/javascript">alert("Connection error.");</script>';
}

if(isSet ($_POST['submitR']) && $_POST['usernameR'] == "") {
	echo '<script type="text/javascript">alert("Please enter username!");</script>';
}

if(isSet ($_POST['submitR']) && $_POST['passwordR'] == "") {
	echo '<script type="text/javascript">alert("Please enter password!");</script>';
}

if(isSet ($_POST['submitR']) && $_POST['planR'] == "Choose a plan") {
	echo '<script type="text/javascript">alert("Please select a plan!");</script>';
}

// Apply all the necessary validation rules before submit
if (isSet ($_POST['submitR']) && $_POST['usernameR'] != "" && $_POST['passwordR'] != "" && $_POST['planR'] != "Choose a plan" ) {
	$image = "Images2/avatar.svg";
	$_SESSION["image"] = $image;
	$checkUser = 1;
	$usernameR = $_POST['usernameR'];
	$passwordR = $_POST['passwordR'];
	$_SESSION["idNumber"] = $idNum;
	$_SESSION["usernameR"]= $usernameR;

	//ENCRYPT THE PASSWORD USING SHA1
	$_SESSION["passwordR"]= sha1($passwordR);
	
	$_SESSION["planR"] = $_POST['planR']; 
	
	header('Location: RegistrationPage.php');
}
	// PLANS COLUMNS WITH DATA FROM DB
echo '<ol class="offersList">';

while ($row = mysqli_fetch_array($offersResult, MYSQLI_ASSOC)) {
	echo '<div class="container" id="offersList" id="offersList" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); border: 1px solid #ccc; border-radius: 5px; padding: 30px;">';
	echo '<li class="offersList" style="font-weight: 600; border: 0px; font-size: x-large;">' . $row["title"] . '</li>';
	echo '<br>';
	echo '<li style="font-size: small;">' . $row["description"] . '</li>';
	echo '<li style="font-size: small;">' . "£" . $row["price"] . '</li>';
	echo '<td><img style="width:60%; padding: 20px 0 0px;" src="'.$row["image"].'">'.'</td>'; 
	echo '<button type="submit" name="planSubmit" value="'.$row["title"].'" style="background-color: #DB53FB; width:100%; margin: 20px 0 0; ">Subscribe</button>';
	echo '</form>';	
	echo '</div>';
}
echo '</ol>';

?>

</div>

</body>

</html>