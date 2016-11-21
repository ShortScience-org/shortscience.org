<?php require("header.php");?>
<?php require("menu.php");?>


<div class="container">
<center>
<br>
<img src="./res/bil-nye-error.jpg"/>
</center>
<br><br>
<div class="col-md-2"></div>
<div class="col-md-8">
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  Error: <?=$message?>

</div>
</div>
</div>

<?php require("footer.php");?>
<?php die();?>