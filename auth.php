<?php 


// function reditectIfNotLoggedIn(){
	
// 	if (getcurrentuser()->userid == -1)
// 		header("Refresh: 0; url=./login");
// }


function takelogin($login){


	$loginresult = (object)[];
	$loginresult->message = "Incorrect";

	$realuser = getUser($login->loginname);
	
	
	$hash = md5($realuser->secret . $login->password . $realuser->secret);
	//print_r($hash);
	//die();
	
	$user = fetchUser($login->loginname, $hash);

	
	if (isset($user) && $user->hash == $realuser->hash){
		$loginresult->message = "";
		logincookie($user->username, $hash, true);
		return $loginresult;
	}
	
	return $loginresult;
}



$CURUSER;
function getcurrentuser(){
	global $CURUSER;
	
	if (isset($CURUSER))
		return $CURUSER;
	else{
		
		$CURUSER = userfromcookie();
		
		if (!isset($CURUSER)){

			logoutcookie();
			
			//login as guest
			$user = (object)[];
			$user->userid = -1;
			$user->username = "guest";
			$user->email = "none";
			$CURUSER = $user;
		}
		
		return $CURUSER;
		
	}
}


function getuser($loginname){

	$sql  = <<<EOT
SELECT * FROM users
WHERE username = :loginname || email = :loginname;
EOT;

	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("loginname", $loginname);
		$stmt->bindParam("hash", $hash);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		$db = null;

		$user->username = strtolower($user->username);
		
		return $user;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
		die();
	}

	return $user;
}

function getuserbyid($userid){

	$sql  = <<<EOT
SELECT * FROM users
WHERE userid = :userid;
EOT;

	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("userid", $userid);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		$db = null;

		$user->username = strtolower($user->username);

		return $user;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
		die();
	}

	return $user;
}



function fetchUser($loginname, $hash){

	$sql  = <<<EOT
SELECT * FROM users
WHERE (username = :loginname || email = :loginname) AND hash = :hash;
EOT;

	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("loginname", $loginname);
		$stmt->bindParam("hash", $hash);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		$db = null;

		$user->username = strtolower($user->username);
		
		return $user;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		include("templates/error.php");
		die();
	}

	return $user;
}


function editUser($user, $useredit){
	
	
	
	$numfound = preg_match("/\\d{4}-\\d{4}-\\d{4}-\\d{4}/",$useredit->orcid, $matches);
	
	if ($useredit->orcid != "" && $numfound != 1){
		http_response_code(400);
		print(json_encode("Invalid ORCID, Must look like 0000-0000-0000-0000"));
		die();
	}
	
	
	
	// edit displayname and description
	
	
	$sql  = <<<EOT
UPDATE users
SET displayname=:displayname, description=:description, user_edited=NOW(), orcid=:orcid, email_receive_comments=:email_receive_comments
WHERE userid = :userid;
EOT;
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("displayname", $useredit->displayname);
		$stmt->bindParam("description", $useredit->description);
		$stmt->bindParam("orcid", $useredit->orcid);
		$stmt->bindParam("email_receive_comments", $useredit->email_receive_comments);
		$stmt->bindParam("userid", $user->userid);
		$stmt->execute();
		$db = null;
	
	} catch(PDOException $e) {
		$message = $e->getMessage();
		
		http_response_code(400);
		print(json_encode($message));
		die();
	}	
	
	
	
	
	
	// edit password
	
	if ($useredit->password != ""){

		$secret = md5(mksecret());
		$wantpasshash = md5($secret . $useredit->password . $secret);
		
		
		
		$sql  = <<<EOT
UPDATE users
SET hash=:hash, secret=:secret
WHERE userid = :userid;
EOT;
		
		try {
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("hash", $wantpasshash);
			$stmt->bindParam("secret", $secret);
			$stmt->bindParam("userid", $user->userid);
			$stmt->execute();
			$db = null;
		
		} catch(PDOException $e) {
			http_response_code(400);
			print(json_encode($message));
			die();
		}
	}
	
}




function addUser($signup){
	
	$signupresult = (object)[];
	$signupresult->message = "Error";
	$signupresult->success = false;
	
	$signup->username = strtolower($signup->username);

	if (empty ( $signup->username ) || empty ( $signup->password ) || empty ( $signup->email )){
		$signupresult->message = "Don't leave any fields blank.";
		return $signupresult;
	}
	
	if (strlen ( $signup->username ) > 20){
		$signupresult->message = "Sorry, username is too long (max is 20 chars)";
		return $signupresult;
	}
	
	if (strlen ( $signup->password ) < 6){
		$signupresult->message = "Sorry, password is too short (min is 6 chars)";
		return $signupresult;
	}
	
	if (strlen ( $signup->password ) > 40){
		$signupresult->message = "Sorry, password is too long (max is 40 chars)";
		return $signupresult;
	}
	
	if ($signup->password == $signup->username){
		$signupresult->message = "Sorry, password cannot be same as user name.";
		return $signupresult;
	}
	
	if (! validemail ( $signup->email )){
		$signupresult->message = "Our LSTM based RNN refuses to accept that email address. Please use a valid one or email us!";
		return $signupresult;
	}
	
	if (! validusername ( $signup->username )){
		$signupresult->message = "Invalid username. The username can only contain alphanumeric characters and no spaces.";
		return $signupresult;
	}

	if ( emailexists($signup->email) ){
		$signupresult->message = "The e-mail address is already in use.";
		
		return $signupresult;
	}
	
	if ( usernameexists($signup->username) ){
		$signupresult->message = "The username is already in use.";
		return $signupresult;
	}
	
	$secret = md5(mksecret());
	$wantpasshash = md5($secret . $signup->password . $secret);
	$editsecret = md5(mksecret());

		
	$sql  = <<<EOT
INSERT INTO users (username, hash, secret, editsecret, email)
VALUES (:username, :hash, :secret, :editsecret, :email);
EOT;

	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("username", $signup->username);
		$stmt->bindParam("hash", $wantpasshash);
		$stmt->bindParam("secret", $secret);
		$stmt->bindParam("editsecret", $editsecret);
		$stmt->bindParam("email", $signup->email);
		$stmt->execute();
		$db = null;
		
		
		sendsignupemail($signup->username, $signup->email, $editsecret);
		
		$signupresult->message = "";
		$signupresult->success = true;
		return $signupresult;

	} catch(PDOException $e) {
		//print_r($e);
		$message = $e->getMessage();
		include("templates/error.php");
		die();
	}

	return $signupresult;
}


function sendsignupemail($username, $email, $editsecret){
	
	global $SITENAME, $DEFAULTBASEURL, $SITEEMAIL;
	
$psecret = md5($editsecret);
	
$body = <<<EOD
Welcome to $SITENAME! You are Awesome!
	
Someone from the IP address {$_SERVER["REMOTE_ADDR"]} wants an account.
	
To confirm your user registration, click this link:
	
$DEFAULTBASEURL/confirm?username=$username&psecret=$psecret

We hope you enjoy the site. Feel free to contact us!
		
-ShortScience.org Team
	
EOD;
//$email = "ieee8023@localhost";
	mail($email, "$SITENAME user registration confirmation", $body, "From: $SITENAME <noreply@shortscience.org>", "-f noreply@shortscience.org");
	
}

function validatesignupemail($username, $psecret){
	
	$db = getConnection();
	$stmt = $db->prepare("SELECT editsecret, hash from users where username=:username");
	$stmt->bindParam("username", $username);
	$stmt->execute();
	$user = $stmt->fetch(PDO::FETCH_OBJ);
	$db = null;
	
	if ($psecret == md5($user->editsecret)){
		
		$db = getConnection();
		$stmt = $db->prepare("UPDATE users SET verifiedemail=1, editsecret='',user_edited=NOW() WHERE username=:username AND verifiedemail=0;");
		$stmt->bindParam("username", $username);
		$stmt->execute();
		$db = null;
		
		
		logincookie($username, $user->hash, true);
		
		return true;
		
	}else{
		return false;
	}
	
}


function recoverUser($recover){

	$recoverresult = (object)[];
	$recoverresult->message = "Error";
	$recoverresult->success = false;
	
	
	if (! validemail ( $recover->email )){
		$recoverresult->message = "Our LSTM based RNN refuses to accept that email address. Please use a valid one or email us!";
		return $recoverresult;
	}

	if (!emailexists($recover->email) ){
	
		$recoverresult->message = "";
		$recoverresult->success = true;
		return $recoverresult;
	}
	
	
	$editsecret = md5(mksecret());
	
	
	$sql  = <<<EOT
UPDATE users
SET editsecret=:editsecret
WHERE email=:email;
EOT;
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("editsecret", $editsecret);
		$stmt->bindParam("email", $recover->email);
		$stmt->execute();
		$db = null;
	
	
		sendrecoveremail($recover->email, $editsecret);
	
	
	} catch(PDOException $e) {
		//print_r($e);
		$message = $e->getMessage();
		include("templates/error.php");
		die();
	}
	
	
	
	$recoverresult->message = "";
	$recoverresult->success = true;
	return $recoverresult;
	
}


function sendrecoveremail($email, $editsecret){

	global $SITENAME, $DEFAULTBASEURL, $SITEEMAIL;

	$psecret = md5($editsecret);

	$body = <<<EOD
Welcome to $SITENAME! You are Awesome!

Someone from the IP address {$_SERVER["REMOTE_ADDR"]} requested a password reset.

To reset your password click this link:

$DEFAULTBASEURL/confirmrecover?email=$email&psecret=$psecret

We hope you enjoy the site. Feel free to contact us!

-ShortScience.org Team

EOD;
	//$email = "ieee8023@localhost";
	mail($email, "$SITENAME password reset", $body, "From: $SITENAME <noreply@shortscience.org>","-f noreply@shortscience.org");

}

function validaterecoveremail($email, $psecret){

	$db = getConnection();
	$stmt = $db->prepare("SELECT editsecret, username, hash from users where email=:email");
	$stmt->bindParam("email", $email);
	$stmt->execute();
	$user = $stmt->fetch(PDO::FETCH_OBJ);
	
	$user->username = strtolower($user->username);
	
	$db = null;

	if ($psecret == md5($user->editsecret)){

		$db = getConnection();
		$stmt = $db->prepare("UPDATE users SET editsecret='',user_edited=NOW() WHERE username=:username;");
		$stmt->bindParam("username", $user->username);
		$stmt->execute();
		$db = null;

		logincookie($user->username, $user->hash, true);

		return true;

	}else{
		return false;
	}

}


function userfromcookie(){
	
	$username = $_COOKIE["u"];
	$hash = $_COOKIE["p"];
	
	if ($username != "" && $hash != ""){
		$user = fetchUser($username, $hash);

		if (!isset($user) || ($user->hash != $hash) || ($user->username != $username)){
			logoutcookie();
			return;
		}
		
		return $user;
	}
	
	logoutcookie();
	return;
}


function logincookie($username, $passhash, $updatedb = 1, $expires = 0x7fffffff)
{
	setcookie("u", $username, $expires, "/");
	setcookie("p", $passhash, $expires, "/");

	if ($updatedb){
		
		$db = getConnection();
		$stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE username = :username");
		$stmt->bindParam("username", $username);
		$stmt->execute();
		$db = null;	
	}
}

function logoutcookie() {
	setcookie("u", "", 0x7fffffff, "/");
	setcookie("p", "", 0x7fffffff, "/");
}

// function loggedinorreturn() {
// 	global $DEFAULTBASEURL;
// 	if (!getUser()) {
// 		header("Refresh: 0; url=".$DEFAULTBASEURL."/login?returnto=" . urlencode($_SERVER["REQUEST_URI"]));
// 		exit();
// 	}
// }



function usernameexists($username){
	
	
	$db = getConnection();
	$stmt = $db->prepare("select count(*) as count from users where username=:username");
	$stmt->bindParam("username", $username);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	$db = null;
	
	return (isset($result) && $result[0]->count > 0);
}

function emailexists($email){


	$db = getConnection();
	$stmt = $db->prepare("select count(*) as count from users where email=:email");
	$stmt->bindParam("email", $email);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	$db = null;

	return (isset($result) && $result[0]->count > 0);
}

// function emailnotverified($email){


// 	$db = getConnection();
// 	$stmt = $db->prepare("SELECT count(*) as count FROM users WHERE email=:email AND verifiedemail=0");
// 	$stmt->bindParam("email", $email);
// 	$stmt->execute();
// 	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
// 	$db = null;

// 	return (isset($result) && $result[0]->count > 0);
// }




function mksecret($len = 20) {
	$ret = "";
	for ($i = 0; $i < $len; $i++)
		$ret .= chr(mt_rand(0, 255));
		return $ret;
}


function validemail($email) {
	return preg_match('/^[+\w.-]+@([\w.-]+\.)+[a-z]{2,6}$/is', $email);
}


function validusername($username)
{
	if ($username == "")
		return false;

		// The following characters are allowed in user names
		$allowedchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		for ($i = 0; $i < strlen($username); ++$i)
			if (strpos($allowedchars, $username[$i]) === false)
				return false;

				return true;
}



?>