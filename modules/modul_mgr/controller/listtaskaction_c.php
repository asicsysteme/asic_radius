<?php
//array colomn
$array_column = array(
	array(
        'column' => 'sys_task_action.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'sys_task_action.descrip',
        'type'   => '',
        'alias'  => 'descrip',
        'width'  => '20',
        'header' => 'Description',
        'align'  => 'L'
    ),
    array(
        'column' => 'sys_task_action.message_etat',
        'type'   => '',
        'alias'  => 'message_etat',
        'width'  => '15',
        'header' => 'Message Etat',
        'align'  => 'L'
    ),
    array(
        'column' => 'CASE sys_task_action.type WHEN 0 THEN \'Lien\' ELSE \'Autorisation\' END',
        'type'   => '',
        'alias'  => 'type',
        'width'  => '10',
        'header' => 'Client',
        'align'  => 'L'
    ),
    
    array(
        'column' => 'sys_task_action.etat_line',
        'type'   => 'int',
        'alias'  => 'etat_line',
        'width'  => '10',
        'header' => 'Etat Line',
        'align'  => 'C'
    ),
    array(
        'column' => 'CASE sys_task_action.notif WHEN 0 THEN \'Non\' ELSE \'OUI\' END',
        'type'   => '',
        'alias'  => 'notif',
        'width'  => '10',
        'header' => 'Notification',
        'align'  => 'C'
    ),
    
 );
$app_id = Mreq::tp('id');
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('sys_task_action');
//Set Jointure
$list_data_table->joint = "appid = $app_id";
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'sys_task_action';
//Set Task used for statut line
$list_data_table->task = 'task_action';
//Set File name for export
$list_data_table->file_name = 'liste_task_action';
//Set Title of report
$list_data_table->title_report = 'Liste Task Action';
//No need status
$list_data_table->need_notif = false;
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
