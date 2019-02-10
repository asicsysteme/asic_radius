<?php
//array colomn
$array_column = array(
    array(
        'column' => 'sys_workflow.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'sys_workflow.descrip',
        'type'   => '',
        'alias'  => 'descrip',
        'width'  => '20',
        'header' => 'Description',
        'align'  => 'L'
    ),
    array(
        'column' => 'sys_workflow.code',
        'type'   => '',
        'alias'  => 'code',
        'width'  => '7',
        'header' => 'Description',
        'align'  => 'L'
    ),
    array(
        'column' => 'sys_workflow.message_etat',
        'type'   => '',
        'alias'  => 'message_etat',
        'width'  => '15',
        'header' => 'Message Etat',
        'align'  => 'L'
    ),
       
    array(
        'column' => 'sys_workflow.etat_line',
        'type'   => 'int',
        'alias'  => 'etat_line',
        'width'  => '10',
        'header' => 'Etat Line',
        'align'  => 'C'
    ),
    array(
        'column' => 'CASE sys_workflow.notif WHEN 0 THEN \'Non\' ELSE \'OUI\' END',
        'type'   => '',
        'alias'  => 'notif',
        'width'  => '10',
        'header' => 'Notification',
        'align'  => 'C'
    ),
    
 );
$modul_id = Mreq::tp('id');
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('sys_workflow');
//Set Jointure
$list_data_table->joint = "modul_id = $modul_id";
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'sys_workflow';
//Set Task used for statut line
$list_data_table->task = 'sys_workflow';
//Set File name for export
$list_data_table->file_name = 'liste_modul_workflow';
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
    
