<?php require("header.php");?>
<?php require("menu.php");?>
<?php global $SCRIPT_VERSION ?>
<?php global $MATHJAX_URL?>
<?php //we are given $paper, $paperBib, and $vignettes
//print_r($paperBib);
?>

<?if (sizeof($vignettes) != 0){?>

<?php 
$pattern = '/https?:\/\/[^ ]+?(?:\.jpg|\.png|\.gif)/';
preg_match($pattern, $vignette->text, $matches);

if (count($matches) > 0){
	$imgurl = $matches[0];
}else{
	$imgurl = "http://www.shortscience.org/res/albert2.jpg";
}
?>


<div style="display:none;">
<div itemscope itemtype="http://schema.org/Review">
  <div itemprop="itemReviewed" itemscope itemtype="http://schema.org/Article">
    <span itemprop="name"><?=$paper->title?></span>
    <span itemprop="headline"><?=$paper->title?></span>
    <span itemprop="author"><?=$paper->authors?></span>
    <span itemprop="datePublished"><?=$paper->year?></span>
    <span itemprop="dateModified" content="<?=$vignette->edited?>"/>
    
	<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
		<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
			<img src="<?=$imgurl?>"/>
			<meta itemprop="url" content="<?=$imgurl?>">
		</div>
    	<meta itemprop="name" content="<?=$paper->venue?>">
	</div>
    
    <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
		<img src="<?=$imgurl?>"/>
		<meta itemprop="url" content="<?=$imgurl?>">
	</div>
    <a itemprop="mainEntityOfPage" href="http://www.shortscience.org/paper?bibtexKey=<?=$paper->bibtexKey?>">
  </a>
  </div>
  <span itemprop="name">Paper summary</span>
  <span itemprop="author" itemscope itemtype="http://schema.org/Person">
	<span itemprop="name"><?=($vignette->anon == 1)?"anonymous":$vignette->username?></span>
  </span>
  <span itemprop="reviewBody"><?=htmlspecialchars($vignette->text)?></span>
  <div itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
    <meta itemprop="name" content="Short Science">
  </div>
</div>
</div>
<?php }?>

<div class="container main" style="max-width:700px; margin:0 auto;">
<div class="row">
<?php $paperpage = true; include("templates/papertitle.php");?>


<ul class="nav nav-tabs">
  <li role="presentation" <?=($tab == "")?'class="active"':''?>><a style="cursor:hand;cursor: pointer;" href="?bibtexKey=<?=$paper->bibtexKey?>">Summaries/Notes <span class="badge"><?=(0!=sizeof($vignettes))?sizeof($vignettes):""?></span></a></li>
</ul>
<br>



<?if (sizeof($vignettes) == 0){?>
<center>
<img style="width:200px;max-width:100%;" src="res/searching.png"/><br><Br>
Other scientists are still reading the paper! Why not add a summary yourself?
</center>
<?}?>

<?php 
for ($v = 0; $v < sizeof($vignettes); $v++){
	$vignette = $vignettes[$v];
	$showcomments = true;
	
	if ($authorfocus != ""){
	    $vignettefocus = ($vignette->username == $authorfocus);
	    if  ($vignettefocus){
    	   include("templates/vignette.php");
	    }
	}else{
	    include("templates/vignette.php");
	}
}?>

</div>
<hr>
<div id="yourentry"> 
	<div class="row">
	<label>Write your summary here (You can use $\LaTeX$ and <a target="_blank" href="https://en.support.wordpress.com/markdown-quick-reference/">markdown syntax</a>):</label>
	<textarea class="form-control" rows="15" id="entrytext"><?=$myvignette->text?></textarea>
	
		<input type="hidden" id="paperid" value="<?=$myvignette->paperid?>"/>
		<input type="hidden" id="userid" value="<?=$myvignette->userid?>"/>
	</div>
	<div class="row">
		<button class="btn btn-default pull-left" type="delete" id="deleteentry" style="margin:10px;" <?=($currentuser->userid == -1)?"disabled":""?>>Delete</button>
		<div class="form-inline pull-right">
		
		

		

		<button class="btn btn-default" id="imgUploadBtn" style="margin:10px" onclick="document.querySelector('#imgUpload').click()" <?=($currentuser->userid == -1)?"disabled":""?>>Add Image</button>
		<input id="imgUpload" style="visibility: collapse; width: 0px; display:none;" type="file" onchange="uploadImage(this.files[0], '#entrytext')" >

		<label for="priv" title="Your name will not be shown with this summary">
		<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Anon
		</label> <input type="checkbox" <?=($myvignette->anon == 1)?"checked":""?> id="entryanon">

		<label for="priv" title="No one will be able to see this summary but you">
		<span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Private
		</label> <input type="checkbox" <?=($myvignette->priv == 1)?"checked":""?> id="entrypriv">

		  <button class="btn btn-default" type="submit" id="submitentry" style="margin:10px" <?=($currentuser->userid == -1)?"disabled":""?>>Submit</button>
		</div>  
	</div>
	
	<div class="row" id="errorbox">
	</div>
		
	<div id="entrypreview" style="display:none;">
	
	<?php if ($currentuser->userid == -1){ ?>

	<div class="alert alert-danger" role="alert" style="margin:10px;">
	  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	  You must log in before you can submit this summary! Your draft will not be saved!
	</div>

<?php }?>
	
	<div class="" style="">Preview:</div>
	<?php  
		$preview = true;  
		$vignette=$myvignette; 
		$showcomments = false;
		include("templates/vignette.php");
		?>
	</div>
	</div>
<?php ?>





</div>


<script src="./res/marked/marked.min.js"></script>
<script src="./res/js/jquery.taboverride.min.js"></script>
<script src="./res/js/standard.js?v=<?=$SCRIPT_VERSION?>"></script>
<script src="./res/js/paper.js?v=<?=$SCRIPT_VERSION?>"></script>


<script type="text/x-mathjax-config">
MathJax.Hub.Config({
  tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
});
</script>
<script type="text/javascript" async src="<?=$MATHJAX_URL?>"></script>

<div class="paddingdiv" style="height:200px;"></div>
<?php require("footer.php");?>








