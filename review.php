<html>
<body>

<?php
    if (!isset($_POST["lgdin"]) or !isset($_POST["username"])) {
        echo "<p>Please Login</p>";
        echo '<form action="..\index.php" method="post">
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
    
    $query = "SELECT * FROM journals, reviewers WHERE journalName = name AND reviewer = '$username'";
    
    if (isset($_POST["sortRow"])) {
        $sortRow = $_POST["sortRow"];
        if ($sortRow == 2) {
            $sort = 2;
        } else {
            $sort = 1;
        }
    } else {
        $sort = 0;
        $sortRow = 0;
    }
    
    if ($sort > 0) {
        if ($sortRow == 0) {
            $query = $query." ORDER BY journalName";
        } else if ($sortRow == 1) {
            $query = $query." ORDER BY submitter";
        } else if ($sortRow == 2) {
            $query = $query." ORDER BY submissionDateTime";
        }
    }
    
    $result = mysqli_query($con, $query);
    
    if ($result) {
        echo '<p>Journals</p>';
        echo '<table>
                <tr>
                <th>
                    <div id="sortButton">
                    <form action="review.php" method="post">
                        <input type="hidden" name="username" value='.$username.'>
                        <input type="hidden" name="sortRow" value=0>
                        <input type="hidden" name="lgdin" value=1>			
                        <input type="submit" value="Journal Name">
                    </form>
                    </div>
                </th>
                <th>
                    <div id="sortButton">
                    <form action="review.php" method="post">
                        <input type="hidden" name="username" value='.$username.'>
                        <input type="hidden" name="sortRow" value=1>
                        <input type="hidden" name="lgdin" value=1>			
                        <input type="submit" value="Submitter">
                    </form>
                    </div>
                </th>
                <th>
                    <div id="sortButton">
                    <form action="review.php" method="post">
                        <input type="hidden" name="username" value='.$username.'>
                        <input type="hidden" name="sortRow" value=2>
                        <input type="hidden" name="lgdin" value=1>
                        <input type="submit" value="Submission Time">
                    </form>
                    </div>
                </th>
                <th>
                </th>
                </tr>';
        
        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>
                    <td>'.$row["journalName"].'</td>
                    <td>'.$row["submitter"].'</td>
                    <td>'.$row["submissionDateTime"].'</td>
                    <td>
                        <div id="button">
                        <form action="editComs.php" method="post">
                            <input type="hidden" name="username" value='.$username.'>
                            <input type="hidden" name=lgdin" value=1>
                            <input type="hidden" name="fname" value='.$row["journalName"].'>
                            <input type="submit" value="Submit Comments">
                        </form>
                        </div>
                    </td>
                </tr>';
        }
        echo '</table>';
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