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
	<shortscience:votes><?=$vignette->vote?></shortscience:votes>
	<title><?=htmlspecialchars($paper->title)?></title>
  	<?php if ($vignette->anon == 1){?>
  	<author>Anonymous</author>
  	<?php } else { ?>
  	<author><?=($vignette->displayname == "")?$vignette->username:htmlspecialchars($vignette->displayname,ENT_QUOTES|ENT_DISALLOWED|ENT_XML1)?></author>
	<?php }?>
	<?php 
	$text = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $vignette->text);
	
	?>
	<?php if ($full == True){?>
	<description><?=htmlspecialchars($text)?></description>
	<?php }else{?>
	<description><?=(strlen(htmlspecialchars($text,ENT_QUOTES|ENT_DISALLOWED|ENT_XML1)) > 500) ? htmlspecialchars(substr($text,0,500),ENT_QUOTES|ENT_DISALLOWED|ENT_XML1).'...' : htmlspecialchars($text,ENT_QUOTES|ENT_DISALLOWED|ENT_XML1)?></description>
	<?php }?>
	<link>http://www.shortscience.org/paper?bibtexKey=<?=$vignette->paperid?>#<?=(($vignette->anon == 0)?$vignette->username:"anon")?></link>
	<guid>http://www.shortscience.org/paper?bibtexKey=<?=$vignette->paperid?>#<?=(($vignette->anon == 0)?$vignette->username:"anon")?></guid>
	<pubDate><?=date('r', strtotime($vignette->edited))?></pubDate>
</item>
<?php }?>
</channel>
</rss>
