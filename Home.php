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
    <title>Home</title>
    <link rel="stylesheet" href="<?php echo $_SESSION['mode'];?>">
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

if (mysqli_connect_errno()){
	echo "ERROR: could not connect to database: " . mysqli_connect_error();
}
// Set sessions variables 
$_SESSION['usernameRauto'] = "";
$_SESSION['passwordRauto'] = "";
$_SESSION['planRauto'] = "";
$_SESSION['signUpFormKey'] = 1;
$_SESSION['autofill'] = 1;
$_SESSION['cP'] = 1;

// Array for random playlist
$myQuery = "SELECT * FROM tracks";
$result = mysqli_query($connection, $myQuery); 	

$numOfTracks = 1;

// Find the number of tracks
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$numOfTracks++;	
}

if($_SESSION['savePlaylist'] == false) {
	$_SESSION['array'] = array(10);
	// Create a loop (length is the number of random songs) and generate 10 random numbers
	for($i = 0; $i < 10; $i++) {
		// Populate the array with random numbers
		$_SESSION['array'][$i] = rand(0, $numOfTracks);
	}
		$_SESSION['savePlaylist'] = true;
}

// Switch Session variables
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
if ($_SESSION['restrictedAccess'] == 2) {
	echo '<script type="text/javascript">alert("Guest Version can not access Music Page! Please sign up.");</script>';
	$_SESSION['restrictedAccess'] = 1;
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
	if(($_SESSION["username"] == $row["username"])) {
		$_SESSION["currentId"]= $row["id"];
		$_SESSION["planR"] = $row['plan'];
	}
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
            <li class="hiddenList"><a href="Home.php"><span style="color:#DB53FB">HOME</span></a></li>
            <li class="hiddenList"><a href="About.php">ABOUT</a></li>
            <li class="hiddenList"><a href="MusicSelection.php">MUSIC</a></li>
            <li class="hiddenList"><a href="Plans.php">PLANS</a></li>
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
<!--====================PART I=====================-->

<?php $numberForRatings = 0;
// Recommendations algorithm
$reviewQuery = "SELECT * FROM reviews WHERE name = '$username'";
$recommendationsResult = mysqli_query($connection, $reviewQuery);

$tracksQuery = "SELECT DISTINCT genre  FROM tracks";
$tracksResult = mysqli_query($connection, $tracksQuery);

$tracks2Query = "SELECT *  FROM tracks";
$tracks2Result = mysqli_query($connection, $tracks2Query);

// Check how many songs did the user rated
while ($recommendationRow = mysqli_fetch_array($recommendationsResult, MYSQLI_ASSOC)) {
	$numberForRatings++;
}

if(isSet($_GET['cleanbtnRecomm'])) {
	$sql = "DELETE FROM reviews WHERE name = '$username' AND review = ''";
	header("Refresh:0; url=Home.php");
	if(!mysqli_query($connection, $sql)) {	
		echo '<script type="text/javascript">alert("Something went wrong.Please try again!");</script>';
	header("Refresh:0; url=Home.php");
	}
}

if($numberForRatings == 0) {
	// Trigger/Open The Modal -->
	echo '<div>';
	echo '<button id="myBtn" onclick="ShowModal();">Recommendations</button>';
	echo '</div>';
	// -- The Modal -->
	echo '<div id="myModal" class="modal">';
	  // -- Modal content -->
	  echo '<div class="modal-content">';
		echo '<span class="close" onclick="HideModal()">&times;</span>';
			echo '<form method="get" style="margin: 6px;" id="favForm">';
				echo '<label for="fav" style="margin-right: 7px;">What is your favorite genre?    </label>';
				echo '<select id="fav" name="fav">';
				
					while($row= mysqli_fetch_array($tracksResult)) {
						echo '<option name="favGenre" value="'.$row['genre'].'">'.$row['genre'].'</option>';
					}
				echo '</select>';
				echo '<button type="submit" name="favSubmit">Submit</button>';
				echo '</form>';
			echo '</div>';
	echo '</div>';

if(isSet($_GET['fav'])) {
		while($row2= mysqli_fetch_array($tracks2Result)) {
			if($row2['genre'] == $_GET['fav']) {
				$productId = $row2['track_id'];
				break;
			}
		}
	$reviewRate = 5;
	$reviewComment = "";
	$reviewTrack = "";
	$imageUser = "";
	$thumbTrack = "";
	
	$sql = "INSERT INTO reviews (product_id, name, rating, review, track, thumb, image) VALUES ('$productId', '$username', '$reviewRate','$reviewComment', '$reviewTrack', '$thumbTrack', '$imageUser')";
	echo '<script type="text/javascript">alert("Thank you for your feedback '. $username.'!");</script>';
	header("Refresh:0; url=Home.php");
	
	if(!mysqli_query($connection, $sql)) {	
	echo "Not inserted!";
	header("Refresh:0; url=Home.php");
		}
	}
}

?>
<div id="part1">
    <h1 id="heading">Welcome<br>to the<br>Music Planet</h1>
    <h6 id="subHeading">The world's leading music platform</h6>

    <!--Search Bar-->
	<form method="get">
    <div class="search" style="width: 70%;">
	    <label for="searchBar"></label>
        <input type="text" placeholder="Search artist, album, or track... " style="height: 40px; float: left; margin: 8px 0 35px;" id="searchBar" name="searchBox">
	</div>	
	<div style="width: 15%; float: left;">
         <button type="submit" id="searchMagn"name="search" style="height: 40px; border: 1px solid #ccc; padding: 0px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); float: left;">&#128270;</button>
    </div>
	</form>
	
	<form method="get">
	<div style="width: 15%; float: left;">
         <button id="cleanbtn" type="submit" name="clean" style="height: 40px; border: 1px solid #ccc; padding: 0px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); float: left; font-size: 20px;
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
	$limit = 130;
	
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

<!--=====SLIDESHOW========(obtained from w3 schools)-->
<!-- Slideshow container -->

<hr style="width: 90%;">

<div class="slideshow-container">
<h3 id="albums" style="font-size: 30px; padding-bottom: 0">Top-rated Songs!</h3>
<img src="Images2/stars.png" style="width: 60%;">

<?php 
$reviewsQuery = "SELECT DISTINCT name, review, rating, product_id, track, thumb, image FROM reviews WHERE rating = 5 AND review IS NOT NULL LIMIT 5";
$reviewsResult = mysqli_query($connection, $reviewsQuery);

$trackQuery = "SELECT * FROM tracks";
$trackResult = mysqli_query($connection, $trackQuery);

$num=1;

while ($row = mysqli_fetch_array($trackResult, MYSQLI_ASSOC)) {
	while($rowReview = mysqli_fetch_array($reviewsResult, MYSQLI_ASSOC)) {
				// <!-- Full-width images with number and caption text -->
				echo  '<div class="mySlides fade">';
				echo 	'<div>';
				echo    '<img src="'.$rowReview['thumb'].'" style="width:30%;  border-radius: 5px; margin-bottom: 20px;">';
				echo    '<div class="text"><a href="FeaturedSong.php?id='.$rowReview["product_id"].'&trackName='.$rowReview["track"].'" style="text-decoration: none;">'.$rowReview["track"].'</a></div>';
				echo 	'<br>';
				echo    '<div class="text">'.$rowReview['name'].'</div>';
				echo    '<div><img src="'.$rowReview['image'].'" style="border-radius: 50%; width: 50px; height: 50px; padding: 5px; background-color: #ccc;"></div>';
				echo    '<div class="text">'.$rowReview['review'].'</div>';
				echo '</div>';
				echo  '</div>';
		$num++;
	}
}
?>
  <!-- Next and previous buttons -->
  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
  <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>
<br>

<!-- The dots/circles -->
<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span>
  <span class="dot" onclick="currentSlide(2)"></span>
  <span class="dot" onclick="currentSlide(3)"></span>
  <span class="dot" onclick="currentSlide(4)"></span>
  <span class="dot" onclick="currentSlide(5)"></span>
</div>

 <div style="text-align: center; width: 100%;"><img id="arrow" src="Images/arrow.gif" alt="arrow"></div>
	 
<div id="aboutUsHome">
	<img src="Images/homePic.jpg" id="homePic" style="width: 100%;">
    <p id="aboutUsParagraphHome">
        At music planet our primary goal is to provide the best music and streaming services.
        We offer a huge collection of tracks from the early 60's till the modern age.
    </p>
</div>

<?php
$number = 0;
// Recommendations algorithm
$recommendationsQuery = "SELECT * FROM reviews WHERE name = '$username' AND rating > 2 ORDER BY product_id ASC";
$recommendationsResult = mysqli_query($connection, $recommendationsQuery);
$recommendationsQuery2 = "SELECT * FROM reviews WHERE name = '$username' AND rating > 2 ORDER BY product_id ASC";
$recommendationsResult2 = mysqli_query($connection, $recommendationsQuery);

$tracksQuery = "SELECT * FROM tracks ORDER BY track_id ASC";
$tracksResult = mysqli_query($connection, $tracksQuery);


// Check how many songs did the user rated
while ($recommendationRow = mysqli_fetch_array($recommendationsResult, MYSQLI_ASSOC)) {
	$number++;
}

// The algorithm will run only if the user rate at least one song
if ($number > 0) {
	$reviewsArray = array($number);
	$genresArray = array($number);
	
	$i = 0;
	// Populate an array with the id of these songs
	while ($recommendationRow2 = mysqli_fetch_array($recommendationsResult2, MYSQLI_ASSOC)) {
		$reviewsArray[$i] = $recommendationRow2['product_id'];
		$i++;
	}
	
	$x = 0;

	// Sync these id's from reviews table with the tracks table to find their genre	
	while ($tracksRow = mysqli_fetch_array($tracksResult, MYSQLI_ASSOC)) {

		if ($tracksRow['track_id'] == $reviewsArray[$x]) {
			
			// Populate the array with the genres now
			$genresArray[$x] = $tracksRow['genre'];
			if($x + 1 < $number) {
				$x++;
			}
		}
	}
	$j = rand(0, $x -1);
	// Recommendations
	$tracksQuery2 = "SELECT * FROM tracks WHERE genre = '$genresArray[$j]' ORDER BY RAND() LIMIT 5";
	$tracksResult2 = mysqli_query($connection, $tracksQuery2);
	if($number > 2) {
		$width = "100%";
	} else {
		$width = "50%";
	}

echo '<hr style="width: 90%;">';
echo '<div class="slideshow-container">';
echo '<h3 id="albums" style="font-size: 30px; padding-bottom: 0">Recommended for you!</h3>';
echo '<img src="Images2/recommended.png" style="width: 60%;">';
echo '<button type="submit" id="refreshButton" name="generateNew" style="padding: 0; border: 0px solid; background: none; width: '.$width.'; float: left; height: 50px;">';
	echo '<img id="refreshImg" style="width: 40px; height: 40px;" src="Images/refresh.png" alt="Refresh Button" onclick="reloadPage()">';
echo '</button>';
echo '<div style="width: 50%; float: left;">';
		echo '<form method="get">';
			if($number < 2) {
				echo '<button id="cleanbtnRecomm" type="submit" name="cleanbtnRecomm" style="height: 50px; padding: 0px; float: left; font-size: 30px; color: #268029; ">&#9851;</button>';
			}
		echo '</form>';
	echo '</div>';
echo '<br>';


$one = 1;
	while ($tracksRow2 = mysqli_fetch_array($tracksResult2, MYSQLI_ASSOC)) {
	echo '<table class="musicSelectionsTable">';
	echo '<tr>';
	echo '<td class="thumb" style="width: 20%;"><img class="thbButton" style="width: 100%; border-radius: 50%;" src="'.$tracksRow2["image"].'"     alt="thub Button">'.'</td>'; 
	echo '<td class="num" style="text-align:center; width:58px; padding: 0 15px;">'.$one.'</td>';
	echo '<td class="artist">'.$tracksRow2["artist"].'</td>';
	echo '<td class="name" style="padding: 0 20px 0 0; text-align:left;"><a href="FeaturedSong.php?id='.$tracksRow2["track_id"].'&trackName='.$tracksRow2["name"].'" style="text-decoration: none;">'.$tracksRow2["name"].'</a></td>';
	echo '<td class="genreHome" style="width: 74px">'.$tracksRow2["genre"].'</td>';
	echo '<td style="text-align:center;"><audio controls style="width: 20px;">'.'<source src="'.$tracksRow2["sample"].'">'.'type="audio/mpeg"'.'</audio></td>';
	echo '<br>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="albumSearch" style="font-size: 12px; text-align: center;"><a href="Album.php?album='.$tracksRow2['album'].'&artist='.$tracksRow2['artist'].'&albumPage=true" style="text-decoration: none;">'.$tracksRow2["album"].'</a></td>';
	echo '</tr>';
	
	echo '</table>';
	
	$one++;
		
	}
}
?>

<!--============COMMENTS SLIDE SHOW================-->
<script>
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}

</script>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
function ShowModal() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
function HideModal() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

function reloadPage() {
            location.reload();
        }
</script>

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