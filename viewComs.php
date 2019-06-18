<!--

viewComs.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This page is for submitters to view the comments that have been made on their journals.
Users are given the option to upload a revised version if the editor has deemed that if requires major or minor revisions.

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu
	fname		- the filename of the journal the user wishes to view the comments for
	sortByCol	- posted if the user wishes to sort the table by a specified column
	
-->
<html>
<head>
<title>Comments</title>
<link href="styleviewsubcoms.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="rectangle"></div>
<?php
	// Check if user is logged in
	if (!isset($_POST["lgdin"]) or !isset($_POST["username"])) {
        echo '
	<div class="pleaseLogin">
		<p>Please Login</p>
	</div>';
        echo '
	<div class="buttons">
		<form action="..\index.php" method="post">
            <input type="submit" value="Return to Menu">
        </form>
	</div>';
        die();
    }
	$username = $_POST["username"];
	$lgdin = $_POST["lgdin"];
	$fname = $_POST["fname"];
	
	echo '<h2>View Comments Made on '. $fname. '</h2>';

	// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
	// end verify


	// display journals
	if(isset($_POST["sortByCol"])){
		$sortByCol = $_POST["sortByCol"];
		$sort = 1;
	}else{
		$sort = 0;
		$sortByCol = 0;
	}

	$query = "SELECT * FROM comments WHERE journalName = '$fname'";
	
	if($sort = 1){
		if($sortByCol == 0){
			$query = $query." ORDER BY reviewer";
		}else if($sortByCol == 1){
			$query = $query." ORDER BY line";
		}
	}

	$result = mysqli_query($con, $query);
	if(!mysqli_num_rows($result)){
		echo '<div class="comments"><p>No comments have been made for this Journal</p></div>';
	}else{
		
		// print comments
		echo '
		<table class="comments">
				<tr>
				<th>
					<div id="sortButton">
					<form action="viewComs.php" method="post">
						<input type="hidden" name="username" value='.$username.'>
						<input type="hidden" name="lgdin" value='.$lgdin.'>
						<input type="hidden" name="fname" value='.$fname.'>
						<input type="hidden" name="sortByCol" value=0>
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
	if ($row['status'] == 2 or $row['status'] == 3) {
		echo ' 
				<form action="upload.php" method="post" enctype="multipart/form-data">
					Select Revised Journal to Upload:
					<input type="file" name="fileToUpload" id="fileToUpload" required><br>
					<input type="hidden" name="username" value ='.$username.'>
					<input type="hidden" name="lgdin" value=1>
					<input type="hidden" name="resub" value=1>
					<input type="hidden" name="fname" value='.$fname.'>
					<input type="submit" value="Upload Journal">
				</form>
			 ';
	}
	mysqli_close($con);
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
