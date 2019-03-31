<?//print_r($paper);?>
<div>
<a href="paper?bibtexKey=<?=$paper->bibtexKey?>"><?=htmlspecialchars($paper->title)?></a>


<?php if ($paper->numOfVignettes == 1){ ?>
<span class="label label-success"><?=$paper->numOfVignettes?> Summary</span>
<?php } else if ($paper->numOfVignettes > 1){?>
<span class="label label-success"><?=$paper->numOfVignettes?> Summaries</span>
<?php }?>

<br>
<small>
<?$authors = $paper->authors;
$authors = explode(" and ",$authors);
//print_r($authors);
for ($x = 0; $x < sizeof($authors); $x++) { 

if ($x < 20){
?>
<?=($x>0? " and " : "")?> <a style="color:black;" rel="nofollow" href='search?term="<?=htmlspecialchars($authors[$x])?>"'/><?=htmlspecialchars($authors[$x])?></a>
<?php
}else{
?>
et al.
<?php
break;
}
}?>

<br>

<?php if ($paper->metavenue != null){?>
	<?$venue = $paper->metavenue->name." - ".$paper->year;?>
	<a style="color:black;" href='venue?key=<?=$paper->metavenue->id?>&year=<?=$paper->year?>'><?=htmlspecialchars($venue)?></a> via <?=$paper->source?>
<?php }else{?>
	<?$venue = $paper->venue." - ".$paper->year;?>
	<a style="color:black;" rel="nofollow" href='search?term="<?=htmlspecialchars($venue)?>"'><?=htmlspecialchars($venue)?></a> via <?=$paper->source?>
<?php }?>

<br>
Keywords: 
<?php
for ($x = 0; $x < sizeof($paper->tags); $x++) {
?>
<?=($x>0? ", " : "")?>
<a style="color:black;" rel="nofollow" href='search?term="<?=htmlspecialchars($paper->tags[$x])?>"'/><?=htmlspecialchars($paper->tags[$x])?></a><?php }?>
</small>
</div>