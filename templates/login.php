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
<form class="" method="post" action="./login" >
	<h3 class="form-signin-heading text-muted">Sign In</h3>
			
	<?php if ($loginresult->message != "") {?>
	<div class="alert alert-danger">
	<?=$loginresult->message?>
	</div>
	<?php }?>	
			
	<input name="loginname" id="loginname" type="text" class="form-control" placeholder="Username or E-Mail" value="<?=$login->loginname?>" required="">
	<input name="password" id="password" type="password" class="form-control" placeholder="Password" value="<?=$login->password?>" required="" >
	<button class="btn btn-lg btn-primary btn-block" type="submit">
		Log in
	</button>
	
</form>

<p>Forgot your password? <a href="./recover">Recover it here</a>. <br><br><a href="./signup"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create Account</a></p>
</div>

</div>
</center>


<?php //require("footer.php");?>
