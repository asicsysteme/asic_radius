
<?php
//array colomn
$array_column = array(
	array(
        'column' => 'sys_services.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'sys_services.service',
        'type'   => '',
        'alias'  => 'service',
        'width'  => '20',
        'header' => 'Service',
        'align'  => 'L'
    ),
    array(
        'column' => '(SELECT(COUNT(id)) FROM sys_users WHERE sys_users.`service` = sys_services.id)',
        'type'   => 'int',
        'alias'  => 'count_memb',
        'width'  => '15',
        'header' => 'Nbr Comptes',
        'align'  => 'C'
    ),
    array(
        'column' => '(CASE sys_services.sign WHEN 0 THEN \'Non\' ELSE \'Oui\' END)',
        'type'   => '',
        'alias'  => 'sign',
        'width'  => '10',
        'header' => 'Signature',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'L'
    ),
    

   
    
 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('sys_services');
$list_data_table->joint = "id <> 1";
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'sys_services';
//Set Task used for statut line
$list_data_table->task = 'services';
//Set File name for export
$list_data_table->file_name = 'liste_services';
//Set Title of report
$list_data_table->title_report = 'Liste Services';
//Need notif
$list_data_table->need_notif = true;
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	

