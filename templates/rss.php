<?php print('<?xml version="1.0" encoding="UTF-8"?>');?>

<rss 
version="2.0"
xmlns:shortscience="http://www.shortscience.org/">
<channel>
 <title>ShortScience.org Latest Summaries</title>
 <description>ShortScience.org Latest Summaries</description>
 <link>http://www.shortscience.org/</link>
 <ttl>60</ttl>
 <lastBuildDate><?=date('r', strtotime(date("Y-m-d H:i:s")))?></lastBuildDate>
<?php for ($i = 0; $i < sizeof($vignettes); $i++) { 
$vignette = $vignettes[$i];	
//print_r($vignette->vote);
if ($vignette->vote <= 1) continue;
$paper = getpaper($vignette->paperid);
//die();
?>
<item>
<?php 
$arxivid = getarxivid($paper);
if ($arxivid){?>
	<shortscience:arxivid><?=$arxivid?></shortscience:arxivid>
<?php }?>
	<shortscience:bibtexkey><?=$paper->bibtexKey?></shortscience:bibtexkey>
	<title>Summary of <?=htmlspecialchars($paper->title)?></title>
	<description><?=(strlen(htmlspecialchars($vignette->text)) > 500) ? htmlspecialchars(substr($vignette->text,0,500)).'...' : htmlspecialchars($vignette->text)?></description>
	<link>http://www.shortscience.org/paper?bibtexKey=<?=urlencode($vignette->paperid)?>#<?=(($vignette->anon == 0)?$vignette->username:"anon")?></link>
	<guid>http://www.shortscience.org/paper?bibtexKey=<?=urlencode($vignette->paperid)?>#<?=(($vignette->anon == 0)?$vignette->username:"anon")?></guid>
	<pubDate><?=date('r', strtotime($vignette->edited))?></pubDate>
</item>
<?php }?>
</channel>
</rss>
