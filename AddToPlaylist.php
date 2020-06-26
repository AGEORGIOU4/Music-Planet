<?php session_start();?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add to Playlist</title>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='icon' href='Images/favicon.ico' type='image/x-icon'>
</head>

<body>

<!--=======================CONNECT TO THE DATABASE AND IMPORT LOGIN INFORMATION=========================-->
<?php
$connection = mysqli_connect("localhost", "ageorgiou4", "BgbW8708", "ageorgiou4");

$playlistQuery = "SELECT * FROM playlist";
$playlistResult = mysqli_query($connection, $playlistQuery);

// Create connection
if (mysqli_connect_errno()) 
{
	echo '<script type="text/javascript">alert("Connection error.");</script>';
} 

//SWITCH SESSION USERNAME VARIABLES
if($_SESSION['username'] == "") {
$username = $_SESSION['usernameR'];
}
else {
$username = $_SESSION['username'];
}

// Variables
// DELETE

$deleteVar = $_GET['deleteTrack'];

if ($deleteVar == "5") {
	
	$playlistId = $_GET['playlist_id'];
	while ($row = mysqli_fetch_array($playlistResult)) {	
		$sql = "DELETE FROM playlist WHERE playlist_id = '$playlistId'";
		header("refresh:0;url=MyPlaylist.php");

		if(!mysqli_query($connection, $sql)) {	
		echo "Not inserted!";
		}	
	}
}

else if ($deleteVar == "6") {

// INSERT
$track_name = $_GET['trackName'];
$product_id = $_GET['id'];
$image = $_GET['image'];
$duplicated = "5";
$playlist_id = 0;
$duplicated = 5;

while ($row = mysqli_fetch_array($playlistResult)) {
	if( $playlist_id < $row['playlist_id']) {
		$playlist_id = $row['playlist_id'];
	}
	
	if ($username == $row['username'] && $track_name == $row['track_name']) {
			echo '<script type="text/javascript">alert("This song is already added to your playlist!");</script>';
			$duplicated = 6;
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
}	

$playlist_id += 1;

if($duplicated == 5){		
$sql = "INSERT INTO playlist (playlist_id, username, product_id, track_name, image) VALUES ('$playlist_id', '$username', '$product_id', '$track_name', '$image')";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		if(!mysqli_query($connection, $sql)) {	
		echo "Not inserted!";
		}
	}
}
			 
?>
</body>

</html>