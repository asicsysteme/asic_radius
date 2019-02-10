<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS ASIC ERP
// Modul: modul_mgr
//Created : 12-10-2017
//Controller EXEC Form
$task_action = new Mmodul();
$task_action->id_task_action = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$task_action->get_task_action())
{  
   // returne message error red to task_action 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $task_action->task_action_info['etat'];
//$task_action->deletetaskaction($etat)
//Execute Validate - delete


if($task_action->delete_task_action())
{
	exit("1#".$task_action->log);

}else{
	exit("0#".$task_action->log);
}