<?php require("header.php");?>
<?php require("menu.php");?>

<?php require("orcid.php");?>

<br>
<div class="container" style="max-width:700px; margin:0 auto;">

<div class="alert" style="background-color:rgb(250,250,250);">
<div class="row">
<div class="col-xl-8">
<h1 style='margin:0px;padding-left:10px;' >
<a href="<?=($currentuser->userid == $user->userid)?"https://en.gravatar.com/site/login/":""?>"><?=get_gravatar($user->email,80,identicon,'g',true,[style=> "border-radius: 50%;" ])?></span></a>

<?=htmlspecialchars(($user->displayname)?$user->displayname:$user->username)?>
</h1>
</div>

<div class="col-xl-4" style="text-align: right">
<h3 style="padding-right:10px;" title="The value has random noise added so anonymous posts remain anonymous">(sciscore: <?=getUserVignettePoints($user->userid)?>)</h3><br>

</div>
</div>
<br>
<div style="text-align: left;max-height:300px;overflow:auto;"><?=htmlspecialchars($user->description)?></div>
</div>


<ul class="nav nav-tabs">

<li role="presentation" <?=($tab == "")?'class="active"':''?>><a href="?name=<?=$user->username?>">My Summaries <span class="badge"><?=sizeof($vignettes)?></span></a></li>

<!--  <li role="presentation" <?=($tab == "papers")?'class="active"':''?>><a href="?name=<?=$user->username?>&tab=papers">My Papers</a></li>-->

<?php if ($currentuser->userid == $user->userid){?>

<!-- <li role="presentation" <?=($tab == "settings")?'class="active"':''?>><a href="?name=<?=$user->username?>&tab=settings">Settings</a></li>-->

<!--  
  <li role="presentation"><a href="?name=<?=$user->username?>&tab=pinned">Pinned Summaries (not yet)</a></li>
  
  <li role="presentation"><a href="?name=<?=$user->username?>&tab=messages">Messages (not yet)</a></li>
-->
<?php }?>
</ul>




<?php if ($tab == ""){?>
<br>
<div id="myvignettes">
<?php if (sizeof($vignettes) ==0 && $currentuser->userid == $user->userid){?>
	<center>
	<br>
	<img src="res/profile.png"/><br><br>
	
	<div class="well">
	Hey! Welcome to Short Science! Once you write summaries for articles they will show up here.
	</div>
	</center>
<?php }?>
<?php 
for ($i = 0; $i < sizeof($vignettes); $i++) {
$vignette = $vignettes[$i];
$paper = getPaper($vignette->paperid);

$vignette->myvote = getMyVignettesVote($vignette->paperid, $vignette->userid);

if (!isset($vignette->vote)) $vignette->vote = 0;

//print_r($paperBib);
include("templates/papertitle.php");
include("templates/vignette.php");
}
?>
</div>
<?php }?>





<?php if ($tab == "papers"){?>

<center>
<div style="vertical-align:center;padding-top:10px">
Papers imported from <img style="height:10px;" alt="orcid" src="http://i.imgur.com/4JyjLva.png"/> and ar$\Large\chi$iv for id <?=htmlspecialchars($user->orcid)?>
</div>
</center>

<?php 
if ($user->orcid != ""){
	
	$papers = getORCIDPapers($user->orcid);
	//print_r($papers);
}
?>

<br>
<div id="mypapers">
<?php if (sizeof($papers) ==0){?>
	<center>
	<br>
	
	<div class="well">
	Papers associated with an ORCID profile will be shown here. 
	Also if an ORCID is associated with an ar$\Large\chi$iv profile those papers will also be shown.
	</div>
	</center>
<?php }?>
<?php 
for ($i = 0; $i < sizeof($papers); $i++) {
$paper = $papers[$i];
//$paper = getPaper($vignette->paperid);

//print_r($paper);
include("templates/papertitle.php");


$vignettes = getVignettes($paper->bibtexKey);

if (sizeof($vignettes) > 0){
	$vignette = $vignettes[0];
	$vignette->myvote = getMyVignettesVote($vignette->paperid, $vignette->userid);
	if (!isset($vignette->vote)) $vignette->vote = 0;
	include("templates/vignette.php");
}
}
?>
</div>
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