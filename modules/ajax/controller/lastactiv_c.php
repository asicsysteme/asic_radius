<?php 

global $db ;
$query="update users_sys set lastactive=CURRENT_TIMESTAMP where id=".$_SESSION['userid'];
 if (! $db->Query($query)) $db->Kill($db->Error());
 exit("1");
?>

