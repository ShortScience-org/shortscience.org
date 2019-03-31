<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'config.php';
require 'db.php';
require 'auth.php';
require 'functions.php';

print("<br><br>Bibsonomy");
print_r(searchBibsonomy("".$_GET["q"]));

print("<br><br>CrossRef");
print_r(searchCrossRef("cohen".$_GET["q"]));

print("<br><br>Arxiv");
print_r(searchArXiv("cohen".$_GET["q"]));


?>