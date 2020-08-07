<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'config.php';
require 'db.php';
require 'auth.php';
require 'functions.php';


?>
<pre>
<?php

if( ini_get('allow_url_fopen') ) {
    print('allow_url_fopen is enabled. file_get_contents should work well');
} else {
    print('allow_url_fopen is disabled. file_get_contents would not work');
}



print("<br><br>Local");
$res = searchLocal("".$_GET["q"]);
if (count($res)>0){
    print_r($res);
}else{
    print_r(error_get_last());
}


print("<br><br>Bibsonomy");
$res = searchBibsonomy("".$_GET["q"]);
if (count($res)>0){
    print_r($res);
}else{
    print_r(error_get_last());
}

print("<br><br>CrossRef");
$res = searchCrossRef("".$_GET["q"]);
if (count($res)>0){
    print_r($res);
}else{
    print_r(error_get_last());
}

print("<br><br>Arxiv");
$res = searchArXiv("".$_GET["q"]);
if (count($res)>0){
    print_r($res);
}else{
    print_r(error_get_last());
}


?>
</pre>