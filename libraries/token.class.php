<?php

class token {

public $token;
//creat token
 static public function creat() {
	   

global $db;
$values["token"] = MySQL::SQLValue(md5(uniqid(rand(), true)));
$result = $db->InsertRow("token", $values);
return TRUE;
   }
 static public function creatimda() {
	 $_SESSION['sign'] = uniqid(rand(), true);
 }

 static public function delimda() {
	 unset ($_SESSION['sign']);
 }
}

?>
