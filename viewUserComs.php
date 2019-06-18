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

-->
<html>
<head>
<title>Comments</title>
<link href="styleviewComs.css" type="text/css" rel="stylesheet" />
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
	
	echo '
<h2>Comments you\'ve made on '. $fname. '</h2>
<div class="view">';

	// add comment to db
	if(isset($_POST["comment"]) and $_POST["comment"] != ""){
		$comment = $_POST["comment"];
		
		$query = "INSERT INTO comments VALUES ('$fname','$username','$comment')";
		if(mysqli_query($con,$query)){
			echo ' <div class="content"> <div class="answer">
	<p>Comment Added Successfully.</p> </div> </div>';
		}else{
			echo ' <div class="content"> <div class="answer">
	<p>Error during adding comment. Please try again.</p> </div> </div>';
		}
	}


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
		echo '<div class="content"> <div class="comments"><p>No comments have been made for this Journal</p> </div> </div>';
	}else{
		$num = 1;
		// print comments
		echo ' <div class="content">
	<table class="comments">
		<tr>
			<th>Number</th>
			<th>Comment</th>
		</tr>';
			while ($row = mysqli_fetch_array($result)) {
				echo  "
		<tr>
		    <td>".$num."</td>
			<td>".$row['comment']."</td>
		</tr>";
				$num = $num + 1;
			}
			echo '
	</table> </div>';
	}

	echo '	<div class="content">
	<div class="addCommentbutton">
		<form action="viewUserComs.php" method="post">
			<div class="pad">
				Add Comments to '.$fname.'
				<input type="text" name="comment">
			</div>
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>
			<input type="hidden" name="fname" value='.$fname.'>
			<input type="submit" value="Add Comment to Journal">
		</form>
	</div> </div>';
	
	$query = "SELECT * FROM reviewers WHERE reviewer = '$username' and journalName = '$fname'";
	$result = mysqli_query($con,$query);
	$row = mysqli_fetch_array($result);
	if($row["decision"] == 0){
		echo ' <div class="content"> <div class="complete">
	<p>Have you completed your review?</p>
	<div class="acceptButton">
		<form action="review.php" method="post" onsubmit="return accept()">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>
			<input type="hidden" name="fname" value='.$fname.'>
			<input type="hidden" name="review" value=1>
			<input type="submit" value="Accept">
		</form>
	</div>
	<div class="rejectButton">
		<form action="review.php" method="post" onsubmit="return reject()">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>
			<input type="hidden" name="fname" value='.$fname.'>
			<input type="hidden" name="review" value=2>
			<input type="submit" value="Reject">
		</form>
	</div> </div> </div>';
	}
	// Close the mysql connectiion
    mysqli_close($con);
?>
<div class="buttons">
	<div class="returnButton">
		<form action="review.php" method="post">
			<input type="hidden" name="username" value="<?php echo $username; ?>">
			<input type="hidden" name="lgdin" value=1>
			<input type="submit" value="Return to Journals to Review Page">
		</form>
	</div>
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
