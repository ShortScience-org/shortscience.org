<?php require("header.php");?>
<?php require("menu.php");?>

<div class="container" style="max-width:750px; margin:0 auto;">

<div class="row">

<div class="alert" style="background-color: rgb(184, 77, 100); color:white;font-size: 1.2em;">
<table style="width:100%">
<tr>
<td style="width:90px">
<img src="res/cabin.png" style="width:60px;padding:5px;"/>
</td>
<td>
<span style="vertical-align: middle;">
<center>
Welcome to ShortScience.org <br>
<small>Post-publication discussions to make research more accessible!</small>
</center>
</span>
</td>
<td style="width:120px">
<a target="_blank" href="/rss.xml">
<img src="res/img/rss-icon-s.png" style="width:50px" alt="RSS Feed"/>
</a>
<a target="_blank" href="https://twitter.com/shortscienceorg">
<img src="res/img/sharebuttons/simple/twitter.png" style="width:50px;border-radius:10px;padding:4px" alt="Twitter"/>
</a>
</td>
</tr>
</table>
</div>


<ul class="nav nav-tabs">

<li role="presentation" <?=($tab == "" || $tab == "popular")?'class="active"':''?>><a href="/?tab=popular">Popular</a></li>
<li role="presentation" <?=($tab == "recent")?'class="active"':''?>><a href="/?tab=recent">Most Recent</a></li>
<li role="presentation" <?=($tab == "best")?'class="active"':''?>><a href="/?tab=best">Highest Rated</a></li>

</ul>
<br>

<?php 
for ($i = 0; $i < sizeof($vignettes); $i++) {
$vignette = $vignettes[$i];
$paper = getPaper($vignette->paperid);

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