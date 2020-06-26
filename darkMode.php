<?php session_start();
		echo $_SESSION['cM'];

if($_SESSION['buttonLabel'] == "Dark Mode") {
	$_SESSION['buttonLabel'] = "Light Mode";
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}

else if($_SESSION['buttonLabel'] == "Light Mode") {
	$_SESSION['buttonLabel'] = "Dark Mode";
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>

<a href="Home.php">Home</a>