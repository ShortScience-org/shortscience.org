<?php //print_r($venues);?>
<?php require("header.php");?>
<?php require("menu.php");?>
<?php global $SCRIPT_VERSION ?>
<?php global $MATHJAX_URL?>

<br>
<div class="container" style="max-width:700px; margin:0 auto;">


<?php for ($i = 0; $i < sizeof($venues); $i++) { 
$venue = $venues[$i];
//print_r($venue);
?>

	<div class="alert" style="background-color:rgb(250,250,250);">
	<div class="row">
	<div class="col-xl-8">
	<h3 style='margin:0px;padding-left:10px;' >
	
	<?php if ($venue->imgurl != ""){?>
	<a href="<?=$venue->url?>"><img style="width:60px;" src="<?=$venue->imgurl?>"/></a>
	<?php }else{?>
	<span style="font-size:2.5em;float:left; padding-top:0px;padding-right:10px;" class="glyphicon glyphicon-book"></span>
	<?php } ?>
	
	<?php if ($venue->name != ""){?>
	<a href="?key=<?=$venue->id?>"><?=$venue->name?></a>
	<?php } else {?>
	<a href="?key=<?=$venue->id?>"><?=$venue->id?></a>
	<?php }?>
	
	</h3>
	</div>
	
	<div class="col-xl-4" style="text-align: right">
	
	</div>
	</div>
	
	<?php if ($venue->numOfVignettes == 1){ ?>
	<span class="label label-success"><?=$venue->numOfVignettes?> Public summary</span>
	<?php } else if ($venue->numOfVignettes > 1){?>
	<span class="label label-success"><?=$venue->numOfVignettes?> Public summaries</span>
	<?php }?>
	
	<br>
	<div style="text-align: left;max-height:300px;overflow:auto;"><?=$venue->description?></div>
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