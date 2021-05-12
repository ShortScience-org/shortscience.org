<?php global $DEFAULTBASEURL; ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>	
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php if (isset($title) && $title != ""){ ?>

<title><?=$title?> - ShortScience.org</title>
<meta property="og:title" content="<?=$title?> - ShortScience.org">
<meta property="og:description" content="<?=$description?>">
<meta name="description" content="<?=$description?>">
<meta name="keywords" content="summary, summaries, intuition, breakdown, short, understanding, explain, explanation, comment, interpretation, motivation, commentary, example, science, researchers, academic, academia, university, college, professor">
<meta property="og:image" content="<?=$DEFAULTBASEURL?>/res/albert2.jpg" />

<?php } else if (isset($paper)) { ?>

<?php 
$pattern = '/https?:\/\/[^ ]+?(?:\.jpg|\.png|\.gif)/';
preg_match($pattern, $vignette->text, $matches);
if (count($matches) > 0){
	$imgurl = $matches[0];
}else{
	$imgurl = "<?=$DEFAULTBASEURL?>/res/albert-s.jpg";
}
?>


<?php 
// clean desc for seo

$text_clean = preg_replace('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', ' ', $vignette->text);

$text_clean = substr($text_clean, 0,100)."...";

$text_clean = htmlspecialchars($text_clean);

?>


<title><?=$paper->title?> on ShortScience.org</title>
<meta property="og:title" content="<?=$paper->title?> - ShortScience.org">
<meta property="og:description" content="<?=$text_clean?>">
<meta name="description" content="<?=$text_clean?>">
<meta property="og:keywords" content='summary, summaries, intuition, breakdown, short, understanding, explain, explanation, comment, interpretation, motivation, commentary, example, science, researchers, academic, academia, university, college, professor, <?=implode(", ", $paper->tags)?>'>
<meta name="keywords" content="summary, summaries, intuition, breakdown, short, understanding, explain, explanation, comment, interpretation, motivation, commentary, example, science, researchers, academic, academia, university, college, professor,  <?=implode(", ", $paper->tags)?>">
<meta property="og:url" content="<?=$DEFAULTBASEURL?>/paper?bibtexKey=<?=$paper->bibtexKey?>" />
<meta property="og:image" content="<?=$imgurl?>" />


<div style="display:none;">

<div itemscope itemtype="http://schema.org/Article">
  <div itemprop="itemReviewed" itemscope itemtype="http://schema.org/Book">
    <span itemprop="name"><?=$paper->title?></span>
    <span itemprop="headline"><?=$paper->title?></span>
    <span itemprop="author"><?=$paper->authors?></span>
    <?php //<span itemprop="isbn"><?=$paper->bibtexKey?></span> ?>
    <span itemprop="datePublished"><?=$paper->year?></span>
    
	<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
	    <?php if ($paper->metavenue != null){?>
    	<meta itemprop="name" content="<?=$paper->metavenue->name?>">
    	<?php }else{?>
    	<meta itemprop="name" content="<?=$paper->venue?>">
    	<?php }?>
	</div>
    
    <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
		<img src="<?=$imgurl?>"/>
		<meta itemprop="url" content="<?=$imgurl?>">
	</div>
    <a itemprop="mainEntityOfPage" href="<?=$DEFAULTBASEURL?>/paper?bibtexKey=<?=$paper->bibtexKey?>">
  </a>
  </div>
  
  
  <span itemprop="name">Paper summary</span>
  <span itemprop="author" itemscope itemtype="http://schema.org/Person">
	<span itemprop="name"><?=($vignette->anon == 1)?"anonymous":$vignette->username?></span>
  </span>
  <span itemprop="reviewBody"><?=htmlspecialchars($text_clean)?></span>
  <div itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
    <meta itemprop="name" content="ShortScience.org">
  </div>
  <span itemprop="datePublished"><?=$vignette->added?></span>
  <span itemprop="dateModified"><?=$vignette->edited?></span>
  <span itemprop="description">A summary of the paper titled "<?=$paper->title?>"</span>
  <span itemprop="url"><?=$DEFAULTBASEURL?>/paper?bibtexKey=<?=$paper->bibtexKey?></span>
</div>

</div>


<?php } else if (isset($venue)) { ?>

<?php 
if ($venue->imgurl != ""){
	$imgurl = $venue->imgurl;
}else{
	$imgurl = "<?=$DEFAULTBASEURL?>/res/albert-s.jpg";
}
?>

<title>Summaries from <?=$venue->name?> on ShortScience.org</title>
<meta property="og:title" content="Summaries from <?=$venue->name?> on ShortScience.org">
<meta property="og:description" content="Summaries of the research papers published in <?=$venue->name?>">
<meta name="description" content="Summaries of the research papers published in <?=$venue->name?>">
<meta property="og:url" content="<?=$DEFAULTBASEURL?>/venue?key=<?=$venue->id?>" />
<meta property="og:image" content="<?=$imgurl?>" />

<?php } else { ?>

<title>ShortScience.org - Making Science Accessible!</title>
<meta property="og:title" content="ShortScience.org - Making Science Accessible!">
<meta property="og:description" content='ShortScience.org is a platform for post-publication discussion aiming to improve accessibility and reproducibility of research ideas.'>
<meta name="description" content="ShortScience.org is a platform for post-publication discussion aiming to improve accessibility and reproducibility of research ideas.">
<meta name="keywords" content="summary, summaries, intuition, breakdown, short, understanding, explain, explanation, comment, interpretation, motivation, commentary, example, science, researchers, academic, academia, university, college, professor">
<meta property="og:image" content="<?=$DEFAULTBASEURL?>/res/albert2.jpg" />

<?php } ?>

<meta property="og:site_name" content="shortscience.org" />
<meta property="og:locale" content="en_US" />  



<?php 
if (isset($extraheader) && $extraheader != ""){ print($extraheader); }
?>


<?php if (false){ ?>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="res/local/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="res/local/bootstrap-theme.min.css">

<script src="res/local/jquery-2.2.2.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="res/local/bootstrap.min.js"></script>

<?php }else{ ?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="res/css/standard.css?v=1">

<?php } ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-76469108-1', 'auto');
  ga('send', 'pageview');

</script>


</head>
<body>
