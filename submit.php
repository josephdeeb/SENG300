<html>
<body>
<head>
<h1>Submit Journal Page</h1>
<?php
	if(!isset($_POST["username"]) or !isset($_POST["lgdin"])){
	  echo "<p>Please Login</p>";
	  echo '<form action="index.php" method="post">
			  <input type="submit" value="Return to Login Page">
			</form>	
	  ';
	  die();
	}
	$username = $_POST["username"];
	$lgdin = $_POST["lgdin"];
	
	// submit journal
	echo '  <form action="upload.php" method="post" enctype="multipart/form-data">
				Select Journal to upload:
				<input type="file" name="fileToUpload" id="fileToUpload" required	><br>
				Preferred Reviewer 1: <input type="text" name="pref1"><br>
				Preferred Reviewer 2: <input type="text" name="pref2"><br>
				Preferred Reviewer 3: <input type="text" name="pref3"><br>
				Non-Preferred Reviewer 1: <input type="text" name="npref1"><br>
				Non-Preferred Reviewer 2: <input type="text" name="npref2"><br>
				Non-Preferred Reviewer 3: <input type="text" name="npref3"><br>
				<input type="hidden" name="username" value ='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="submit" value="Upload Journal" name="submit">
			</form>
		 ';

?>

<div id="button">
<form action="login.php" method="post">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
    <input type="hidden" name="lgdin" value=1>
	<input type="submit" value="Return to Main Menu">
</form>
</div>
<div id="button">
<form action="../index.php" method="post">
	<input type="submit" value="Logout">
</form>	
</div>


</body>
</html>