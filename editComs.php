<html>
<body>

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
	$fname = $_POST["fname"];
	
	echo '<h1>Add Comments to '. $fname. '</h1>';
?>

<div id="button">
<form action="addCom.php" method="post">
    <input type="text" name="comment">
	<input type="hidden" name="username" value="<?php echo $username; ?>">
    <input type="hidden" name="lgdin" value=1>
	<input type="hidden" name="fname" value="<?php echo $fname; ?>">
	<input type="submit" value="Add Comment to Journal">
</form>
</div>
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