<?php 
$id = Mreq::tp('id');

$error_id = $id == null ? 'false' : 'true';
$data = MInit::crypt_tp('id', $id);

$error_task = !MInit::crypt_tp('task', null, 'D') ? 'false' : 'true'; ;
$task = Mreq::tp('task');

if($error_id == 'false' OR $error_task == 'false' )
{
    $arr_return  = array('error' => 'false');
}else{
	$arr_return  = array('error' => 'true', 'data' => $data, 'task' => $task);
}
print json_encode($arr_return);

?>
