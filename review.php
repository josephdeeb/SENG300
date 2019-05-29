<html>
<body>

<?php
    if (!isset($_POST["lgdin"])) {
        if (!isset($_POST["username"]) or !isset($_POST["password"])) {
            echo "<p>Please Login</p>";
            echo '<form action="..\index.php" method="post">
                    <input type="submit" value="Return to Login Page">
                  </form>	
            ';
            die();
        }
    }
?>

</body>
</html>