<?php require("header.php");?>
<?php require("menu.php");?>

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
<form id="form1" class="" method="post" action="./login" >
	<h3 class="form-signin-heading text-muted">Sign In</h3>
			
	<?php if ($loginresult->message != "") {?>
	<div class="alert alert-danger">
	<?=$loginresult->message?>
	</div>
	<?php }?>	

	<small>Username or E-Mail</small>
	<input name="loginname" id="loginname" type="text" class="form-control" placeholder="Username or E-Mail" value="<?=$login->loginname?>" required="">
	<small>Password</small>
	<input name="password" id="password" type="password" class="form-control" placeholder="Password" value="<?=$login->password?>" required="" >
	<br>
	<button class="btn btn-lg btn-primary btn-block" type="submit">
		Log in
	</button>
	
</form>

<p>Forgot your password? <a href="./recover">Recover it here</a>. <br><br>


<meta name="google-signin-scope" content="profile email">
<meta name="google-signin-client_id" content="1029373175845-ssht9fr1nsdq6b20dm5icum8td8f5i2n.apps.googleusercontent.com">
<script src="https://apis.google.com/js/platform.js?onload=onLoadCallback" async defer></script>
<div id="google-signin" class="g-signin2" id="signin-button" data-onsuccess="onGoogleSignIn" data-theme="dark"></div>

<Br>
<?php 
///<a href="./signup"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create Account</a></p>
?>
</div>

</div>
</center>

<script>

var clicked = false;
$("#google-signin").click(function(){
	clicked = true;
});

function onGoogleSignIn(googleUser) {
	
	if (!clicked){
		var auth2 = gapi.auth2.getAuthInstance();
		auth2.signOut().then(function () {
		  console.log('User signed out.');
		});
		return;
	}

	
	var profile = googleUser.getBasicProfile();
	var id_token = googleUser.getAuthResponse().id_token;
	
	$("#loginname").val(profile.getEmail());
	$("#password").val(id_token.substring(0,40));

	$("#form1").submit();
};



</script>

<?php //require("footer.php");?>
