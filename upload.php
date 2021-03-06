<!--

upload.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This page is used to upload a new journal or a revised version.
This page adds the journal's name and uploaded location to the db and adds the submitters preferences to the db.

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu
	resub		- (optional) if uploading a revised version, the journal will be added to the revisions table instead of journals table
	pref(1-3)	- the preferred reviewers of the submitting user
	npref(1-3)	- the non-preferred reviewers of the submitting user
	resub		- a variable indicating if the file being uploaded is a revised version or not

-->
<html>
<head>
<title>Upload</title>
<link href="styleupload.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="rectangle"></div>
<?php
	if(!isset($_POST["lgdin"]) or !isset($_POST["username"])){
	  echo '<div class="pleaseLogin"><p>Please Login</p></div>';
	  echo '<div class="buttons"><form action="index.php" method="post">
			  <input type="submit" value="Return to Login Page">
			</form>	</div>
	  ';
	  die();
	}
	$username = $_POST["username"];

	// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
	// end verify


	// add preferences
	
	$cancelUpload = 0;
	$pref1 = false;
	$pref2 = false;
	$pref3 = false;
	$npref1 = false;
	$npref2 = false;
	$npref3 = false;
	if(!isset($_POST["resub"])){
		if($_POST["pref1"] != ""){
			$pref1 = $_POST["pref1"];
			$query = "SELECT * FROM users WHERE userName = '$pref1'";
			$result = mysqli_query($con,$query);
			$row = mysqli_fetch_array($result);
			if(!$row){
				echo "<p>Preference 1, $pref1, is an incorrect username</p>";
				$cancelUpload = 1;
			}
			elseif($row['type'] != 2){
				echo "<p>Preference 1, $pref1, is not a reviewer</p>";
				$cancelUpload = 1;
			}
		}
		if($_POST["pref2"] != "" && $_POST["pref2"] != $_POST["pref1"]){
			$pref2 = $_POST["pref2"];
			$query = "SELECT * FROM users WHERE userName = '$pref2'";
			$result = mysqli_query($con,$query);
			$row = mysqli_fetch_array($result);
			if(!$row){
				echo "<p>Preference 2, $pref2, is an incorrect username</p>";
				$cancelUpload = 1;
			}
			elseif($row['type'] != 2){
				echo "<p>Preference 2, $pref2, is not a reviewer</p>";
				$cancelUpload = 1;
			}
		}
		if($_POST["pref3"] != "" && $_POST["pref3"] != $_POST["pref1"] && $_POST["pref3"] != $_POST["pref2"]){
			$pref3 = $_POST["pref3"];
			$query = "SELECT * FROM users WHERE userName = '$pref3'";
			$result = mysqli_query($con,$query);
			$row = mysqli_fetch_array($result);
			if(!$row){
				echo "<p>Preference 3, $pref3, is an incorrect username</p>";
				$cancelUpload = 1;
			}
			elseif($row['type'] != 2){
				echo "<p>Preference 3, $pref3, is not a reviewer</p>";
				$cancelUpload = 1;
			}
		}
		if($_POST["npref1"] != "" && $_POST["npref1"] != $_POST["pref1"] && $_POST["npref1"] != $_POST["pref2"] && $_POST["npref1"] != $_POST["pref3"]){
			$npref1 = $_POST["npref1"];
			$query = "SELECT * FROM users WHERE userName = '$npref1'";
			$result = mysqli_query($con,$query);
			$row = mysqli_fetch_array($result);
			if(!$row){
				echo "<p>Non-Preference 1, $npref1, is an incorrect username</p>";
				$cancelUpload = 1;
			}
			elseif($row['type'] != 2){
				echo "<p>Non-Preference 1, $npref1, is not a reviewer</p>";
				$cancelUpload = 1;
			}
		}
		if($_POST["npref2"] != "" && $_POST["npref2"] != $_POST["pref1"] && $_POST["npref2"] != $_POST["pref2"] && $_POST["npref2"] != $_POST["pref3"] && $_POST["npref2"] != $_POST["npref1"]){
			$npref2 = $_POST["npref2"];
			$query = "SELECT * FROM users WHERE userName = '$npref2'";
			$result = mysqli_query($con,$query);
			$row = mysqli_fetch_array($result);
			if(!$row){
				echo "<p>Non-Preference 2, $npref2, is an incorrect username</p>";
				$cancelUpload = 1;
			}
			elseif($row['type'] != 2){
				echo "<p>Non-Preference 2, $npref2, is not a reviewer</p>";
				$cancelUpload = 1;
			}
		}
		if($_POST["npref3"] != "" && $_POST["npref3"] != $_POST["pref1"] && $_POST["npref3"] != $_POST["pref2"] && $_POST["npref3"] != $_POST["pref3"] && $_POST["npref3"] != $_POST["npref1"] && $_POST["npref3"] != $_POST["npref2"]){
			$npref3 = $_POST["npref3"];
			$query = "SELECT * FROM users WHERE userName = '$npref3'";
			$result = mysqli_query($con,$query);
			$row = mysqli_fetch_array($result);
			if(!$row){
				echo "<p>Non-Preference 3, $npref3, is an incorrect username</p>";
				$cancelUpload = 1;
			}
			elseif($row['type'] != 2){
				echo "<p>Non-Preference 3, $npref3, is not a reviewer</p>";
				$cancelUpload = 1;
			}
		}
	}
	
	// preferences added
	
	
	if(!$cancelUpload){
		$target_dir = "journals\\";
		$target_file = basename($_FILES["fileToUpload"]["name"]);
		$target = $target_dir . $target_file;
		$uploadOk = 1;
		$fileType = strtolower(pathinfo($target,PATHINFO_EXTENSION));
		// Check if file is an actual pdf
		$check = filesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			echo '<div class="errorMsg"><p>File is not a PDF.</p></div>';
			$uploadOk = 0;
		}
		
		$query1 = "";
		$query2 = "";
		// for resubmission of revisions
		if(!isset($_POST["resub"])){
			// Check if file already exists
			if(file_exists($target)) {
				echo '<div class="fileExists"><p>Sorry, file already exists.</p></div>';
				$uploadOk = 0;
			}
		}else{
			$fname = $_POST["fname"];
			$query = "SELECT * FROM journals WHERE name = '$fname'";
			$result = mysqli_query($con,$query);
			$row = mysqli_fetch_array($result);
			$rev = $row['version'] + 1;

			//update filename if the same
			if(file_exists($target)) {
				$target_file = pathinfo($fname)['filename']."($rev).".pathinfo($target)['extension'];
			}

			// update revision number
			$query1 = "UPDATE journals SET version = version + 1, status = 1 WHERE name = '$fname'";

			// record revision name
			$query2 = "INSERT INTO revisions VALUES ('$fname','$target_file','$rev',NOW())";
			$target = $target_dir."revisions\\".$target_file;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
			echo '<div class="fileTooLarge"><p>Sorry, your file is too large.</p></div>';
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($fileType != "pdf") {
			echo '<div class="pdfOnly"><p>Sorry, only PDF files are allowed.</p></div>';
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo '<div class="fileNotUploaded"><p>Sorry, your file was not uploaded.</p></div>';
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target)) {
				if(isset($_POST["resub"])){
					mysqli_query($con,$query1);
					mysqli_query($con,$query2);
				}
				
				// --------------------------------------------successful file upload-----------------------------


				echo '<div class="successfulUpload">The file '.basename( $_FILES["fileToUpload"]["name"]).' has been uploaded.</div>';
				

				// ------------------------------------------------------------------------------------------------
				// insert revisions into journal table
				// add if necessary
				if(!isset($_POST["resub"])){
					$target = $target_dir . "\\" . $target_file;
 					$query = "INSERT INTO journals VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','$username','$target',0,0,NOW(),0)";
					mysqli_query($con, $query);
					if($pref1){
						$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','$pref1',1)";
						mysqli_query($con,$query);
					}
					if($pref2){
						$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','$pref2',1)";
						mysqli_query($con,$query);
					}
					if($pref3){
						$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','$pref3',1)";
						mysqli_query($con,$query);
					}
					if($npref1){
						$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','$npref1',0)";
						mysqli_query($con,$query);
					}
					if($npref2){
						$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','$npref2',0)";
						mysqli_query($con,$query);
					}
					if($npref3){
						$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','$npref3',0)";
						mysqli_query($con,$query);
					}
				}
			}else{
				echo '<div class="errorMsg"><p>Sorry, there was an error uploading your file.</p></div>';
			}
		}
	}else{
		echo '<div class="fileNotUploaded"><p>Your file was not uploaded</p></div>';
	}

	mysqli_close($con);
?>
<div class="buttons">
<div class="returnMenuButton" id="button">
<form action="login.php" method="post">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
	<input type="hidden" name="lgdin" value=1>			
	<input type="submit" value="Return to Main Menu">
</form>
</div>
<div class="logoutButton" id="button">
<form action="index.php" method="post">
	<input type="submit" value="Logout">
</form>	
</div>
</div>

</body>
</html>
