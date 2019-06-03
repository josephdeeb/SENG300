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
	
	echo '<h1>View Comments Made on '. $fname. '</h1>';

	// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
	// end verify


	// display journals
	if(isset($_POST["sortRow"])){
		$sortRow = $_POST["sortRow"];
		$sort = 1;
	}else{
		$sort = 0;
		$sortRow = 0;
	}

	$query = "SELECT * FROM comments WHERE journalName = '$fname'";
	
	if($sort = 1){
		if($sortRow == 0){
			$query = $query." ORDER BY reviewer";
		}else if($sortRow == 1){
			$query = $query." ORDER BY line";
		}
	}

	$result = mysqli_query($con, $query);
	if(!mysqli_num_rows($result)){
		echo '<p>No comments have been made for this Journal</p>';
	}else{
		
		// print comments
		echo '
		<table>
				<tr>
				<th>
					<div id="sortButton">
					<form action="viewComs.php" method="post">
						<input type="hidden" name="username" value='.$username.'>
						<input type="hidden" name="lgdin" value='.$lgdin.'>
						<input type="hidden" name="fname" value='.$fname.'>
						<input type="hidden" name="sortRow" value=0>
						<input type="submit" value="Reviewer Name">
					</form>
					</div>
				</th>
				<th>
					<div id="sortButton">
					<form action="viewComs.php" method="post">
						<input type="hidden" name="username" value='.$username.'>
						<input type="hidden" name="lgdin" value='.$lgdin.'>
						<input type="hidden" name="fname" value='.$fname.'>
						<input type="submit" value="Comment">
					</form>
					</div>
				</th>
				</tr>';
			while ($row = mysqli_fetch_array($result)) {
				echo  "<tr>".
					  "  <td>".$row['reviewer']."</td>" .
					  "  <td>".$row['comment']."</td>" .
				  "</tr>";
			}
			echo "
			</table>";
	}
	
	$query = "SELECT * FROM journals WHERE name = '$fname '";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_array($result);
	
	
	// remove if for second iteration
	if(true){
		if ($row['status'] == 2 or $row['status'] == 3) {
			echo ' 
					<form action="upload.php" method="post" enctype="multipart/form-data">
						Select Revised Journal to Upload:
						<input type="file" name="fileToUpload" id="fileToUpload" required><br>
						<input type="hidden" name="username" value ='.$username.'>
						<input type="hidden" name="lgdin" value=1>
						<input type="hidden" name="resub" value=1>
						<input type="hidden" name="fname" value='.$fname.'>
						<input type="submit" value="Upload Journal" name="submit">
					</form>
				 ';
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
<form action="../index.php" method="post">
	<input type="submit" value="Logout">
</form>	
</div>

</body>
</html>