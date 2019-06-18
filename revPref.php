<!--

revPref.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This page gives users the ability to choose their journal file from their filesystem.
This page also supplies 3 dropdown menus for preferred and non-preferrend reviewers for the journal.

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu
	pref(1-3)	- users new preferences
	
-->
<html>
<head>
<title>Select Preferences</title>
<link href="stylerevpref.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="rectangle"></div>
<h1>Select Preferences</h1>
<?php
	if(!isset($_POST["username"]) or !isset($_POST["lgdin"])){
	  echo '
<p>Please Login</p>
<form action="index.php" method="post">
	<input type="submit" value="Return to Login Page">
</form>';
	  die();
	}
	$username = $_POST["username"];
	$lgdin = $_POST["lgdin"];
	
		// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");
	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
	// end verify
	
	
	//
	if(isset($_POST["pref1"]) and $_POST["pref1"] != ""){
		mysqli_query($con,"DELETE FROM revprefs WHERE reviewer='$username'");
		$pref1 = $_POST["pref1"];
		$pref2 = $_POST["pref2"];
		$pref3 = $_POST["pref3"];
		mysqli_query($con,"INSERT INTO revprefs VALUES('$pref1','$username',1)");
		if($pref2 != $pref1){
			mysqli_query($con,"INSERT INTO revprefs VALUES('$pref2','$username',1)");
		}
		if($pref3 != $pref1 and $pref3 != $pref2){
			mysqli_query($con,"INSERT INTO revprefs VALUES('$pref3','$username',1)");
		}
	}
	//
	
	
	$query = "SELECT * FROM revprefs, users WHERE reviewer='$username' AND userName=submitter";
	$result = mysqli_query($con,$query);
	if(mysqli_num_rows($result)){
		// submit journal
		echo '
<form>
	<div class="list">
		<option value="">Your Current Author Preferences</option>
	</div><br>';
		while($row = mysqli_fetch_array($result)){
			echo '
	<div class="list">
		<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>
	</div><br>';
		}
		echo '
</form>';
	}else{
		echo "
<p>You have not submitted your preferences yet</p>";
	}
	echo '
<form action="revPref.php" method="post" enctype="multipart/form-data">
	<div class="preferred">
		Preference 1: <select name="pref1" required>';
	$query = "SELECT * FROM users WHERE (type=2 OR type=1) AND userName<>'$username' ORDER BY lastName ASC";
	$result = mysqli_query($con,$query);
	echo '
			<option value="">Select an Author</option>';
	while($row = mysqli_fetch_array($result)){
		echo '
			<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo '
		</select>
	</div>
	<div class="preferred2">
		Preference 2: <select name="pref2">';
	$result = mysqli_query($con,$query);
	echo '
			<option value="">Select an Author</option>';
	while($row = mysqli_fetch_array($result)){
		echo '
			<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo '
		</select>
	</div>
	<div class="preferred3">
		Preference 3: <select name="pref3">';
	$query = "SELECT * FROM users WHERE (type=2 OR type=1) AND userName<>'$username' ORDER BY lastName ASC";
	$result = mysqli_query($con,$query);
	echo '
			<option value="">Select an Author</option>';
	while($row = mysqli_fetch_array($result)){
		echo '
			<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo '
		</select>
	</div>
	<input type="hidden" name="username" value ='.$username.'>
	<input type="hidden" name="lgdin" value=1>
	<div class="uploadJournal">
		<input type="submit" value="Update Preferences">
	</div>
</form>';
?>

<div class="buttons">
	<div class="returnMenuButton">
		<form action="login.php" method="post">
			<input type="hidden" name="username" value="<?php echo $username; ?>">
			<input type="hidden" name="lgdin" value=1>
			<input type="submit" value="Return to Main Menu">
		</form>
	</div>
	<div class="logoutButton">
		<form action="../index.php" method="post">
			<input type="submit" value="Logout">
		</form>	
	</div>
</div>

</body>
</html>
