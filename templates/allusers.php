<?//print_r($userss);?>
<?php require("header.php");?>
<?php require("menu.php");?>
<?php global $SCRIPT_VERSION ?>
<?php global $MATHJAX_URL?>

<br>
<div class="container" style="max-width:700px; margin:0 auto;">

<?php for ($i = 0; $i < sizeof($users); $i++) { 
$user = $users[$i];
//print_r($user);
?>

	<div class="col-sm-4">
	<div class="alert" style="background-color:rgb(250,250,250);min-height:175px">
	<center>
	
	<h3 style='margin:0px;padding-left:10px;' >
	
	<a href="user?name=<?=$user->username?>">
	<?=get_gravatar($user->email,50,"identicon","g",true,["style"=> "border-radius: 50%;height:50px;" ])?></a>
	
	<br>
	
	<a href="user?name=<?=$user->username?>">
	<span style="font-size:12pt"><?=($user->displayname == "")?$user->username:htmlspecialchars($user->displayname)?></span>
	</a>
	
	</h3>
	
	<?php if ($user->numOfVignettes == 1){ ?>
	<span class="label label-success"><?=$user->numOfVignettes?> Public summary</span>
	<?php } else if ($user->numOfVignettes > 1){?>
	<span class="label label-success"><?=$user->numOfVignettes?> Public summaries</span>
	<?php }?>
	
	<span class="label label-info"> SciScore: <?=getUserVignettePoints($user->userid)?></span>
	
	
	</center>
	</div>
	</div>





	
<?php }?>




</div>
</div>


<script src="./res/marked/marked.min.js"></script>
<script src="./res/js/standard.js?v=<?=$SCRIPT_VERSION?>"></script>

<script type="text/x-mathjax-config">
MathJax.Hub.Config({
  tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
});
</script>
<script type="text/javascript" async src="<?=$MATHJAX_URL?>"></script>


<?php require("footer.php");?>