<?php require("header.php");?>
<?php require("menu.php");?>


<div class="row">
<div class="col-sm-2"></div>
<div class="col-sm-8" style="max-width:800px">
<br>
<?php 
for ($i = 0; $i < sizeof($vignettes); $i++) {
$vignette = $vignettes[$i];
$paperBib = getPaper($vignette->paperid);

$vignette->myvote = getMyVignettesVote($vignette->paperid, $vignette->userid);

if (!isset($vignette->vote)) $vignette->vote = 0;

//print_r($paperBib);

include("templates/papertitle.php");

include("templates/vignette.php");
?>
<?//print_r($paperBib);?>

<?php }?>

</div>
</div>


<script src="./res/marked/marked.min.js"></script>
<script src="./res/js/jquery.taboverride.min.js"></script>
<script src="./res/js/standard.js"></script>

<script type="text/x-mathjax-config">
MathJax.Hub.Config({
  tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
});
</script>
<script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_CHTML"></script>


<?php require("footer.php");?>