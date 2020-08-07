<?php 

$logfile = "./logerror";
$logfilewarn = "./logwarn";
$logfilehack = "./loghack";

function logerror($where, $text){
	
	global $logfile;
	
	file_put_contents($logfile, date("Y-m-d H:i:s").",".$where.",".$text."\n", FILE_APPEND | LOCK_EX);
}

function logwarn($where, $text){

	global $logfilewarn;

	file_put_contents($logfilewarn, date("Y-m-d H:i:s").",".$where.",".$text."\n", FILE_APPEND | LOCK_EX);
}

function loghack($where, $text){

	global $logfilehack;

	file_put_contents($logfilehack, date("Y-m-d H:i:s").",".$where.",".$text."\n", FILE_APPEND | LOCK_EX);
}




?>