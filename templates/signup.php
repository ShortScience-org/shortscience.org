<?php require("header.php");?>
<?php require("menu.php");?>
<?php die('Signups are disabled')?>
<style>

body{
background: url(res/trinity_college_dublin.jpg) no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
</style>
<center>

<div class="container">


<div class="col-md-4"></div>
<div class="well col-md-4" style="height:auto;margin-top:80px;">
<form id="form1" class="" method="post" action="./signup" autocomplete="off">
	<h3 class="form-signin-heading text-muted">Create Account</h3>
	
	<?php if ($signupresult->message != "") {?>
	<div class="alert alert-danger">
	<?=$signupresult->message?>
	</div>
	<?php }?>
	
	<?php if ($signupresult->success) {?>
	<div class="alert alert-info">
	<span style="font-size:150px;" class="glyphicon glyphicon-envelope" aria-hidden="true"></span><br>
	
	An email is on it's way! Click the link inside to log in!
	</div>
	<?php }else{?>
	
		<input style="display:none" type="text" name="fakeusernameremembered"/>
		<input style="display:none" type="password" name="fakepasswordremembered"/>
	
		<small>E-Mail</small>
		<input name="email" id="email" type="text" class="form-control" placeholder="E-Mail" value="<?=$signup->email?>" required="">

		<small>Display Name</small>
		<input name="displayname" id="displayname" type="text" class="form-control" placeholder="Display Name" value="<?=$signup->displayname?>" required="">

		<small>Username</small>
		<input name="username" id="username" type="text" class="form-control" placeholder="Username" value="<?=$signup->username?>" required="">
		
		<small>Password</small>
		<input name="password" id="password" type="password" class="form-control" placeholder="Password" required="" value="<?=$signup->password?>">
		<br>
		<button class="btn btn-lg btn-primary btn-block" type="submit">
			Sign Up
		</button>
		<br>
		<meta name="google-signin-scope" content="profile email">
		<meta name="google-signin-client_id" content="1029373175845-ssht9fr1nsdq6b20dm5icum8td8f5i2n.apps.googleusercontent.com">
		<script src="https://apis.google.com/js/platform.js" async defer></script>
		<div id="google-signin" class="g-signin2" data-onsuccess="onGoogleSignIn" data-theme="dark"></div>
		
		<br>
		<div>
		The email address must be valid. You will receive a confirmation email which you need to respond to. The email address won't be publicly shown anywhere.
		</div>
	<?php }?>
</form>
</div>

</div>
</center>

<script>

function onGoogleSignIn(googleUser) {

	
	var profile = googleUser.getBasicProfile();
	var id_token = googleUser.getAuthResponse().id_token;

	$("#username").val(profile.getName().replace(/ /g,"").toLowerCase());
	$("#email").val(profile.getEmail());
  	$("#password").val(id_token.substring(0,40));
  	$("#displayname").val(profile.getName());

	var auth2 = gapi.auth2.getAuthInstance();
	auth2.signOut().then(function () {
	  console.log('User signed out.');
	});

	$("#form1").submit();
};

</script>

<?php //require("footer.php");?>
