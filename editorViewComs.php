<!--

viewUserComs.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This page is for reviewers to view the comments that they have made on the selected journal.
Reviewers are also given a text box to submit new comments on the journal as well as buttons to accept or reject the journal.

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu
	fname		- the filename of the journal the user wishes to view the comments for
	comment		- the comment submitted by the reviewer
	sortByCol	- posted if the user wishes to sort the table by a specified column

--->
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
	
	// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
	// end verify

	$username = $_POST["username"];
	$lgdin = $_POST["lgdin"];
	$fname = $_POST["fname"];
	$submitter = $_POST["submitter"];
	$reviewer = $_POST["reviewer"];
	
	// add comment to db
	if(isset($_POST["comment"]) and $_POST["comment"] != ""){
		$comment = $_POST["comment"];
		
		$query = "INSERT INTO comments VALUES ('$fname','$username','$comment')";
		if(mysqli_query($con,$query)){
			echo "<p>Comment Added Successfully.</p>";
		}else{
			echo "<p>Error during adding comment. Please try again.</p>";
		}
	}


	echo "<p>Comments you've made on ". $fname. "</p>";

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
		echo '<p>No comments have been made for this Journal</p>';
	}else{
		$num = 1;
		// print comments
		echo '
		<table>
				<tr>
				<th>Number</th>
				<th>Comment</th>
				</tr>';
			while ($row = mysqli_fetch_array($result)) {
				echo  "<tr>".
					  "  <td>".$num."</td>" .
					  "  <td>".$row['comment']."</td>" .
				  "</tr>";
				$num = $num + 1;
			}
			echo "
			</table>";
	}

	echo "<p>Add Comments to ". $fname. "</p>";
	echo '	
		<div id="button">
		<form action="editorViewComs.php" method="post">
			<input type="text" name="comment">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>
			<input type="hidden" name="fname" value='.$fname.'>
			<input type="submit" value="Add Comment to Journal">
		</form>
		</div>
	';
	
	$query = "SELECT * FROM reviewers WHERE reviewer = '$username' and journalName = '$fname'";
	$result = mysqli_query($con,$query);
	$row = mysqli_fetch_array($result);
	if($row["decision"] == 0){
		echo '
			<p>Have you completed your review?</p>
			<div id="button">
			<form action="editorViewComs.php" method="post" onsubmit="return accept()">
				<input type="hidden" name="username" value='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="hidden" name="fname" value='.$fname.'>
				<input type="hidden" name="review" value=1>
				<input type="submit" value="Accept">
			</form>
			</div>
			<div id="button">
			<form action="editorViewComs.php" method="post" onsubmit="return reject()">
				<input type="hidden" name="username" value='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="hidden" name="fname" value='.$fname.'>
				<input type="hidden" name="review" value=2>
				<input type="submit" value="Reject">
			</form>
			</div>
		';
	}
	if($reviewer != ""){
		$submitter = "";
	}
	// Close the mysql connectiion
    mysqli_close($con);
?>


<div id="button">
<form action="complete.php" method="post">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
	<input type="hidden" name="submitter" value="<?php echo $submitter; ?>">
	<input type="hidden" name="reviewer" value="<?php echo $reviewer; ?>">
    <input type="hidden" name="lgdin" value=1>
	<input type="submit" value="Return to Journals to Completed Journals Page">
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

<script>
function accept() {
	if(confirm("Are you sure you want to accept this Journal?")) {
		return true;
	}else{
		return false;
	}
}
function reject() {
	if(confirm("Are you sure you want to reject this Journal?")){
		return true;
	}else{
		return false;
	}
}
</script>
</body>
</html>