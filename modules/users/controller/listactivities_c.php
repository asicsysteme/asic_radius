<?php
//array colomn
$array_column = array(
	array(
        'column' => 'sys_log.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'sys_log.message',
        'type'   => '',
        'alias'  => 'operation',
        'width'  => '20',
        'header' => 'Operation',
        'align'  => 'L'
    ),
    array(
        'column' => 'CONCAT(users_sys.fnom,\' \',users_sys.lnom)',
        'type'   => '',
        'alias'  => 'nom',
        'width'  => '15',
        'header' => 'Utilisateur',
        'align'  => 'L'
    ),
    
    array(
        'column' => 'sys_log.datlog',
        'type'   => 'datetime',
        'alias'  => 'date_operation',
        'width'  => '15',
        'header' => 'Date opération',
        'align'  => 'C'
    ),
    
 );
$user_id = Mreq::tp('id');
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('users_sys', 'sys_log');
//Jointure
$list_data_table->joint = "users_sys.nom = sys_log.user_exec AND users_sys.ID = $user_id ";
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'sys_log';
//Set Task used for statut line
$list_data_table->task = 'activities';
//Set File name for export
$list_data_table->file_name = 'liste_activites';
//Set Title of report
$list_data_table->title_report = 'Liste Activités';
//Need notif
$list_data_table->need_notif = false;
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	