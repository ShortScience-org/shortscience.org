<?//print_r($years);?>
<?php require("header.php");?>
<?php require("menu.php");?>
<?php global $SCRIPT_VERSION ?>
<?php global $MATHJAX_URL?>

<br>
<div class="container" style="max-width:700px; margin:0 auto;">

<div class="alert" style="background-color:rgb(250,250,250);">
<div class="row">
<div class="col-xl-8">
<h3 style='margin:0px;padding-left:10px;' >

<?php if ($venue->imgurl != ""){?>
<a href="<?=$venue->url?>"><img style="width:60px;" src="<?=$venue->imgurl?>"/></a>
<?php }?>

<?php if ($venue->name != ""){?>
<a href="<?=$venue->url?>"><?=$venue->name?></a>
<?php } else {?>

<?php }?>

</h3>

</div>

<div class="col-xl-4" style="text-align: right">

</div>
</div>
<br>
<div style="text-align: left;max-height:300px;overflow:auto;"><?=$venue->description?></div>
</div>


<ul class="nav nav-tabs">

<?php foreach ($years as $key => $value) { ?>

<li role="presentation" <?=($year == $key)?'class="active"':''?>><a href="?key=<?=$venue->id?>&year=<?=$key?>"><?=($key == "")?"Unknown Year":$key?> <span class="badge"><?=sizeof($value)?></span></a></li>

<?php }?>
</ul>




<?php if ($tab == ""){?>
<br>
<div id="vignettes">
<?php if (sizeof($vignettes) ==0){?>
	<center>
	<br>
	
	<div class="well">
	There are no summaries for this venue.
	</div>
	</center>
<?php }?>
<?php 
for ($i = 0; $i < sizeof($vignettes); $i++) {
$vignette = $vignettes[$i];
$paper = $vignette->paper;

$vignette->myvote = getMyVignettesVote($vignette->paperid, $vignette->userid);

if (!isset($vignette->vote)) $vignette->vote = 0;

//print_r($paperBib);
include("templates/papertitle.php");
include("templates/vignette.php");
}
?>
</div>
<?php }?>




</div>
</div>


<script src="./res/marked/marked.min.js"></script>
<script src="./res/js/jquery.taboverride.min.js"></script>
<script src="./res/js/standard.js?v=<?=$SCRIPT_VERSION?>"></script>

<script type="text/x-mathjax-config">
MathJax.Hub.Config({
  tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
});
</script>
<script type="text/javascript" async src="<?=$MATHJAX_URL?>"></script>


<?php require("footer.php");?>