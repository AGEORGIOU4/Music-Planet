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
<?php
$connection = mysqli_connect("localhost", "ageorgiou4","BgbW8708", "ageorgiou4");

if($_SESSION["username"] == "" && $_SESSION["usernameR"] == "") {
	header('Location: Login.php');
}

if (mysqli_connect_errno())
{
echo "ERROR: could not connect to database: " . mysqli_connect_error();
}

if($_SESSION['randomKey'] == 1) {
// Array for random playlist
$myQuery = "SELECT * FROM tracks";
$result = mysqli_query($connection, $myQuery); 	

$numOfTracks = 1;

// Find the number of tracks
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$numOfTracks++;	
}
	$_SESSION['array'] = array(10);
	// Create a loop (length is the number of random songs) and generate 10 random numbers
	for($i = 0; $i < 10; $i++) {

		// Populate the array with random numbers
		$_SESSION['array'][$i] = rand(0, $numOfTracks);
	}
		$_SESSION['savePlaylist'] = true;

	$_SESSION['randomKey'] = 2;
	header('Location: RandomPlaylist.php');	
}
?>

</body>

</html>