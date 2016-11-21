<?php 


/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
	$url = 'http://www.gravatar.com/avatar/';
	$url .= md5( strtolower( trim( $email ) ) );
	$url .= "?s=$s&d=$d&r=$r";
	if ( $img ) {
		$url = '<img src="' . $url . '"';
		foreach ( $atts as $key => $val )
			$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
	}
	return $url;
}


//https://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
function time_elapsed_string($datetime, $full = false) {
	$now = new DateTime;
	$ago = new DateTime($datetime);
	$diff = $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}

	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function performSearch($term){
	
	if ($term == "") $term = "\"Cohen, Joseph Paul\"";
	
	$term = str_replace("-"," ",$term);
	$term = str_replace("+"," ",$term);
	
	$results = (object)[];
	if (strpos($term, "\"") !== false) {
		// has quotes
	
		$results0 = searchLocal($term, false);
		
		$results1 = searchBibsonomy($term);
		$results2 = searchArXivMeta($term);
		$results3 = searchCrossRef($term);
		$results = mergeResults(array($results0, $results1, $results2, $results3));
		
	}else{
		
		
		$results0 = searchLocal($term, false);
		
		// check arxiv
		$numfound = preg_match("/(\\d{4}\\.\\d{4}\\d*)/",$term, $matches);
		if ($numfound > 0){
			$arxivid = $matches[0];
			$results11 = searchBibsonomy($arxivid);
		}else{
			$results12 = searchBibsonomy("\"".$term."\"");
		}
		
		$results1 = mergeResults(array($results11, $results12));
		$results2 = searchArXivMeta($term);
		$results3 = searchCrossRef($term);
		$results4 = searchBibsonomy($term);	
		
		$results = mergeResults(array($results0, $results1, $results2, $results3, $results4));
	}
	
	// pull how many entries we have for it.
	for ($i = 0; $i < sizeof($results); $i++) {
		$results[$i]->numOfVignettes = numOfVignettes($results[$i]->bibtexKey);
	}
	
	// set the venue
	for ($i = 0; $i < sizeof($results); $i++) {
		
		if ($results[$i]->venuekey)
			// if we hard coded the venue
			$results[$i]->metavenue = getVenueForBibtexKey($results[$i]->venuekey);
		else
			$results[$i]->metavenue = getVenueForBibtexKey($results[$i]->bibtexKey);
	}
	
	// sort based on numOfVignettes
	$resultsN = array();
	
	for ($r = 0; $r < sizeof($results); $r++) {
		if (strcmp($results[$r]->bibtexKey,$term) == 0){
			$resultsN[] = $results[$r];
			unset($results[$r]);
		}
	}
	
	
	for ($i = 0; $i < sizeof($results); $i++) {
		if ($results[$i]->numOfVignettes > 0)
			$resultsN[] = $results[$i];
	}
	
	for ($i = 0; $i < sizeof($results); $i++) {
		if ($results[$i]->numOfVignettes <= 0)
			$resultsN[] = $results[$i];
	}
	
	
	$resultsY = array();
	for ($i = 0; $i < sizeof($resultsN); $i++) {
		if (isset($resultsN[$i]->metavenue)){
			$resultsY[] = $resultsN[$i];
		}
	}
	
	for ($i = 0; $i < sizeof($resultsN); $i++) {
		if (!isset($resultsN[$i]->metavenue))
			$resultsY[] = $resultsN[$i];
	}
	
	
	return $resultsY;
}

// from https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
function startsWith($haystack, $needle) {
	// search backwards starting from haystack length characters from the end
	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}
// from https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
function endsWith($haystack, $needle) {
	// search forward starting from end minus needle length characters
	return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

function mergeResults($multipleresults){
	
	$results = array();//$results1->post;
	
	for ($r = 0; $r < sizeof($multipleresults); $r++) {
		
		$singleresults = $multipleresults[$r];
		
		for ($i = 0; $i < sizeof($singleresults); $i++) {
		
			if ($singleresults[$i]->bibtexKey != ""){
			
				$unique = true;
				for ($j = 0; $j < sizeof($results); $j++) {
					
					
					if ($results[$j]->bibtexKey == $singleresults[$i]->bibtexKey)
						$unique = false;
				}
			
				if ($unique)
					$results[] = $singleresults[$i];
			}
		}
	}
	return $results;
}


/*
each search entry will have the following fields

$paper = (object)[];
$paper->bibtexKey
$paper->title
$paper->authors
$paper->year
$paper->venue
$paper->urls // array
$paper->tags
$paper->source
 */

function searchLocal($term, $searchjustkey = false){
	
	if ($term == "") die("must have keyword to search");
	
	
	
	if ($searchjustkey){
		$sql = "select * FROM papers WHERE bibtexKey = :term";
	}else if (strpos($term, "\"") !== false){
		// have quote
		$term = str_replace('"', "", $term);
		$sql = "select * FROM papers WHERE bibtexKey = :term OR title = :term";
	}else{
		$searchterm = "%".$term."%";
		$sql = "select * FROM papers WHERE bibtexKey = :term OR title LIKE trim(:searchterm)";
	}
	
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("term", $term);
		if ($searchterm)
			$stmt->bindParam("searchterm", $searchterm);
		$stmt->execute();
		$papers = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
	
		for ($i = 0; $i < sizeof($papers); $i++) {
			
			$paper = $papers[$i];
			
			$urls = array();
			$re = "/(www\\.|https?:\\/\\/)?[a-zA-Z0-9]{2,}\\.[a-zA-Z0-9]{2,}(\\S*)/"; 
			
			preg_match_all($re, $paper->urls, $urls);
			
			$paper->urls = $urls[0];
			
			$paper->tags = explode(",",$paper->tags);
			
			$paper->source = "Local";
			
		}

		return $papers;

		
	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
	
	
	
	
}


function searchCrossRef($term){
	
	if ($term == "") die("must have keyword to search");
	
	//http://search.crossref.org/dois?q=renear+palmer
	
	$urlterm = urlencode($term);
	$json = cachedWebRequest("crossref",$term,"http://search.crossref.org/dois?q=".$urlterm);
	
	//$json = file_get_contents("http://search.crossref.org/dois?q=".$urlterm);
	$obj = json_decode($json);
	
	$toreturn = array();	
	for ($i = 0; $i < sizeof($obj); $i++) {
	
		$paperBib = $obj[$i];
		
		//print_r($paperBib);

		$paper = (object)[];		
		$paper->bibtexKey = trim(str_replace("http://dx.doi.org/","",$paperBib->doi));
		$paper->title = trim($paperBib->title);
		$paper->year = trim($paperBib->year);
		
		$rawcoins = str_replace("&amp","",urldecode($paperBib->coins));
		$coins = str_getcsv($rawcoins, $delimiter = ";");

		$firstauth = true;
		for ($v = 0; $v < sizeof($coins); $v++) {
			
			$coin = $coins[$v];
			
			if (strpos($coin, 'rft.au=') !== false) {
				if (!$firstauth){
					$paper->authors .= " and ";
				}
				$firstauth = false;
				$paper->authors .= trim(explode("=", $coin)[1]);
			}
			
			if (strpos($coin, 'rft.jtitle=') !== false) {
				$paper->venue .= trim(explode("=", $coin)[1]);
			}			
		}
		$paper->tags = array();
	
		for ($x = 0; $x < sizeof($paperBib->tag); $x++) {
			$paper->tags[] = trim($paperBib->tag[$x]['name']);
		}
	
		$paper->urls = array();
		$paper->urls[] = trim($paperBib->doi);
		
		$paper->source = "CrossRef";
		
		//print_r($paper);
		
		if ($paper->title != "" && $paper->authors != "")
			$toreturn[] = $paper;
	}
	return $toreturn;
}


function searchBibsonomy($term){

	global $BIBSONOMY_LOGIN;
	
	//1c414c050cd5b96c3858e0fa84f8ec82

	if ($term == "") die("must have keyword to search");

	$urlterm = urlencode($term);
	
	$xml = cachedWebRequest("bibsonomy",$term,"http://".$BIBSONOMY_LOGIN."@www.bibsonomy.org/api/posts?resourcetype=bibtex&search=$urlterm");
	
	$xmlp = simplexml_load_string($xml);
	
	$toreturn = array();
	
	if (!isset($xmlp) || $xmlp == ""){
	
		return $toreturn;
	}
	
	for ($i = 0; $i < sizeof($xmlp->posts->post); $i++) {
	
		$paperBib = $xmlp->posts->post[$i];
		
		//print_r($paperBib);
		
		$paper = (object)[];
		$paper->bibtexKey = strval($paperBib->bibtex['bibtexKey']);
		$paper->title = strval($paperBib->bibtex['title']);
		$paper->title = rtrim($paper->title, ".");
		$paper->authors = strval($paperBib->bibtex['author'].$paperBib->bibtex['authors']);
		$paper->year = strval($paperBib->bibtex['year']);
		$paper->venue = trim($paperBib->bibtex['publisher']." ".$paperBib->bibtex['booktitle'].$paperBib->bibtex['journal'].$paperBib->bibtex['conference']);
		
		$paper->tags = array();
		
		for ($x = 0; $x < sizeof($paperBib->tag); $x++) {
			$paper->tags[] = trim($paperBib->tag[$x]['name']);
		}
		
		$paper->urls = array();
		
		preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $paperBib->bibtex['url'], $matches);
		for ($x = 0; $x < sizeof($matches[0]); $x++) {
			$url = $matches[0][$x];
			$paper->urls[] = trim($url);
		}
		
		preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $paperBib->bibtex['misc'], $matches);
		for ($x = 0; $x < sizeof($matches[0]); $x++) {
			$url = $matches[0][$x];
			$paper->urls[] = trim($url);
		}
		
		$paper->urls = array_unique($paper->urls);
		
		$paper->source = "Bibsonomy";
		
		$toreturn[] = $paper;
	}

	// set category
	for ($i = 0; $i < sizeof($toreturn); $i++) {
	
		if (in_array("dblp",$toreturn[$i]->tags)){
			$toreturn[$i]->category = "Computer Science";
		}
	}
	
	return $toreturn;
}


function searchArXivMeta($term){
	
	$numfound = preg_match("/(\\d{4}\\.\\d{4}\\d*)/",$term, $matches);
	
	if ($numfound > 0){
		$arxivid = $matches[0];
		$results = searchArXiv($arxivid);
	}else{
		$results = searchArXiv($term);
	}
	return $results;
}

function searchArXiv($term){

	if ($term == "") die("must have keyword to search");
	
	$urlterm = urlencode($term);

	$xml = cachedWebRequest("arxiv",$term,"http://export.arxiv.org/api/query?start=0&max_results=10&search_query=$urlterm");

	$xmlp = simplexml_load_string($xml);
	
	$toreturn = array();

	if (!isset($xmlp) || $xmlp == ""){

		return $toreturn;
	}

	for ($i = 0; $i < sizeof($xmlp->entry); $i++) {

		$paperBib = $xmlp->entry[$i];
		
		//print_r($paperBib);
		
		$paper = (object)[];
		
		$numfound = preg_match("/(\\d{4}\\.\\d{4}\\d*)/",$paperBib->id, $matches);
		$arxivid = $matches[0];
		
		// to test if there was a valid arxivid
		if (strcmp($arxivid,"") == 0) continue;
		
		$paper->bibtexKey = "journals/corr/".strval($arxivid);
		$paper->title = strval($paperBib->title);
		$paper->abstract = strval($paperBib->summary);
		$paper->published = strval($paperBib->published);
		$paper->updated = strval($paperBib->updated);
		
		$authors = array();
		for ($a = 0; $a < sizeof($paperBib->author); $a++)
			$authors[] = strval($paperBib->author[$a]->name);
		$paper->authors = implode(" and ", $authors);
		
		$paper->year = date_parse($paperBib->published)['year'];
		$paper->venue = "arXiv";

		$paper->tags = array();
		for ($a = 0; $a < sizeof($paperBib->category); $a++)
			$paper->tags[] = strval($paperBib->category[$a]['term']);

		$paper->urls = array();
		
		$paper->urls[] = strval($paperBib->id);

		$paper->source = "arXiv";
		
		$toreturn[] = $paper;
	}

	// set category
	for ($i = 0; $i < sizeof($toreturn); $i++) {

		if (in_array("dblp",$toreturn[$i]->tags)){
			$toreturn[$i]->category = "Computer Science";
		}
	}
	
	//print_r($toreturn);

	return $toreturn;
}

ini_set('default_socket_timeout', 10);
function cachedWebRequest($tag, $key, $url){
	
	//print_r($url);
	
	$hash = base64url_encode($key);
	
	$file = "cache/".$tag."/".$hash;
	
	if (!file_exists($file)){
		
		@mkdir("cache");
		@mkdir("cache/".$tag);
		$data = file_get_contents($url);
		$myfile = fopen($file, "w") or unlink($file);
		fwrite($myfile, $data);
		fclose($myfile);
	}
	
	$data = file_get_contents($file) or unlink($file);;

	return $data;	
}

function base64url_encode($s) {
	return str_replace(array('+', '/'), array('-', '_'), base64_encode($s));
}

// function base64url_decode($s) {
// 	return base64_decode(str_replace(array('-', '_'), array('+', '/'), $s));
// }



function getPapers() {
	$sql = "select * FROM papers ORDER BY title";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		$papers = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;


		include("templates/papers.php");

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}



function getPaper($bibtexKey) {

	try{
		
		$results0 = searchLocal($bibtexKey, true);
		
		// check arxiv
		$numfound = preg_match("/(\\d{4}\\.\\d{4}\\d*)/",$bibtexKey, $matches);
		if ($numfound > 0){
			$arxivid = $matches[0];
			$results11 = searchArXivMeta($arxivid);
			//$results11 = searchBibsonomy($arxivid);
		}else{
			$results12 = searchBibsonomy("\"".$bibtexKey."\"");
		}
		$results1 = mergeResults(array($results11, $results12));
		
		//$results1 = searchBibsonomy("\"".$bibtexKey."\"");
		//$results2 = searchArXivMeta($bibtexKey);
		$results3 = searchCrossRef($bibtexKey);
		$results = mergeResults(array($results0, $results1, $results3));
				
		
		$paper = $results[0];
 		for ($r = 0; $r < sizeof($results); $r++) {
 			
 			if (strcmp($results[$r]->bibtexKey,$bibtexKey) == 0){
 				$paper = $results[$r];
 				break;
 			}
 		}
 		
//  		print_r($results);
//  		print_r($paper);
//  		//die();
 		
		
		// set the venue		
		if ($paper->metavenue == ""){
			if ($paper->venuekey){
				$paper->metavenue = getVenueForBibtexKey($paper->venuekey);
			}else{
				$paper->metavenue = getVenueForBibtexKey($paper->bibtexKey);
			}
		}
			

		
		return $paper;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}


function numOfVignettes($paperid){


	$sql  = <<<EOT
SELECT count(*) as count from vignettes where paperid=:paperid AND priv = 0
EOT;
	
	$db = getConnection();
	$stmt = $db->prepare($sql);
	$stmt->bindParam("paperid", $paperid);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	$db = null;

	return $result[0]->count;
}

function getVignettes($paperid) {
	//$sql = "select * FROM vignettes WHERE paperid=:id AND (priv = 0 OR userid = :userid)";

	$sql  = <<<EOT
SELECT * FROM vignettes
LEFT JOIN (SELECT sum(vote) as vote,paperid as vpaperid,userid as vuserid FROM votes GROUP BY vpaperid, vuserid) as votes
ON paperid=vpaperid AND userid=vuserid
LEFT JOIN users
ON vignettes.userid = users.userid
WHERE vignettes.paperid=:id AND (priv = 0 OR vignettes.userid = :userid)
ORDER BY vote DESC
EOT;

	//print_r(getUser());
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id", $paperid);
		$stmt->bindParam("userid", getcurrentuser()->userid);
		$stmt->execute();
		$vignettes = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		return $vignettes;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}

function getOneRandomVignette() {
	//$sql = "select * FROM vignettes WHERE paperid=:id AND (priv = 0 OR userid = :userid)";

	$sql  = <<<EOT
SELECT * FROM vignettes
LEFT JOIN (SELECT sum(vote) as vote,paperid as vpaperid,userid as vuserid FROM votes GROUP BY vpaperid, vuserid ORDER BY vote DESC) as votes
ON paperid=vpaperid AND userid=vuserid
LEFT JOIN users
ON vignettes.userid = users.userid
WHERE (priv = 0 OR vignettes.userid = :userid)
ORDER BY RAND()
LIMIT 1
EOT;

	//print_r(getUser());
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("userid", getcurrentuser()->userid);
		$stmt->execute();
		$vignettes = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		return $vignettes;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}


function getUsersVignettes($userid){
	
	
	$sql  = <<<EOT
SELECT * FROM vignettes
LEFT JOIN (SELECT sum(vote) as vote,paperid as vpaperid,userid as vuserid FROM votes GROUP BY vpaperid, vuserid) as votes
ON paperid=vpaperid AND userid=vuserid
LEFT JOIN users
ON vignettes.userid = users.userid
WHERE vignettes.userid = :userid AND ((anon = 0 AND priv = 0) OR vignettes.userid = :currentuserid)
ORDER BY vignettes.added DESC
EOT;
	
	//print_r(getUser());
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("userid", $userid);
		$stmt->bindParam("currentuserid", getcurrentuser()->userid);
		$stmt->execute();
		$vignettes = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
	
		return $vignettes;
	
	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}

function getRecentVignettes($userid, $limit){


	$sql  = <<<EOT
SELECT
text, paperid, vignettes.userid, priv, anon, vignettes.added, vignettes.edited, vote,
username,email,displayname, orcid
FROM vignettes
LEFT JOIN (SELECT sum(vote) as vote,paperid as vpaperid,userid as vuserid FROM votes GROUP BY vpaperid, vuserid) as votes
ON paperid=vpaperid AND userid=vuserid
LEFT JOIN users
ON vignettes.userid = users.userid
WHERE priv = 0 AND vote > -1
ORDER BY vignettes.edited DESC
LIMIT :resultlimit;
EOT;

	//print_r(getUser());
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		//$stmt->bindParam("currentuserid", getcurrentuser()->userid);
		$stmt->bindParam("resultlimit", $limit, PDO::PARAM_INT);
		$stmt->execute();
		$vignettes = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		return $vignettes;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}

function getPopularVignettes($userid, $limit){


	$sql  = <<<EOT
SELECT
text, paperid, vignettes.userid, priv, anon, vignettes.added, vignettes.edited, vote,
username,email,displayname, orcid
FROM vignettes
LEFT JOIN (SELECT sum(vote) as vote,paperid as vpaperid,userid as vuserid FROM votes GROUP BY vpaperid, vuserid) as votes
ON paperid=vpaperid AND userid=vuserid
LEFT JOIN users
ON vignettes.userid = users.userid
WHERE priv = 0 AND vote > 2
ORDER BY vignettes.edited DESC
LIMIT :resultlimit; 
EOT;

	//print_r(getUser());
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		//$stmt->bindParam("currentuserid", getcurrentuser()->userid);
		$stmt->bindParam("resultlimit", $limit, PDO::PARAM_INT);
		$stmt->execute();
		$vignettes = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		return $vignettes;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}

function getBestVignettes($userid, $limit){


	$sql  = <<<EOT
SELECT
text, paperid, vignettes.userid, priv, anon, vignettes.added, vignettes.edited, vote,
username,email,displayname, orcid
FROM vignettes
LEFT JOIN (SELECT sum(vote) as vote,paperid as vpaperid,userid as vuserid FROM votes GROUP BY vpaperid, vuserid) as votes
ON paperid=vpaperid AND userid=vuserid
LEFT JOIN users
ON vignettes.userid = users.userid
WHERE priv = 0 AND vote > -1
ORDER BY vote DESC
LIMIT :resultlimit;
EOT;

	//print_r(getUser());
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		//$stmt->bindParam("currentuserid", getcurrentuser()->userid);
		$stmt->bindParam("resultlimit", $limit, PDO::PARAM_INT);
		$stmt->execute();
		$vignettes = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		return $vignettes;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}


function getVignettePapers(){


	$sql  = <<<EOT
SELECT DISTINCT paperid FROM vignettes
WHERE priv = 0
EOT;

	//print_r(getUser());
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$vignettes = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		return $vignettes;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}

function getVenue($key){
	
	$sql  = <<<EOT
SELECT * FROM venues
WHERE id=:key OR doiprefix=:key
LIMIT 1;
EOT;
	
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("key", $key);
		$stmt->execute();
		$venue = $stmt->fetch(PDO::FETCH_OBJ);
		$db = null;
		
		if ($venue->id == ""){
			$venue->id = $venue->doiprefix;
		}
		
		return $venue;
	
	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
	
}

function getVenueForBibtexKey($bibtexKey){

	$sql  = <<<EOT
SELECT * FROM venues
WHERE 
	(id != "" AND :key LIKE CONCAT(id,'%'))
	OR 
	(doiprefix != "" AND :key LIKE CONCAT(doiprefix,'%'))
LIMIT 1;
EOT;


	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("key", $bibtexKey);
		$stmt->execute();
		$venue = $stmt->fetch(PDO::FETCH_OBJ);
		$db = null;
		
		if ($venue!= null && $venue->id == ""){
			$venue->id = $venue->doiprefix;
		}

		return $venue;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}

function getVenues(){

// 	$sql  = <<<EOT
// SELECT * FROM 
// (SELECT DISTINCT substring_index(paperid, '/', 2)as venueid, count(text) FROM vignettes WHERE paperid LIKE "journal%" OR paperid LIKE "conf/%") as allvenues
// LEFT JOIN venues ON id=venueid
// ORDER BY name DESC
// 

$sql  = <<<EOT
SELECT *, count(*) as numOfVignettes FROM
(SELECT paperid, venues.* FROM vignettes
INNER JOIN venues ON id != "" AND paperid LIKE CONCAT(id,'%') AND priv = 0
UNION
SELECT paperid, venues.* FROM vignettes
INNER JOIN venues ON doiprefix != "" AND paperid LIKE CONCAT(doiprefix,'%')) as t
GROUP BY id,doiprefix
ORDER BY numOfVignettes DESC
EOT;
	
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		//$stmt->bindParam("key", $key);
		$stmt->execute();
		$venues = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		
		for ($i = 0; $i < sizeof($venues); $i++) {
			if ($venues[$i]->id == ""){
				$venues[$i]->id = $venues[$i]->doiprefix;
			}
		}
		
		
		//print_r($venues);die();

		return $venues;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}

}

function getTopVenueVignettes($key, $year){

	
	$venue = getVenueForBibtexKey($key);
	
	
	$sql  = <<<EOT
SELECT * FROM vignettes
LEFT JOIN (SELECT sum(vote) as vote,paperid as vpaperid,userid as vuserid FROM votes GROUP BY vpaperid, vuserid) as votes
ON paperid=vpaperid AND userid=vuserid
LEFT JOIN users
ON vignettes.userid = users.userid
LEFT JOIN papers
ON vignettes.paperid = papers.bibtexKey
WHERE priv = 0 AND (
	((:doiprefix != "" AND (paperid LIKE CONCAT(:doiprefix,'%') OR venuekey LIKE CONCAT(:doiprefix,'%'))))
	OR ((:bibtexKey != "" AND (paperid LIKE CONCAT(:bibtexKey,'%') OR venuekey LIKE CONCAT(:bibtexKey,'%'))))
			)
ORDER BY vote DESC
EOT;
//OR LIKE CONCAT(:doiprefix,'%')	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		//$stmt->bindParam("currentuserid", getcurrentuser()->userid);
		$stmt->bindParam("bibtexKey", $venue->id);
		$stmt->bindParam("doiprefix", $venue->doiprefix);
		$stmt->execute();
		$vignettes = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		
		$years = array();
		
		for ($x = 0; $x < sizeof($vignettes); $x++) {
			
			$vignette = $vignettes[$x];
			
			$vignette->paper = getPaper($vignette->paperid);
			
			// I cannot figure out how to make the sql query do this!
			$alreadyseen = false;
			for ($y = 0; $y < sizeof($years[$vignette->paper->year]); $y++) {
			
				if ($years[$vignette->paper->year][$y]->paperid == $vignette->paperid)
					$alreadyseen = true;
					
			}
			
			//print_r($vignette->paper->year);
			if (!$alreadyseen)
				$years[$vignette->paper->year][] = $vignette;
			

		}
		
		//print_r($years);
		//die();
		
		return $years;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}

function getUserVignettePoints($userid){

	$sql  = <<<EOT
SELECT sum(vote)/count(vote) as count FROM (SELECT sum(vote) as vote FROM votes WHERE userid=:userid GROUP BY paperid) as avgvotes;
EOT;
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("userid", $userid);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		$base = $result[0]->count+0;
		
		$random = ((float)rand()/(float)getrandmax())-0.5;
		
		$base = $base + $random;
		
		return round($base, 3);

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}

function getMyVignettesVote($paperid, $userid) {

	$sql  = <<<EOT
SELECT * FROM votes
WHERE paperid=:id AND userid = :userid AND voteruserid = :voteruserid;
EOT;


	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id", $paperid);
		$stmt->bindParam("userid", $userid);
		$stmt->bindParam("voteruserid", getcurrentuser()->userid);
		$stmt->execute();
		$vote = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		if (sizeof($vote) > 0){
			return $vote[0]->vote;
		}else{
			return 0;
		}

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
	}
}


function addPaper($paper) {
	$sql = "INSERT INTO papers (id,title, authors) VALUES (:id, :title, :authors)";
	//print_r($paper->title);
	// 	$request = Slim::getInstance()->request();
	// 	$wine = json_decode($request->getBody());
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id", $paper->id);
		$stmt->bindParam("title", $paper->title);
		$stmt->bindParam("authors", $paper->authors);
		$stmt->execute();
		$paper->id = $db->lastInsertId();
		$db = null;

		return $paper->id;
	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//include("templates/error.php");
	}
}

function getComments($paperid, $summaryuserid) {
	
$sql  = <<<EOT
SELECT * FROM comments 
JOIN users ON comments.userid = users.userid 
WHERE paperid = :paperid
AND summaryuserid = :summaryuserid 
AND (visible IS NULL OR visible != 'false') 
ORDER BY comments.added
EOT;
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("paperid", $paperid);
		$stmt->bindParam("summaryuserid", $summaryuserid);

		$stmt->execute();
		$comments = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		
		return $comments;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//include("templates/error.php");
	}
}


function addComment($comment) {
	
	if (getcurrentuser()->userid < 0){
		echo "{}";
		die();
	}
	
	$sql = "INSERT INTO comments (paperid, summaryuserid, userid, text, added) VALUES (:paperid, :summaryuserid, :userid, :text, NOW())";

	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("paperid", $comment->paperid);
		$stmt->bindParam("text", $comment->text);
		$stmt->bindParam("summaryuserid", $comment->summaryuserid);
		$stmt->bindParam("userid", getcurrentuser()->userid);
		
		$stmt->execute();
		$db = null;
		
	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//include("templates/error.php");
	}
	
	// send email
	
	
	
	$paper = getPaper($comment->paperid);
	
	
	$summaryuser = getuserbyid($comment->summaryuserid);
	$responseuser = getcurrentuser();
	
	$commenttext = $comment->text;
	
	if ($summaryuser->email != $responseuser->email)
		if ($summaryuser->email_receive_comments == true)
			sendcommentemail($summaryuser->email, $summaryuser, $paper, $responseuser, $commenttext);
	
		
	$comments = getComments($comment->paperid, $comment->summaryuserid);
	
	$alreadysent = array();
	// don't send to this commenter
	$alreadysent[] = $summaryuser->email;
	$alreadysent[] = $responseuser->email;
	
	
	foreach ($comments as $comment){
		
		// check and save to prevent duplicates.
		if (in_array($comment->email, $alreadysent)) continue;
		$alreadysent[] = $comment->email;
			
		$commentuser = getuserbyid($comment->userid);
		
		if ($comment->email_receive_comments == true)
			sendcommentresponseemail($comment->email, $commentuser, $responseuser, $summaryuser, $paper, $commenttext);
		
	}
	
}


function sendcommentemail($email, $user, $paper, $responseuser, $commenttext){

	global $SITENAME, $DEFAULTBASEURL, $SITEEMAIL;

	$name = ($user->displayname)?$user->displayname." ($user->username)":$user->username;
	$responseusername = ($responseuser->displayname)?$responseuser->displayname." ($responseuser->username)":$responseuser->username;
	
	$body = <<<EOD
Hello $name,

There is a new comment on your $SITENAME summary of "$paper->title"

$responseusername said "$commenttext"

View it here: $DEFAULTBASEURL/paper?bibtexKey=$paper->bibtexKey#$user->username

This message was sent from $SITENAME. To change your email preferences log in and go to the settings page.

EOD;

	//ini_set('sendmail_from', $SITEEMAIL);
	mail($email, "New comment on your $SITENAME summary!", $body, "From: $SITENAME <noreply@shortscience.org>","-f noreply@shortscience.org");
}

function sendcommentresponseemail($email, $commentuser, $responseuser, $summaryuser, $paper, $commenttext){

	global $SITENAME, $DEFAULTBASEURL, $SITEEMAIL;

	$name = ($commentuser->displayname)?$commentuser->displayname." ($commentuser->username)":$commentuser->username;
	$responseusername = ($responseuser->displayname)?$responseuser->displayname. " ($responseuser->username)":$responseuser->username;

	$body = <<<EOD
Hello $name,

There is a new response to your comment on the summary of "$paper->title"

$responseusername said "$commenttext"

View it here: $DEFAULTBASEURL/paper?bibtexKey=$paper->bibtexKey#$summaryuser->username

This message was sent from $SITENAME. To change your email preferences log in and go to the settings page.

EOD;

	//ini_set('sendmail_from', $SITEEMAIL);
	mail($email, "New response to your comment on $SITENAME!", $body, "From: $SITENAME <noreply@shortscience.org>", "-f noreply@shortscience.org");
}

function delComment($comment) {
	
	if (getcurrentuser()->userid < 0){
		echo "{}";
		die();
	}

	$sql = "UPDATE comments SET visible='false' WHERE userid=:userid and id = :commentid ";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("commentid", $comment->commentid);
		$stmt->bindParam("userid", getcurrentuser()->userid);
		$stmt->execute();
		$db = null;

		return;
	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//include("templates/error.php");
	}
}

function addVignette($vignette) {
	$sql = "INSERT INTO vignettes (text, userid, paperid, edited, priv, anon) VALUES (:text, :userid, :paperid, NOW(), :priv, :anon) ON DUPLICATE KEY UPDATE text=:text,  edited=NOW(), priv=:priv, anon=:anon";

	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("text", $vignette->text);
		$stmt->bindParam("userid", $vignette->userid);
		$stmt->bindParam("paperid", $vignette->paperid);
		$stmt->bindParam("priv", $vignette->priv);
		$stmt->bindParam("anon", $vignette->anon);
		$stmt->execute();
		//$vignette->id = $db->lastInsertId();
		
		//print_r($vignette->id); die();
		
		$db = null;
		
		
		
		
		// vote!!
		$vote = (object)[];
		$vote->vote = 1;
		$vote->paperid = $vignette->paperid;
		$vote->userid = $vignette->userid;
		voteVignette($vote);
		
		
		//echo json_encode($vignette);

	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		die();
		//include("templates/error.php");
	}
}

function voteVignette($vote){

	if (getcurrentuser()->userid < 0){
		echo "{}";
		die();
	}

	$vote->vote = min($vote->vote,1);
	$vote->vote = max($vote->vote,-1);
	
	//print_r($vote);
		
	$sql = "INSERT INTO votes (vote, userid, paperid, voteruserid, edited) VALUES (:vote, :userid, :paperid, :voteruserid, NOW()) ON DUPLICATE KEY UPDATE vote=:vote, edited=NOW()";

	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("vote", $vote->vote);
		$stmt->bindParam("userid", $vote->userid);
		$stmt->bindParam("paperid", $vote->paperid);
		$stmt->bindParam("voteruserid", getcurrentuser()->userid);
		$stmt->execute();
		$db = null;
		
	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		die();
		//include("templates/error.php");
	}
}


function delVignette($vignette) {
	$sql = "DELETE FROM vignettes WHERE userid=:userid and paperid = :paperid";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("userid", $vignette->userid);
		$stmt->bindParam("paperid", $vignette->paperid);
		$stmt->execute();
		$db = null;

		return;
	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//include("templates/error.php");
	}
}


function getStats() {

	$sql  = <<<EOT
SELECT "Number of Public Summaries",count(*) FROM vignettes WHERE priv = 0
UNION ALL
SELECT "Number of Private Summaries", count(*) FROM vignettes WHERE priv = 1
UNION ALL
SELECT "Number of User Accounts", count(*)  FROM users
UNION ALL
SELECT "Number of Votes", count(*)  FROM votes
EOT;
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);

		$stmt->execute();
		$stats = $stmt->fetchAll();
		$db = null;

		return $stats;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//include("templates/error.php");
	}
}




function getarxivid($paper){
	
	for ($x = 0; $x < sizeof($paper->urls); $x++) {
		$url = $paper->urls[$x];
		$parse = parse_url($url);
		$urlhost = $parse['host'];
		if ($urlhost == "arxiv.org"){
			$numfound = preg_match("/(\\d{4}\\.\\d{4}\\d*)/",$url, $matches);
			$arxivid = $matches[0];
			return $arxivid;
		}
	}
}




?>