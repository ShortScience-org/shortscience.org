<?php //print_r($paper);?>
<div class="papertitle well papertitle" style="min-height:130px;">
<!-- <span style="font-size:2.5em;float:left; padding-top:0px;padding-right:10px;" class="glyphicon glyphicon-book"></span>
 -->
<div name="links" style="font-size:1em;float:right;text-align: right;">
<?php for ($x = 0; $x < sizeof($paper->urls); $x++) {
		$url = $paper->urls[$x];
		$parse = parse_url($url);
		$urlhost = $parse['host'];
		if ($urlhost == "dblp.uni-trier.de") continue;
	?>
	<a target="_blank" href="<?=$url?>"><span  class="glyphicon glyphicon-link"></span> <?=$urlhost?></a><br>
	
	<?php if ($urlhost == "arxiv.org"){
		$numfound = preg_match("/(\\d{4}\\.\\d{4}\\d*)/",$url, $matches);
		$arxivid = $matches[0];
		
		//cs.[CV|CL|LG|NE]/stat.ML
		if (in_array("cs.CV",$paper->tags) ||
			in_array("cs.CL",$paper->tags) ||
			in_array("cs.LG",$paper->tags) ||
			in_array("stat.ML",$paper->tags) ||
			in_array("cs.NE",$paper->tags)){
			
		$arxivsanityid = $arxivid;
		?>
	
	<a target="_blank" href="http://www.arxiv-sanity.com/<?=$arxivsanityid?>"
	class="arxivsanitypop"
	title="Arxiv Sanity Preview"
	data-content='<img style="width:100%" src="http://www.arxiv-sanity.com/static/thumbs/<?=$arxivsanityid?>v1.pdf.jpg" alt=" Image loading error"/>'
	><span  class="glyphicon glyphicon-link"></span> arxiv-sanity.com</a><br>
	
	
	
	<?php }}?>
	<?php if ($urlhost != "arxiv.org" && 
			  $urlhost != "jmlr.org" && 
			  $urlhost != "www.jmlr.org" &&
			  $urlhost != "papers.nips.cc" &&
			  $urlhost != "www.cv-foundation.org" &&
			  !endsWith($urlhost,".edu")){?>
	
	<a target="_blank" href="http://sci-hub.cc/<?=$url?>"><span  class="glyphicon glyphicon-link"></span> sci-hub.cc</a><br>
	<?php }?>
	
<?php }?>

<a target="_blank" href='https://scholar.google.com/scholar?q="<?=$paper->title?>"'><span  class="glyphicon glyphicon-link"></span> scholar.google.com</a><br>

</div>

<?php if ($paperpage == true){?>
<big><span class="papertitle"><?=htmlspecialchars($paper->title)?></span></big>
<?php }else{?>
<a href="./paper?bibtexKey=<?=$paper->bibtexKey?>"><big><span class="papertitle"><?=htmlspecialchars($paper->title)?></span></big></a>
<?php }?>
<br>
<small>
<span class="paperauthors">
<?$authors = $paper->authors;
$authors = explode(" and ",$authors);
//print_r($authors);
for ($x = 0; $x < sizeof($authors); $x++) {
	
if ($x < 20){
?>
<?=($x>0? " and " : "")?> 
<a style="color:black;" rel="nofollow" href='search?term="<?=htmlspecialchars($authors[$x])?>"'/>
<?=htmlspecialchars($authors[$x])?>
</a>
<?php
}else{
?>
et al.
<?php
break;
}

}?>
</span>




<br>
<?php if ($paper->metavenue != null){?>
	<?$venue = $paper->metavenue->name." - ".$paper->year;?>
	<a style="color:black;" href='venue?key=<?=$paper->metavenue->id?>&year=<?=$paper->year?>'><?=$venue?></a> via <?=$paper->source?>
<?php }else{?>
	<?$venue = $paper->venue." - ".$paper->year;?>
	<a style="color:black;" rel="nofollow" href='search?term="<?=$venue?>"'><?=$venue?></a> via <?=$paper->source?>
<?php }?>
<br>
Keywords: 
<?php
for ($x = 0; $x < sizeof($paper->tags); $x++) {
?>
<?=($x>0? ", " : "")?>
<a style="color:black;" rel="nofollow" href='search?term="<?=htmlspecialchars($paper->tags[$x])?>"'><?=htmlspecialchars($paper->tags[$x])?></a><?php }?>
</small>
<br>
<?php if ($paper->abstract){?>
<small>
<div style="display:none" id="more-<?=base64_encode($vignette->paperid);?>">
<hr style="border-color:gray;margin:5px;">
<b>First published:</b> <?=date_format(date_create($paper->published),"Y/m/d")?> (<?=time_elapsed_string($paper->published)?>)<br>
<b>Abstract:</b><?=$paper->abstract?>
</div>
</small>
<small>

<span style="position:absolute;left:50%;width:100px;margin-left:-50px">
<center>
<span id="showmore-<?=base64_encode($vignette->paperid);?>"><a style="color:gray;" href="javascript:void($('#showmore-<?=base64_encode($vignette->paperid);?>').hide());void($('#showless-<?=base64_encode($vignette->paperid);?>').show());void($('#more-<?=base64_encode($vignette->paperid);?>').show());"><span class="glyphicon glyphicon-info-sign"></span> more</a></span>
<span id="showless-<?=base64_encode($vignette->paperid);?>" style="display:none;"><a style="color:gray;" href="javascript:void($('#showmore-<?=base64_encode($vignette->paperid);?>').show());void($('#showless-<?=base64_encode($vignette->paperid);?>').hide());void($('#more-<?=base64_encode($vignette->paperid);?>').hide());"><span class="glyphicon glyphicon-info-sign"></span> less</a></span>
</center>
</span>
</small>
<?php }?>

</div>
