<?php session_start();?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration Page</title>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='icon' href='Images/favicon.ico' type='image/x-icon'>
</head>

<body>

<!--=======================CONNECT TO THE DATABASE AND IMPORT LOGIN INFORMATION=========================-->
<?php
$connection = mysqli_connect("localhost", "ageorgiou4", "BgbW8708", "ageorgiou4");

$_SESSION['username'] = "";	 
	
if($_SESSION["username"] == "" && $_SESSION["usernameR"] == "") {
	header('Location: SignUp.php');
}

if (mysqli_connect_errno())
{
echo "ERROR: could not connect to database: " . mysqli_connect_error();
}

if($_SESSION['autofill'] == 2) {
	$_SESSION['autofill'] = 1;
	header('Location: SignUp.php');
}

$loginQuery = "SELECT * FROM login";
$loginResult = mysqli_query($connection, $loginQuery);

$passwordR = "";
$usernameR = $_SESSION["usernameR"];
$passwordR = $_SESSION["passwordR"];
$planR = $_SESSION["planR"];
$idR = $_SESSION["idNumber"] + 1;
$counter=1;
$checkUsernameR = 1;
$rowsNum = mysqli_num_rows ( $loginResult );

while($row = mysqli_fetch_array($loginResult)) {
	if($usernameR == $row["username"]) {
	$checkUsernameR = 2;
	}
	$row++;
} 

if ($checkUsernameR == 2) {
	echo '<script type="text/javascript">alert("Invalid username!");</script>';
	header("refresh:0;url=SignUp.php");
}	

if ($checkUsernameR == 1) {
	$image="Images2/avatar.svg";
	$sql = "INSERT INTO login (id, username, password, plan, image) VALUES ('$idR', '$usernameR', '$passwordR', '$planR', '$image')";
	echo '<script type="text/javascript">alert("Registered! Welcome '.$usernameR.'!");</script>';
	$_SESSION['username'] = $_SESSION['usernameR'];	 
	header("refresh:0;url=Home.php"); 
		if(!mysqli_query($connection, $sql)) 
		{	
		echo "Not inserted!";
		}
}	
?>
</body>

</html>