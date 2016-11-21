<?php 

$isinit = false;
$dbh;

function getConnection() {
	global $isinit, $dbh, $DBCONFIG;

	//if ($isinit) echo "true\n"; else echo "false\n";

	if (!$isinit){
		//echo "connected to db\n";
		$isinit = true;

		$dbhost=$DBCONFIG->host;
		$dbuser=$DBCONFIG->user;
		$dbpass=$DBCONFIG->pass;
		$dbname=$DBCONFIG->name;
		try {
			$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
		} catch (PDOException $e) {
			// TODO: use something better than die
			die($e->getMessage());
		}
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	return $dbh;
}


function createDB(){
	$db = getConnection();

	//print_r($this);
	$result = $db->exec('CREATE TABLE IF NOT EXISTS papers (
                    id VARCHAR(50) NOT NULL,
                    title VARCHAR(500) NOT NULL,
					year INTEGER,
                    authors VARCHAR(500) NOT NULL);');

	if (!($result instanceof Sqlite3Result)) {
		echo "successful."; // This will never echo.
	} else {
		$result->fetchArray(); // This will throw an error.
	}

	$result = $db->exec('CREATE TABLE IF NOT EXISTS vignettes (
                    text VARCHAR(5000) NOT NULL,
					paperid VARCHAR(50) NOT NULL,
                    userid INTEGER NOT NULL,
					downvotes INTEGER NOT NULL DEFAULT 0,
					upvotes INTEGER NOT NULL DEFAULT 0,
					priv BOOLEAN NOT NULL DEFAULT true,
					anon BOOLEAN NOT NULL DEFAULT false,
					added DATETIME NOT NULL DEFAULT NOW(),
					edited DATETIME NOT NULL DEFAULT NOW(),
					PRIMARY KEY (paperid, userid)
			);');

	if (!($result instanceof Sqlite3Result)) {
		echo "successful."; // This will never echo.
	} else {
		$result->fetchArray(); // This will throw an error.
	}

	$result = $db->exec('CREATE TABLE IF NOT EXISTS votes (
					paperid VARCHAR(50) NOT NULL,
                    userid INTEGER NOT NULL,
					voteruserid INTEGER NOT NULL,
					vote INTEGER NOT NULL,
					added DATETIME NOT NULL DEFAULT NOW(),
					edited DATETIME NOT NULL DEFAULT NOW(),
					PRIMARY KEY (paperid, userid, voteruserid)
			);');

	if (!($result instanceof Sqlite3Result)) {
		echo "successful."; // This will never echo.
	} else {
		$result->fetchArray(); // This will throw an error.
	}

	$result = $db->exec('CREATE TABLE IF NOT EXISTS users (
                    userid INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
                    username VARCHAR(150) UNIQUE NOT NULL,
                    email VARCHAR(150) NOT NULL,
					verifiedemail BOOLEAN NOT NULL DEFAULT false,
                    hash VARCHAR(150) NOT NULL,
					secret VARCHAR(150) NOT NULL,
					editsecret VARCHAR(150) NOT NULL,
                    level INTEGER NOT NULL DEFAULT 0,
					added DATETIME NOT NULL DEFAULT NOW(),
					edited DATETIME NOT NULL DEFAULT NOW(),
					last_login DATETIME
			);');

	if (!($result instanceof Sqlite3Result)) {
		echo "successful."; // This will never echo.
	} else {
		$result->fetchArray(); // This will throw an error.
	}

	// 	$result = $db->exec('CREATE TABLE IF NOT EXISTS pins (
	// 					paperid VARCHAR(50) NOT NULL,
	//                     userid INTEGER NOT NULL,
	// 					voteruserid INTEGER NOT NULL,
	// 					added DATETIME NOT NULL DEFAULT NOW(),
	// 					edited DATETIME NOT NULL DEFAULT NOW(),
	// 					PRIMARY KEY (paperid, userid, voteruserid)
	// 			);');


	// 	if (!($result instanceof Sqlite3Result)) {
	// 		echo "successful."; // This will never echo.
	// 	} else {
	// 		$result->fetchArray(); // This will throw an error.
	// 	}

		$paper = (object)[];
		$paper->id = "conf/visualization/SadowskyCT05";
		$paper->title = "Wireless protocol design for smart home on mesh wireless sensor network.";
		$paper->authors="Liu, Jianfei and Zhang, Xiaopeng";
		addPaper($paper);
		
		$papers = findByName($paper->title);

		$vignette = (object)[];
		$vignette->id = $papers[0]->id;
		$vignette->text = "Paper is good yo";
		$vignette->userid=0;

		addVignette($vignette);

		$paper = (object)[];
		$paper->id = "krizhevsky2012imagenet";
		$paper->title = "Imagenet classification with deep convolutional neural networks";
		$paper->authors="Krizhevsky, Alex and Sutskever, Ilya and Hinton, Geoffrey E";
		addPaper($paper);

		$papers = findByName($paper->title);

		$vignette = (object)[];
		$vignette->id = $papers[0]->id;
		$vignette->text = "Arguing that existing theories of online collective action (e.g., tragedy of the commons and social loafing theory) fall short, Lampe et al. try to provide a set of tests of theories from uses and gratifications theory (U&G) and organizational commitment (OC) to help explain why users are motivated to participate in online communities. U&G seeks to explain involvement by suggesting that users seek out certain media because with the goal of satisfying a particular need. OC suggests that social identity is developed as part of involvement and focus on issues of attachment to a group. The central concept in this study is \"sense of belonging.\" The goal of the project is to compare the two models to see how they apply in a real online community.";
		$vignette->userid=2;

		addVignette($vignette);

		$vignette = (object)[];
		$vignette->id = $papers[0]->id;
		$vignette->text = "There is only one thing useful in this paper and it is this formula. Cross Entropy: $$- \frac1N\sum_{n=1}^N\ \bigg[y_n  \log \hat y_n + (1 - y_n)  \log (1 - \hat y_n)$$";
		$vignette->userid=3;

		addVignette($vignette);

		$vignette = (object)[];
		$vignette->id = $papers[0]->id;
		$vignette->text = "*Italic*, **bold**, and `monospace`. Itemized lists look like this";
		$vignette->userid=4;

		addVignette($vignette);
	}
?>