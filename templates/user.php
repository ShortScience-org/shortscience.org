<?php require("header.php");?>
<?php require("menu.php");?>
<?php require("orcid.php");?>
<?php global $SCRIPT_VERSION ?>
<?php global $MATHJAX_URL?>

<br>
<div class="container" style="max-width:700px; margin:0 auto;">

<div class="alert" style="background-color:rgb(250,250,250);">
<div class="row">
<div class="col-xl-8">
<h1 style='margin:0px;padding-left:10px;' >
<a href="<?=($currentuser->userid == $user->userid)?"https://en.gravatar.com/site/login/":""?>"><?=get_gravatar($user->email,80,"identicon",'g',true,["style"=> "border-radius: 50%;" ])?></span></a>

<?=htmlspecialchars(($user->displayname)?$user->displayname:$user->username,ENT_QUOTES)?>
</h1>
</div>

<div class="col-xl-4" style="text-align: right">
<h3 style="padding-right:10px;" title="The sum of all upvotes for public summaries divided by the number of summaries"><span class="glyphicon glyphicon-education"></span> sciscore: <?=getUserVignettePoints($user->userid)?></h3><br>

</div>
</div>
<br>
<div style="text-align: left;max-height:300px;overflow:auto;"><?=htmlspecialchars($user->description)?></div>
</div>


<ul class="nav nav-tabs">

<li role="presentation" <?=($tab == "")?'class="active"':''?>><a href="?name=<?=$user->username?>">My Summaries <span class="badge"><?=sizeof($vignettes)?></span></a></li>

<!--  <li role="presentation" <?=($tab == "papers")?'class="active"':''?>><a href="?name=<?=$user->username?>&tab=papers">My Papers</a></li>-->

<?php if ($currentuser->userid == $user->userid){?>

<li role="presentation" <?=($tab == "liked")?'class="active"':''?>><a href="?name=<?=$user->username?>&tab=liked"><span class="glyphicon glyphicon-lock" aria-hidden="true" alt="Only you can see this"></span> Liked <span class="badge"><?=sizeof($likedvignettes)?></span></a></li>
<li role="presentation" <?=($tab == "disliked")?'class="active"':''?>><a href="?name=<?=$user->username?>&tab=disliked"><span class="glyphicon glyphicon-lock" aria-hidden="true" alt="Only you can see this"></span> Disliked <span class="badge"><?=sizeof($dislikedvignettes)?></span></a></li>
<li role="presentation" <?=($tab == "visits")?'class="active"':''?>><a href="?name=<?=$user->username?>&tab=visits"><span class="glyphicon glyphicon-lock" aria-hidden="true" alt="Only you can see this"></span> My Views</a></li>


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
	Hey! Welcome to ShortScience.org! Once you write summaries for articles they will show up here.
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


<?php if ($tab == "liked"){?>
<br>
<div id="liked">
<?php 
for ($i = 0; $i < sizeof($likedvignettes); $i++) {
$vignette = $likedvignettes[$i];
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

<?php if ($tab == "disliked"){?>
<br>
<div id="disliked">
<?php 
for ($i = 0; $i < sizeof($dislikedvignettes); $i++) {
$vignette = $dislikedvignettes[$i];
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

<?php if ($tab == "visits"){?>
<br>
<div id="visits">
<center>
<div style="vertical-align:center;padding-top:10px">
These plots shows the visits per day of all papers you have written summaries for.
</div>
</center>
<center>
<iframe id="iframe-<?=$more_hash?>" style="padding-top: 10px; width:100%; height:280px; max-width:100%" scrolling="no" src="/visits?userid=<?=$user->userid?>&report=7d"  frameborder="0" >Loading...</iframe>
</center>

<center>
<iframe id="iframe-<?=$more_hash?>" style="padding-top: 10px; width:100%; height:280px; max-width:100%" scrolling="no" src="/visits?userid=<?=$user->userid?>&report=1m"  frameborder="0" >Loading...</iframe>
</center>
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
<script src="./res/js/standard.js?v=<?=$SCRIPT_VERSION?>"></script>

<script type="text/x-mathjax-config">
MathJax.Hub.Config({
  tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
});
</script>
<script type="text/javascript" async src="<?=$MATHJAX_URL?>"></script>


<?php require("footer.php");?>