<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_users
//Created : 05-02-2019
//View
//array colomn
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
$html_data_table                 = new Mdatatable();
$html_data_table->columns_html   = $array_column;
$html_data_table->title_module   = "Historique Connexion";
$html_data_table->task           = 'cp_users_connexions';
//$html_data_table->btn_add_text =  '';
$html_data_table->btn_add_check  = true;
$html_data_table->btn_action     = false;


if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































