<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_users
//Created : 05-02-2019
//Controller Liste
$array_column = array(
	array(
        'column' => 'radacct.radacctid',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'radacct.framedipaddress',
        'type'   => '',
        'alias'  => 'ip',
        'width'  => '10',
        'header' => 'IPV4',
        'align'  => 'C'
    ),
    array(
        'column' => 'radacct.callingstationid',
        'type'   => '',
        'alias'  => 'mac',
        'width'  => '10',
        'header' => 'MAC',
        'align'  => 'C'
    ),
    array(
        'column' => 'radacct.starttime',
        'type'   => '',
        'alias'  => 'starttime',
        'width'  => '10',
        'header' => 'Début',
        'align'  => 'C'
    ),
    array(
        'column' => 'radacct.stoptime',
        'type'   => '',
        'alias'  => 'stoptime',
        'width'  => '10',
        'header' => 'Fin',
        'align'  => 'C'
    ),
    array(
        'column' => 'TIMEDIFF(stoptime, starttime )',
        'type'   => '',
        'alias'  => 'temp',
        'width'  => '5',
        'header' => 'Durée',
        'align'  => 'C'
    ),
    array(
        'column' => 'radacct.acctterminatecause',
        'type'   => '',
        'alias'  => 'cause',
        'width'  => '10',
        'header' => 'Cmd',
        'align'  => 'C'
    ),
    array(
        'column' => 'CASE WHEN (TIMEDIFF(NOW(), stoptime )) > 70 THEN \Déconnecté<\' ELSE \'Connecté\' END',
        'type'   => 'html',
        'html' => 'CASE WHEN (TIMEDIFF(NOW(), stoptime )) > 70 THEN \'<span class="label label-sm label-warning">Déconnecté</span>\' ELSE \'<span class="label label-sm label-success">Connecté</span>\' END',
        'alias'  => 'statut',
        'width'  => '5',
        'header' => 'Statut',
        'align'  => 'C'
    ),
    
 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('radacct');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'radacct';
//Where
$list_data_table->need_notif = false;
//$list_data_table->where = 'radacct.username = \''.MySQL::SQLValue(Mreq::tp);
//Set Task used for statut line
$list_data_table->task = 'cp_users_connexions';
//Set File name for export
$list_data_table->file_name = 'cp_users_connexions';
//Set Title of report
$list_data_table->title_report = 'Historique Connexion';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

