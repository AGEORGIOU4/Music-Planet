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
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
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

<!-- LOGIN FORM -->
<form class="signUpForm" method="post" autocomplete="on">
<div class="imgcontainer">
  <img src="Images2/avatar.svg" alt="Avatar" class="avatar">
</div>

<div class="container">
  <label for="username"><b>Username</b></label>
  <input type="text" id="username" placeholder="Enter Username" name="username">

  <label for="password"><b>Password</b></label>
  <input type="password" id="password" placeholder="Enter Password" name="password">
	
  <button type="submit" name="login">Login</button>
  <button type="submit" name="guest" style="background-color:#313131;">Guest</button>
  <label>
	<input type="checkbox" checked="checked" name="remember"> Remember me
  </label>
</div>

<div class="container" style="background-color:#f1f1f1; text-align:right;">
	<span>Not a member?<button type="button" onclick="window.location.href = 'https://vesta.uclan.ac.uk/~ageorgiou4/SignUp.php';" class="cancelbtn" style=" margin-left: 35px;">Sign Up</button></span>
</div>

</form>

<!--=======================CONNECT TO THE DATABASE AND IMPORT LOGIN INFORMATION=========================-->
<?php
$connection = mysqli_connect("localhost", "ageorgiou4","BgbW8708", "ageorgiou4");
	$loginQuery = "SELECT * FROM login";
	$loginResult = mysqli_query($connection, $loginQuery);
	
	$_SESSION["username"] = "";
	$_SESSION["usernameR"] = "";
	$_SESSION["currentId"] = "";
	$_SESSION["planR"] = "";
	$_SESSION['changePlan'] = "";
	$_SESSION['welcomeKey'] = 1;
	$_SESSION['mode'] = "styles.css";
	$_SESSION['buttonLabel'] = "Dark Mode";
	$_SESSION['cM'] = 1;
	$_SESSION['restrictedAccess'] = 1;
	$_SESSION['usernameRauto'] = "";
	$_SESSION['passwordRauto'] = "";
	$_SESSION['planRauto'] = "";
	$_SESSION['signUpFormKey'] = 1;
	$_SESSION['savePlaylist'] = false;
	$_SESSION['searchQueryVar'] = "or";
	
/* Login using DB data */
if(mysqli_connect_errno()) {
	echo '<script type="text/javascript">alert("Connection error.");</script>';
}
	if(isSet($_POST['guest'])) {
		$_SESSION["username"]= "guest";
		$_SESSION["usernameR"]= "";
		$_SESSION["currentPlan"] = "Free version";
		$_SESSION["planR"] = "Free version";
		$_SESSION['changePlan'] = "Free version";
		$_SESSION["currentId"]= "0";
		$_SESSION['welcomeKey'] = 2;
		header('Location: Home.php');
	}
	
	if(isSet($_POST['login'])) {
		if($_POST['username'] == "" && $_POST['password'] == "") {
			$username = "";
			$password = "";
			$_SESSION["username"]= $username;
			$_SESSION["password"]= $password;
			$row="";
			echo '<script type="text/javascript">alert("Please enter credentials.");</script>';
			
		} else {
			$username = $_POST['username'];
			$password = sha1($_POST['password']);
			$_SESSION["username"]= $username;
			$_SESSION["password"]= $password;
			$_SESSION['welcomeKey'] = 2;
			
			while($row = mysqli_fetch_array($loginResult)) {
				if(($username == $row["username"]) && ($password == $row["password"])) {
					$_SESSION['usernameR']= "";
					$_SESSION["planR"]= $row["plan"];
					$_SESSION["currentId"]= $row["id"];
					header('Location: Home.php');
				}
				$row++;
			}
			if (($username != $row["username"]) || ($password != $row["password"])) {
			echo '<script type="text/javascript">alert("Wrong credentials.");</script>';
			}
		}
}
?>

</body>

</html>