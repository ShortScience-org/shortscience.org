<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>	
<meta charset="utf-8">

<?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'obile') !== false) {?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php }else{?>
<?php }?>

<?php if ($title != ""){ ?>

<title><?=$title?> - ShortScience.org</title>
<meta property="og:title" content="<?=$title?> - ShortScience.org">
<meta property="og:description" content="<?=$description?>">
<meta name="description" content="<?=$description?>">
<meta name="keywords" content="summary, summaries, intuition, breakdown, short, understanding, explain, explanation, comment, interpretation, motivation, commentary, example, science, researchers, academic, academia, university, college, professor">

<?php } else if (isset($paper)) { ?>

<title>Summary of <?=$paper->title?> on ShortScience.org</title>
<meta property="og:title" content="Summary of <?=$paper->title?> on ShortScience.org">
<meta property="og:description" content="<?=htmlspecialchars($vignette->text)?>">
<meta name="description" content="<?=htmlspecialchars($vignette->text)?>">
<meta property="og:keywords" content='summary, summaries, intuition, breakdown, short, understanding, explain, explanation, comment, interpretation, motivation, commentary, example, science, researchers, academic, academia, university, college, professor, <?=implode(", ", $paper->tags)?>'>
<meta name="keywords" content="summary, summaries, intuition, breakdown, short, understanding, explain, explanation, comment, interpretation, motivation, commentary, example, science, researchers, academic, academia, university, college, professor,  <?=implode(", ", $paper->tags)?>">
<meta property="og:url" content="http://www.shortscience.org/paper?bibtexKey=<?=$paper->bibtexKey?>" />

<?php } else { ?>

<title>ShortScience.org - Making Science Accessible!</title>
<meta property="og:title" content="ShortScience.org - Making Science Accessible!">
<meta property="og:description" content='ShortScience.org allows researchers to publish paper summaries that are voted on and ranked until the best and most accessible summary has been found! The goal is to make all the seminal ideas in science accessible to the people that want to understand them.'>
<meta name="description" content="ShortScience.org allows researchers to publish paper summaries that are voted on and ranked until the best and most accessible summary has been found! The goal is to make all the seminal ideas in science accessible to the people that want to understand them.">
<meta name="keywords" content="summary, summaries, intuition, breakdown, short, understanding, explain, explanation, comment, interpretation, motivation, commentary, example, science, researchers, academic, academia, university, college, professor">

<?php } ?>

<meta property="og:site_name" content="www.shortscience.org" />
<meta property="og:locale" content="en_US" />  
<meta property="og:type" content="article" />
<meta property="og:image" content="http://www.shortscience.org/res/albert2.jpg" />


<?php 
if ($extraheader != ""){ print($extraheader); }
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
<link rel="stylesheet" href="res/css/standard.css">

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