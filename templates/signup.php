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
<form class="" method="post" action="./signup" autocomplete="off">
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
	
		<input name="email" id="email" type="text" class="form-control" placeholder="E-Mail" value="<?=$signup->email?>" required="">
		<input name="username" id="username" type="text" class="form-control" placeholder="Username" value="<?=$signup->username?>" required="">
		<input name="password" id="password" type="password" class="form-control" placeholder="Password" required="" value="<?=$signup->password?>">
		<br>
		<button class="btn btn-lg btn-primary btn-block" type="submit">
			Sign Up
		</button>
		<br>
		<div>
		The email address must be valid. You will receive a confirmation email which you need to respond to. The email address won't be publicly shown anywhere.
		</div>
	<?php }?>
</form>
</div>

</div>
</center>


<?php //require("footer.php");?>
