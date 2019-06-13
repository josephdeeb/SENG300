<!--
regFront.php
----------------------------------------------------------------------------------------------------------------------------------------------------
This is the User Registration Page.
Displays text boxes and buttons for users to input their information.
This page does live username checking to verify input username is not already in use.
This page also notifies users is their two input passwords are different.
----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	no required inputs
-->

<html>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
function checkAvailability() {
	$("#loaderIcon").show();
	jQuery.ajax({
	url: "check_availability.php",
	data:'username='+$("#username").val(),
	type: "POST",
	success:function(data){
		$("#user-availability-status").html(data);
		$("#loaderIcon").hide();
	},
	error:function (){}
	});
}
</script>
<script>
$(document).ready(function(){
    $("#password2").keyup(function(){
        if ($("#password1").val() != $("#password2").val()) {
            $("#msg").html("Password do not match").css("color","red");
        }else{
            $("#msg").html("Password matched").css("color","green");
        }
	});
});
</script> 
<script>
function validate() {
    var password1 = $("#password1").val();
    var password2 = $("#password2").val();
    if(password1 == password2) {
         $("#validate-status").text("Passwords Match!");
         $('#submit-button').prop('disabled', false);
    }
    else {
         $("#validate-status").text("Passwords Do Not Match!");  
         $('#submit-button').prop('disabled', true);
    }
}
    
$("#password2").keyup(validate);
</script>
<head>
<title>Register</title>
<link href="stylereg.css" type="text/css" rel="stylesheet" />
</head>

<body>
<div class="rectangle"></div>
<h1>New Customer Registration</h1>

<form action="regBack.php" method="post">
	<div class="msg">
		<h2>Want to have your journal reviewed?<br></h2>
		<h2>Register Here<br><hr width="400px" align="left"/><br></h2>
	</div>
	<div class="checkUsername" id="frmCheckUsername">
	    <label>Check Username:<br></label>
		<input name="username" type="text" id="username" class="demoInputBox" onBlur="checkAvailability()" required>*<span id="user-availability-status"></span>    
	</div>
	<p><img src="LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>

	<div class="col-sm-4">
		<div class="form-group"><label>Password (minimum 8 characters):<br></label>
			<input type="password"   id="password1"   name="password" pattern=".{8,}" class="form-control input-sm" required/>
		</div>
	</div>
																  
	<div class="col-sm-44"> <!-- used to be <div class="col-sm-4"> -->
		<div class="form-group"><label>Confirm Password:<br></label>
			<input type="password"   id="password2"   name="password2"   class="form-control input-sm" required/>
		</div>
		<div id="msg"></div>
	</div>
	<div class="names">
		<div class="firstName">
			Enter First Name:<br>
			<input type="text" name="fname" required>*<br>
		</div>
		<div class="lastName">
			Enter Last Name:<br>
			<input type="text" name="lname" required>*<br>
		</div>
	</div>
	<div class="chooseUser">
<<<<<<< Updated upstream
		Choose What Type of User You Are:<br>
		<input type="radio" name="type" value=1 required> Submitter<br>
		<input type="radio" name="type" value=2> Reviewer<br>
		<input type="reset">
		<input id="submit-button" type="submit" value="Submit">
=======
	Choose What Type of User You Are:<br>
    <input type="radio" name="type" value=1 required> Author<br>
    <input type="radio" name="type" value=2> Reviewer<br>
    <input type="reset">
    <input id="submit-button" type="submit" value="Submit">
>>>>>>> Stashed changes
	</div>

</form>
<form action="..\index.php" method="post">
	<div class="returnLoginButton">
		<input type="submit" value="Return to Login Page">
	</div>
</form>	
</body>
</html>
