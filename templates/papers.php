<?php require("header.php");?>
<?php require("menu.php");?>
<?//print_r($results);die();?>
<div class="container">
<div class="col-md-1"></div>
<div class="col-md-10">
<br>


<a href="/internalsearch?q=<?=$term?>">
<center><div class="alert alert-info">Want to search existing summaries? Click here</div></center>
</a>

<?php if (sizeof($results) == 0){?>
<center>
<br>
<img src="res/ndt.jpg"/>
</center>
<br><br>
<div class="col-md-2"></div>
<div class="col-md-8">
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  Error: No papers found with that search term

</div>
</div>
<?php }?>
<?php 
for ($i = 0; $i < sizeof($results); $i++) {
$paper = $results[$i];
include("papersearchresult.php");
?>
<br>	
<?
}?>

</div>
</div>
<script type="text/x-mathjax-config">
MathJax.Hub.Config({
  tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
});
</script>
<script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_CHTML"></script>



<?php require("footer.php");?>