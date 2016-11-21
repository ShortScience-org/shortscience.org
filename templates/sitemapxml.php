<?php print('<?xml version="1.0" encoding="UTF-8"?>');?>

<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

<url>
  <loc>http://www.shortscience.org/</loc>
</url>
<url>
  <loc>http://www.shortscience.org/about</loc>
</url>
<url>
  <loc>http://www.shortscience.org/venue</loc>
</url>

<?php for ($i = 0; $i < sizeof($vignettes); $i++) { ?>
<url>
  <loc>http://www.shortscience.org/paper?bibtexKey=<?=urlencode($vignettes[$i]->paperid)?></loc>
</url>
<?php }?>

<?php 
//print_r($venues);
for ($i = 0; $i < sizeof($venues); $i++) {
	if ($venues[$i]->name != ""){?>
<url>
  <loc>http://www.shortscience.org/venue?key=<?=urlencode($venues[$i]->id)?></loc>
</url>
<?php }}?>
</urlset>
