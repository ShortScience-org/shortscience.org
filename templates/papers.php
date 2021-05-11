<?php require("header.php");?>
<?php require("menu.php");?>
<?php global $MATHJAX_URL?>
<?php //print_r($results);die();?>
<div class="container">

<div class="col-md-1"></div>
<div class="col-md-10">
<br>
	<form class="" role="search" action="search" style="border: none;width:100%;">
	<div class="input-group" style="width:100%;">
	<input type="text" name="term"  class="form-control" placeholder="Search for a paper..." value="<?=htmlspecialchars($term)?>" >
		<span class="input-group-btn" style="width:1px;">
 			<input class="btn btn-default" type="submit" style="" value="Go!"/>
		</span>
	</div>
	</form>
<br>
<div class="searchresults">

<?php if ($term == ""){?>

<div class="col-md-2"></div>
<div class="col-md-8">
<div class="alert alert-info" role="alert">
  <span class="sr-only">Error:</span>
  <center>Search for a paper by title, arXiv ID,  or doi to write a summary</center>
</div>
</div>

<?php } else if (sizeof($results) == 0){?>
<center>
<br>
<img src="res/ndt.jpg"/>
</center>
<br><br>
<div class="col-md-2"></div>
<div class="col-md-8">
<div class="alert alert-danger" role="alert">
<center>
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  Error: No papers found with that search term
  </center>
</div>
</div>
<?php }?>
<?php 
if ($results){
    for ($i = 0; $i < sizeof($results); $i++) {
        $paper = $results[$i];
        include("papersearchresult.php");
        ?>
        <br>	
        <?php     }
}
?>
</div>
</div>
</div>
<script type="text/x-mathjax-config">
MathJax.Hub.Config({
  tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
});
</script>
<script type="text/javascript" async src="<?=$MATHJAX_URL?>"></script>



<?php require("footer.php");?>