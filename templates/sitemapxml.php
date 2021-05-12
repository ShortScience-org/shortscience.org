<?php 
global $DEFAULTBASEURL;
print('<?xml version="1.0" encoding="UTF-8"?>');
?>

<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

<url>
  <loc><?=$DEFAULTBASEURL?></loc>
</url>
<url>
  <loc><?=$DEFAULTBASEURL?>/about</loc>
</url>
<url>
  <loc><?=$DEFAULTBASEURL?>/venue</loc>
</url>

<?php for ($i = 0; $i < sizeof($vignettes); $i++) { ?>
<url>
  <loc><?=$DEFAULTBASEURL?>/paper?bibtexKey=<?=$vignettes[$i]->paperid?></loc>
</url>
<?php }?>

<?php 
//print_r($venues);
for ($i = 0; $i < sizeof($venues); $i++) {
	if ($venues[$i]->name != ""){?>
<url>
  <loc><?=$DEFAULTBASEURL?>/venue?key=<?=$venues[$i]->id?></loc>
</url>
<?php }}?>
</urlset>
