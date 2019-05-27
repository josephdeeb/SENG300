<html>
<body>

<h1>Login Page</h1>

<?php
	if(!isset($_POST["lgdin"])){
		if(!isset($_POST["username"]) or !isset($_POST["password"])){
		  echo "<p>Please Login</p>";
		  echo '<form action="..\index.php" method="post">
				  <input type="submit" value="Return to Login Page">
				</form>	
		  ';
		  die();
		}
	}
	$username = $_POST["username"];

	// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}

	if(!isset($_POST["lgdin"])){
		$password = $_POST["password"];
		$query = "SELECT password FROM users WHERE userName = '$username'";
		$pw = mysqli_query($con, $query);
		if(!$pw){
		  echo "<p>Please Login</p>";
		  echo '<form action="..\index.php" method="post">
				  <input type="submit" value="Return to Login Page">
				</form>	
		  ';
		  die();
		}else{
		  $row = $pw->fetch_assoc();
		  //echo "pw: ". $row['password']. " password: ". $password. "<br>";
		}

		if(strcmp($row['password'], $password) == 0){
		  //echo "login successful<br>";
		}else{
		  echo "<p>login failed </p>";
		  echo '<form action="..\index.php" method="post">
				  <input type="submit" value="Return to Login Page">
				</form>	
		  ';
		  die();
		}
	}

	// user logged in


	$query = "SELECT * FROM users WHERE userName = '$username'";
	$sql = mysqli_query($con,$query);
	$type = $sql->fetch_assoc()['type'];
	if($type == 1){
		echo "<h1>Submitter</h1>";
	}else if($type == 2){
		echo "<h1>Reviewer</h1>";
	}else{
		echo "<h1>Editor</h1>";
	}


	//
	//		REVIEWER
	//	
	
	
	if($type == 2){
	  // give reviewer options
	  echo '
		<p>Journals to Review</p>
		<form action="review.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="submit" value="View">
		</form>	
	  ';
	}
	
	
	//
	//		SUBMITTER
	//
	
	
	if($type == 1 or $type == 2){
			// submit button
	  echo '
		<form action="submit.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>
			<input type="submit" value="Submit Journal">
		</form>	
	  ';
			// display journals
		if(isset($_POST["sortRow"])){
			$sortRow = $_POST["sortRow"];
			$sort = 1;
		}else{
			$sort = 0;
			$sortRow = 0;
		}

		$query = "SELECT * FROM journals WHERE submitter = '{$username}'";

		if($sort = 1){
			if($sortRow == 0){
				$query = $query." ORDER BY name";
			}else if($sortRow == 1){	
				$query.=" ORDER BY status";
			}
		}

		$result = mysqli_query($con, $query);
		// print journals
		if($result){
			echo '<p>Submitted Journals</p>';
			echo '<table>
					<tr>
					<th>
						<div id="sortButton">
						<form action="login.php" method="post">
							<input type="hidden" name="username" value='.$username.'>
							<input type="hidden" name="sortRow" value=0>
							<input type="hidden" name="lgdin" value=1>			
							<input type="submit" value="Journal Name">
						</form>
						</div>
					</th>
					<th>
						<div id="sortButton">
						<form action="login.php" method="post">
							<input type="hidden" name="username" value='.$username.'>
							<input type="hidden" name="sortRow" value=1>
							<input type="hidden" name="lgdin" value=1>			
							<input type="submit" value="Status">
						</form>
						</div>
					</th>
					<th>
					</th>
					</tr>';
			while ($row = mysqli_fetch_array($result)) {
				if($row['status'] == 0){
					$status = 'Pending Review';
				}else if($row['status'] == 1){
					$status = 'Reviewed';
				}else if($row['status'] == 2){
					$status = 'Major Revisions Required';
				}else if ($row['status'] == 3){
					$status = 'Minor Revisions Required';
				}else if ($row['status'] == 4){
					$status = 'Accepted';
				}else {
					$status = 'Rejected';
				}
				echo  '<tr>
					    <td>'.$row["name"].'</td>
					    <td>'.$status.'</td>
					    <td>
							<div id="button">
							<form action="viewComs.php" method="post">
								<input type="hidden" name="username" value='.$username.'>
								<input type="hidden" name="lgdin" value=1>		
								<input type="hidden" name="fname" value='.$row["name"].'>
								<input type="submit" value="View Comments">
							</form>
							</div>								
						 </td> 
				  </tr>';
			}
			echo '</table>';
		}
	}
		
		
	
	//
	//		Editor
	//
	
	
	
	if($type == 3){

	  // give editor options
	  echo '
		<form action="sumbitted.php" method="post">
		  <input type="submit" value="View Submitted Journals">
		</form>
		<form action="complete.php" method="post">
		  <input type="submit" value="View Completed Reviews">
		</form>
		<form action="view.php" method="post">
		  <input type="submit" value="View All Journals">
		</form>
		';
	}
	mysqli_close($con);
?>
<form action="..\index.php" method="post">
	<input type="submit" value="Logout">
</form>	

</body>
</html>