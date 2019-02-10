<?php
$new_logout = new  MLogin();
$new_logout->token = session::get('ssid');
if($new_logout->logout())
{
	header('location:./');
}else{
	MInit::msg_cor($new_logout->log, $err = "", $return = "");
	//exit('error');
}


?>