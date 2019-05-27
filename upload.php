<html>
<body>

<?php
	if(!isset($_POST["lgdin"]) or !isset($_POST["username"])){
	  echo "<p>Please Login</p>";
	  echo '<form action="index.php" method="post">
			  <input type="submit" value="Return to Login Page">
			</form>	
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



	$target_dir = "journals/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = filesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
//			echo "<p>File is a PDF - " . $check["mime"] . ".</p>";
			$uploadOk = 1;
		} else {
			echo "<p>File is not a PDF.</p>";
			$uploadOk = 0;
		}
	}
	// Check if file already exists
	if (file_exists($target_file)) {
		echo "<p>Sorry, file already exists.</p>";
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		echo "<p>Sorry, your file is too large.</p>";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($fileType != "pdf") {
		echo "<p>Sorry, only PDF files are allowed.</p>";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "<p>Sorry, your file was not uploaded.</p>";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			
			// successful file upload
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			$query = "INSERT INTO journals VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','".$username."','".$target_file."',0)";
			mysqli_query($con, $query);
			if(isset($_POST["pref1"])){
				$pref1 = $_POST["pref1"];
				$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','".$pref1."',"."1)";
				if(!mysqli_query($con,$query)){
					echo "\n error on pref1";
				}
			}
			if(isset($_POST["pref2"])){
				$pref2 = $_POST["pref2"];
				$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','".$pref2."',"."1)";
				mysqli_query($con,$query);
			}
			if(isset($_POST["pref3"])){
				$pref3 = $_POST["pref3"];
				$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','".$pref3."',"."1)";
				mysqli_query($con,$query);
			}
			if(isset($_POST["npref1"])){
				$npref1 = $_POST["npref1"];
				$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','".$npref1."',"."0)";
				mysqli_query($con,$query);
			}
			if(isset($_POST["npref2"])){
				$npref2 = $_POST["npref2"];
				$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','".$npref2."',"."0)";
				mysqli_query($con,$query);
			}
			if(isset($_POST["npref3"])){
				$npref3 = $_POST["npref3"];
				$query = "INSERT INTO subprefs VALUES ('".basename( $_FILES["fileToUpload"]["name"])."','".$npref3."',"."0)";
				mysqli_query($con,$query);
			}
		} else {
			echo "<p>Sorry, there was an error uploading your file.</p>";
		}
	}

	mysqli_close($con);
?>

<div id="button">
<form action="login.php" method="post">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
	<input type="hidden" name="lgdin" value=1>			
	<input type="submit" value="Return to Main Menu">
</form>
</div>
<div id="button">
<form action="index.php" method="post">
	<input type="submit" value="Logout">
</form>	
</div>


</body>
</html>