<?php 

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=shortscience-export.csv");
header("Pragma: no-cache");
header("Expires: 0");

$out = fopen('php://output', 'w');

for ($i = 0; $i < sizeof($vignettes); $i++) {
$vignette = $vignettes[$i];
//$paperBib = getPaper($vignette->paperid);

//if (!isset($vignette->vote)) $vignette->vote = 0;

//$text = trim(preg_replace('/\n/', ' ', $vignette->text));

$text = trim($vignette->text);

$line = array($vignette->paperid, $text);

fputcsv($out,$line);

}
?>