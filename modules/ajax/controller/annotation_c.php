<?php 

function annotation($message,$app,$idart,$userid){
	global $db;
$values["message"] = MySQL::SQLValue($message);
$values["artid"] = MySQL::SQLValue($idart);
$values["app"] = MySQL::SQLValue($app);
$values["usrid"] = MySQL::SQLValue($userid);

// Execute the insert
$result = $db->InsertRow("annotation", $values);

// If we have an error
if (! $result) {
	// Show the error and kill the script
	$db->Kill($db->Error());
	return false;
}
}
if(isset($_REQUEST['a']) && ($_REQUEST['a'])=='an'){annotation($_REQUEST['message'],$_REQUEST['app'],$_REQUEST['idart'],$_SESSION['userid']);}
?>