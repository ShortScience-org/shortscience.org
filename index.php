<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'config.php';
require 'db.php';
require 'auth.php';
require 'functions.php';
require 'qa.php';
require 'logging.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];


$configuration['notFoundHandler'] = function ($c) {
	return function ($request, $response) use ($c) {
		
		header("HTTP/1.1 404 Not Found");
		$message = "Page not found!";
		include("templates/error.php");
		die();
	};
};


$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);


$app->get('/', function (Request $request, Response $response) {
	
	$currentuser = getcurrentuser();
	
	if ($currentuser->userid == -1 && empty($_GET)){
	    $cache_file = "./cache-index.html";
	    //print("cache");
	    if (file_exists($cache_file)) {
	        $cached = file_get_contents($cache_file);
	        if (strlen($cached) > 500){
	            print($cached);
    	        print("<small>Cache was last modified: " . date ("F d Y H:i:s.", filemtime($cache_file)) ."</small>");
    	        die();
	        }
	    }
	}
	
	$tab = $request->getParam('tab');
	$sections = $request->getParam('s');
	
	$valid_sections= array("cs","bio", "ph");
	$sections = implode(",",array_intersect($valid_sections,explode(',',$sections)));
	
	$page = 1*$request->getParam('page', 0);
	$page = max($page,1);
	//print $page;
	if ($tab == "recent"){
		$vignettes = getRecentVignettes($user->userid, 10, $page, $sections);
	}else if($tab == "best"){
		$vignettes = getBestVignettes($user->userid, 10, $page, $sections);
	}else if($tab == "popularweek"){
		$vignettes = getPopularVignettes($user->userid, 10, $page, $sections, $howmanydays=7);
	}else{
		$vignettes = getPopularVignettes($user->userid, 10, $page, $sections, $howmanydays=1);
	}
		
	include("templates/home.php");
	die();
});

$app->get('/welcome', function (Request $request, Response $response) {

	include("templates/welcome.php");
	die();
});

$app->get('/search', function (Request $request, Response $response) {
	$term = $request->getParam('term');
	
	$results = performSearch($term);
	
	//print_r($results);
	
	// don't index search results
	$extraheader = '<meta name="robots" content="noindex, follow">';
	
	include("templates/papers.php");
	die();
});

$app->get('/internalsearch', function (Request $request, Response $response) {
	$q = $request->getParam('q');


	//print_r($results);

	// don't index search results
	$extraheader = '<meta name="robots" content="noindex, follow">';

	include("templates/internalsearch.php");
	die();
});

// $app->get('/papers', function (Request $request, Response $response) {
// 	getPapers();
// });

$app->get('/paper', function (Request $request, Response $response) {
	
	$bibtexKey = $request->getParam('bibtexKey');
	
	$code = $request->getParam('code');
	
	$authorfocus = $request->getParam('a');
	
	$paper = getPaper($bibtexKey);
	
	//When no paper is found redirect
	if (!isset($paper->bibtexKey)){
	
		header("HTTP/1.1 303 See Other");
		header("Location: ./search?term=".$bibtexKey);
		die();
	}
	
	

	$currentuser = getcurrentuser();
	
	if ($paper->bibtexKey != "" && $bibtexKey != "" && $bibtexKey != $paper->bibtexKey){
		
		$vignettes = getVignettes($bibtexKey);
		
		// if there are no entries here then redirect to the better page
		// otherwise just continue with the one that was linked
		if (sizeof($vignettes) == 0){
			
			header("HTTP/1.1 303 See Other");
			header("Location: ./paper?bibtexKey=".$paper->bibtexKey);
			
			//header("Refresh: 0; url=./paper?bibtexKey=".$paper->bibtexKey);
			die();
		}else{
			$paper->bibtexKey = $bibtexKey;
		}
	}
	
	$vignettes = getVignettes($paper->bibtexKey, $code=$code);

	
	for ($v = 0; $v < sizeof($vignettes); $v++){
	
		$vignette = $vignettes[$v];
	
		$vignette->myvote = getMyVignettesVote($vignette->paperid, $vignette->userid);
	
		if (!isset($vignette->vote)) $vignette->vote = 0;
	
		//print_r($vignette);
	
		if($vignette->userid == $currentuser->userid){
			
			$myvignette = $vignette;
			//print_r($myvignette);
		}
	}
	
	if (!isset($myvignette)){
		
		$myvignette = (object)[];
		$myvignette->paperid = $paper->bibtexKey;
		$myvignette->userid=getcurrentuser()->userid;
		$myvignette->username=getcurrentuser()->username;
		$myvignette->displayname=getcurrentuser()->displayname;
		$myvignette->email=getcurrentuser()->email;
		$myvignette->vote=0;
		$myvignette->myvote=0;
	}
	
	// if no summaries then don't be in the search results
	if (sizeof($vignettes) == 0){
		header("HTTP/1.1 203 No Summaries Yet");
		$extraheader = '<meta name="robots" content="noindex, follow">';
	}
		
	include("templates/paper.php");
	
	logVisit($paper->bibtexKey);
	die();
});

$app->get('/random', function (Request $request, Response $response) {

	$currentuser = getcurrentuser();
		
	$vignettes = getOneRandomVignette();
	
	//print_r($vignettes);
	
	$bibtexKey = $vignettes[0]->paperid;
	
	header("HTTP/1.1 303 See Other");
	header("Location: ./paper?bibtexKey=".$bibtexKey);
	//header("Refresh: 0; url=$DEFAULTBASEURL/paper?bibtexKey=$bibtexKey");
	die();

});

$app->post('/vignette', function (Request $request, Response $response) {
	
	$vignette = (object)[];
	$vignette->paperid = $request->getParam('paperid');
	$vignette->text = $request->getParam('text');
	$vignette->priv = $request->getParam('priv');
	$vignette->anon = $request->getParam('anon');
	$vignette->userid=getcurrentuser()->userid;
	
	addVignette($vignette);
	
	echo "{}";
	die();
});

$app->delete('/vignette', function (Request $request, Response $response) {

	$userid = $request->getParam('userid');
	
	if (getcurrentuser()->userid != $userid){
		http_response_code(401);
		die(); 
	}
	
	$vignette = (object)[];
	$vignette->paperid = $request->getParam('paperid');
	$vignette->userid = $userid;

	delVignette($vignette);
	
	echo "{}";
	die();
});

$app->post('/vote', function (Request $request, Response $response) {

	$vote = (object)[];
	$vote->vote = $request->getParam('vote');
	$vote->paperid = $request->getParam('paperid');
	$vote->userid = $request->getParam('userid');

	voteVignette($vote);
	
	echo "{}";
	die();
});

$app->post('/comment', function (Request $request, Response $response) {

    $comment = (object)[];
    $comment->text = $request->getParam('text');
    $comment->paperid = $request->getParam('paperid');
    $comment->summaryuserid = $request->getParam('summaryuserid');

    addComment($comment);

    echo "{}";
    die();
  });


$app->delete('/comment', function (Request $request, Response $response) {

    $comment = (object)[];
    $comment->commentid = $request->getParam('commentid');

    delComment($comment);

    echo "{}";
    die();
  });

$app->get('/user', function (Request $request, Response $response) {
	$name = $request->getParam('name');
	$tab = $request->getParam('tab');

	$currentuser = getcurrentuser();
	
	if ($name == "" && $currentuser->userid == -1){
		header("HTTP/1.1 303 See Other");
		header("Location: ./login");
		//header("Refresh: 0; url=$DEFAULTBASEURL/login");
		die();
	}else if ($name == ""){
		header("HTTP/1.1 303 See Other");
		header("Location: ./user?name=".$currentuser->username);
		//header("Refresh: 0; url=$DEFAULTBASEURL/user?name=".$currentuser->username);
		die();
	}

	$user = getuser($name);
	
	$vignettes = getUsersVignettes($user->userid);
	
	if ($currentuser->userid == $user->userid){
	
		$likedvignettes = getUsersLikedVignettes($user->userid);
		$dislikedvignettes = getUsersDisLikedVignettes($user->userid);
	}
	
	$title = htmlspecialchars(($user->displayname)?$user->displayname:$user->username)."'s profile";
	
	if ($user->description == ""){
		$description = "Here are summaries of research papers by user ".htmlspecialchars(($user->displayname)?$user->displayname:$user->username);
	}else{
		$description = htmlspecialchars($user->description);
	}
	
	// we don't want this overview page showing up
	$extraheader = '<meta name="robots" content="noindex, follow">';
		
	include("templates/user.php");
	die();
});

$app->get('/settings', function (Request $request, Response $response) {
	$name = $request->getParam('name');
	$tab = $request->getParam('tab');

	$currentuser = getcurrentuser();

	if ($name == "" && $currentuser->userid == -1){
		header("HTTP/1.1 303 See Other");
		header("Location: ./login");
		die();
	}

	$user = $currentuser;

	$title = "Settings";

	// we don't want this overview page showing up
	$extraheader = '<meta name="robots" content="noindex, follow">';

	include("templates/settings.php");
	die();
});

$app->get('/venue', function (Request $request, Response $response) {
	$key = $request->getParam('key');
	$year = $request->getParam('year');

	if ($key == ""){
		
		$venues = getVenues();
		
		$title = "All venues with summaries";
		$description = "Browse papers with summaries from these conferences.";
		include("templates/allvenues.php");
		
	}else{
		$years = getTopVenueVignettes($key, $year);
		

		// if no year pick most recent year
		krsort($years);
		reset($years);
		$defaultyear = key($years);
		if ($year == "")
			$year = $defaultyear;
		
			
		$venue = getVenue($key);
		if ($venue == null){
			$venue = (object)[];
			$venue->name = $years[$defaultyear][0]->paper->venue." - ".$key;
			$venue->id = $key;
		}
			
			

		ksort($years);
		//$year = $years->paper->year;
		$vignettes = $years[$year];

		//print_r($years[$defaultyear][0]->a);die();
		
		//$title = "Summaries from ".$venue->name;
		include("templates/venue.php");
	}
	die();
});


$app->get('/users', function (Request $request, Response $response) {

	if ($key == ""){

		$users = getUsers();

		$title = "All users";
		$description = "Browse users with summaries";
		include("templates/allusers.php");

	}
	die();
});

$app->post('/user', function (Request $request, Response $response) {
	
	$useredit = (object)[];
	$useredit->username = $request->getParam('username');
	$useredit->displayname = $request->getParam('displayname');
	$useredit->description = $request->getParam('description');
	$useredit->password = $request->getParam('password');
	$useredit->orcid = $request->getParam('orcid');
	$useredit->email_receive_comments = $request->getParam('email_receive_comments') == "true";
	
	
	if ($useredit->username != getcurrentuser()->username){
		die("Hello");
	}
	
	$user = getuser($useredit->username);
	
	editUser($user, $useredit);
	
	echo "{}";
	die();
});

$app->get('/export', function (Request $request, Response $response) {
	//$name = $request->getParam('name');

	$currentuser = getcurrentuser();

	if ($currentuser->userid == -1){
		header("HTTP/1.1 303 See Other");
		header("Location: ./login");
		//header("Refresh: 0; url=$DEFAULTBASEURL/login");
		die();
	}

	$vignettes = getUsersVignettes($currentuser->userid);

	include("templates/vignettecsv.php");
	die();
});

$app->get('/install', function (Request $request, Response $response) {
	//createDB();
});



$app->get('/login', function (Request $request, Response $response) {
	
	$currentuser = getcurrentuser();
	
	include("templates/login.php");
	die();
});

$app->get('/logout', function (Request $request, Response $response) {
	
	logoutcookie();
	header("HTTP/1.1 303 See Other");
	header("Location: ./");
	//header("Refresh: 0; url=./");
	die();
});

$app->post('/login', function (Request $request, Response $response) {

	$login = (object)[];
	$login->loginname = $request->getParam('loginname');
	$login->password = $request->getParam('password');
	
	$returnto = $request->getParam('returnto');
	
	$loginresult = takelogin($login);
	
	//print_r($loginresult);die();
	if ($loginresult->message == "No user"){
		header("HTTP/1.1 303 See Other");
		header("Location: ./signup");
	}else if ($loginresult->message != ""){
		include("templates/login.php");
	}else if ($returnto != ""){
		
		header("HTTP/1.1 303 See Other");
		header("Location: ./".$returnto);
		//header("Refresh: 0; url=".$returnto);
		die();
	}else{
		header("HTTP/1.1 303 See Other");
		header("Location: ./user");
		//header("Refresh: 0; url=./user");
		die();
	}
	die();
});

$app->get('/signup', function (Request $request, Response $response) {

	include("templates/signup.php");
	die();
});

$app->get('/recover', function (Request $request, Response $response) {

	include("templates/recover.php");
	die();
});

$app->get('/confirmrecover', function (Request $request, Response $response) {

	$confirm = (object)[];
	$confirm->email = $request->getParam('email');
	$confirm->psecret = $request->getParam('psecret');
	
	$result = validaterecoveremail($confirm->email, $confirm->psecret);
	
	if ($result){
		header("HTTP/1.1 303 See Other");
		header("Location: ./user");
		//header("Refresh: 0; url=./user");
	}else{
		$message = "Your reset link may have expired!";
		include("templates/error.php");
	}
	die();
});

$app->post('/recover', function (Request $request, Response $response) {

	$recover = (object)[];
	$recover->email = $request->getParam('email');
	
	$recoverresult = recoverUser($recover);
	
	include("templates/recover.php");
	die();
});

$app->post('/signup', function (Request $request, Response $response) {

	$signup = (object)[];
	$signup->username = $request->getParam('username');
	$signup->email = $request->getParam('email');
	$signup->displayname = $request->getParam('displayname');
	$signup->password = $request->getParam('password');
	
	$signupresult = addUser($signup);
	
	include("templates/signup.php");
	die();
});

$app->get('/confirm', function (Request $request, Response $response) {
	
	$confirm = (object)[];
	$confirm->username = $request->getParam('username');
	$confirm->psecret = $request->getParam('psecret');
	
	$result = validatesignupemail($confirm->username, $confirm->psecret);
	
	if ($result){
		header("HTTP/1.1 303 See Other");
		header("Location: ./user");
		//header("Refresh: 0; url=./user");
	}else{
		$message = "Please contact us!";
		include("templates/error.php");
	}
	die();
});

$app->get('/sitemap.xml', function (Request $request, Response $response) {

	$vignettes = getVignettePapers();
	$venues = getVenues();
	
	header('Content-Type: text/xml');
	include("templates/sitemapxml.php");
	die();
});

$app->get('/rss-generate', function (Request $request, Response $response) {
	
	ob_start();
	$vignettes = getRecentVignettes(0, 100, 1, "");
	header('Content-Type: text/xml');
	include("templates/rss.php");
	$contents = ob_get_flush();
	$contents = stripInvalidXml($contents);
	$xml = new SimpleXMLElement($contents);
	file_put_contents("rss.xml",$xml->asXML());
	
	ob_start();
	$vignettes = getRecentVignettes(0, 1000000, 1, "");
	header('Content-Type: text/xml');
	include("templates/rss.php");
	$contents = ob_get_flush();
	$contents = stripInvalidXml($contents);
	$xml = new SimpleXMLElement($contents);
	file_put_contents("rss-all.xml",$xml->asXML());
	
	ob_start();
	$vignettes = getRecentVignettes(0, 1000000, 1, "");
	$full = True;
	header('Content-Type: text/xml');
	include("templates/rss.php");
	$contents = ob_get_flush();
	$contents = stripInvalidXml($contents);
	$xml = new SimpleXMLElement($contents);
	file_put_contents("rss-full.xml",$xml->asXML());
	
	die();
});
	

$app->get('/about', function (Request $request, Response $response) {

	include("templates/about.php");
	die();
});



/// QA stuff

$app->post('/newquestion', function (Request $request, Response $response) {

	$question = (object)[];
	$question->paperid = $request->getParam('paperid');
	$question->text = $request->getParam('text');
	$question->category = $request->getParam('category');

	addQuestion($question);

	echo "{}";
	die();
});

$app->post('/newanswer', function (Request $request, Response $response) {

	$answer = (object)[];
	$answer->questionid = $request->getParam('questionid');
	$answer->text = $request->getParam('text');

	addAnswer($answer);

	echo "{}";
	die();
});

// $app->post('/editquestion', function (Request $request, Response $response) {

// 	$question = (object)[];
// 	$question->questionid = $request->getParam('questionid');
// 	$question->status = $request->getParam('status');
// 	$question->category = $request->getParam('category');

// 	editQuestion($question);

// 	echo "{}";
// 	die();
// });

$app->get('/questions', function (Request $request, Response $response) {

	$paperid = $request->getParam('paperid');
	
	$questions = getQuestions($paperid);
	
	for ($i = 0; $i < sizeof($questions); $i++) {
		$questions[$i]->answers = getAnswers($questions[$i]->id);
	}
	
	
	echo json_encode($questions);
	die();
});

$app->get('/visits', function (Request $request, Response $response) {
		
	$bibtexKey = $request->getParam('bibtexKey');
	$report = $request->getParam('report');
	$userid = $request->getParam('userid');
	
	$previousdays = 7;
	if ($report == "1m"){
		$previousdays = 30;
	}
	
	if ($bibtexKey){
		$views = getVisitCounts($bibtexKey, $previousdays);
	}
	
	if ($userid){
		$views = getVisitCountsUser($userid, $previousdays);
	}
	
	//print_r($views);
	
	include("templates/visits.php");
	die();
});


$app->run();





