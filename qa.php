<?php 

function addQuestion($question){
	
	if (getcurrentuser()->userid < 0){
		echo "{}";
		die();
	}
	
$sql  = <<<EOT
INSERT INTO qa_questions (paperid, userid, text, category, added, edited) 
VALUES (:paperid, :userid, :text, :category, NOW(), NOW())";
EOT;

	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("paperid", $question->paperid);
		$stmt->bindParam("userid", getcurrentuser()->userid);
		$stmt->bindParam("text", $question->text);
		$stmt->bindParam("category", $question->category);
	
		$stmt->execute();
		$db = null;
	
	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//include("templates/error.php");
	}
}

function addAnswer($answer){

	if (getcurrentuser()->userid < 0){
		echo "{}";
		die();
	}
	
	$sql  = <<<EOT
INSERT INTO qa_answers (questionid, userid, text, added)
VALUES (:questionid, :userid, :text, NOW())";
EOT;
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("questionid", $answer->questionid);
		$stmt->bindParam("userid", getcurrentuser()->userid);
		$stmt->bindParam("text", $answer->text);
	
		$stmt->execute();
		$db = null;
	
	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//include("templates/error.php");
	}

}

function editQuestion($question){

	if (getcurrentuser()->userid < 0){
		echo "{}";
		die();
	}
	
$sql  = <<<EOT
UPDATE qa_questions SET status=:status, edited=NOW() WHERE userid=:userid and id = :id
EOT;
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id", $question->questionid);
		$stmt->bindParam("userid", getcurrentuser()->userid);
		$stmt->bindParam("status", $question->status);
	
		$stmt->execute();
		$db = null;
	
	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//include("templates/error.php");
	}
	
}


function getQuestions($paperid){


	$sql  = <<<EOT
SELECT * FROM qa_questions WHERE paperid = :paperid
EOT;
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("paperid", $paperid);

		$stmt->execute();
		$questions = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		return $questions;
		
	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//include("templates/error.php");
	}

}



function getAnswers($questionid){


	$sql  = <<<EOT
SELECT * FROM qa_answers WHERE questionid = :questionid
EOT;
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("questionid", $questionid);

		$stmt->execute();
		$answers = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		return $answers;

	} catch(PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//include("templates/error.php");
	}

}


?>