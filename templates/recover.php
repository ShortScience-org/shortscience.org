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
<form class="" method="post" action="./recover" >
	<h3 class="form-signin-heading text-muted">Recover Account</h3>
	
	<?php if ($recoverresult->message != "") {?>
	<div class="alert alert-danger">
	<?=$recoverresult->message?>
	</div>
	<?php }?>
	
	<?php if ($recoverresult->success) {?>
	<div class="alert alert-info">
	<span style="font-size:150px;" class="glyphicon glyphicon-envelope" aria-hidden="true"></span><br>
	
	If you have an account an email is on it's way! Click the link inside!
	</div>
	<?php }else{?>
	
		<input name="email" id="email" type="text" class="form-control" placeholder="E-Mail" value="<?=htmlspecialchars($recover->email)?>" required="">
		<br>
		<button class="btn btn-lg btn-primary btn-block" type="submit">
			Send Recovery Email
		</button>
		<br>
	<?php }?>
</form>
</div>

</div>
</center>


<?php //require("footer.php");?>
