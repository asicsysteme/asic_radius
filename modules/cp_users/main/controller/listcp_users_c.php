<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_users
//Created : 03-02-2019
//Controller Liste
$array_column = array(
	array(
        'column' => 'radcheck.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
        //Complete Array fields here
    array(
        'column' => 'radcheck.username',
        'type'   => '',
        'alias'  => 'username',
        'width'  => '10',
        'header' => 'username',
        'align'  => 'L'
    ),

    array(
        'column' => 'CONCAT(radcheck.nom, \' \',radcheck.prenom)',
        'type'   => '',
        'alias'  => 'nom',
        'width'  => '15',
        'header' => 'nom',
        'align'  => 'L'
    ),
    
    array(
        'column' => 'cp_profiles.profile',
        'type'   => '',
        'alias'  => 'profile',
        'width'  => '15',
        'header' => 'Profile',
        'align'  => 'L'
    ),
    array(
        'column' => '(radcheck.data_up + radcheck.data_down) / 1024 / 1024',
        'type'   => 'int',
        'alias'  => 'data',
        'width'  => '10',
        'header' => 'Data Mb',
        'align'  => 'R'
    ),
    
    array(
        'column' => 'CASE WHEN (`radcheck`.`etat_connect` = 1) THEN \'Connecté\' ELSE \'Déconnecté\' END',
        'type'   => 'html',
        'html'   =>  'CASE WHEN (`radcheck`.`etat_connect` = 1) THEN \'<span class="label label-sm label-success">Connecté</span>\' ELSE \'<span class="label label-sm label-warning">Déconnecté</span>\' END',
        'alias'  => 'etat_connect',
        'width'  => '15',
        'header' => 'Connexion',
        'align'  => 'L'
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
$list_data_table               = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables       = array('radcheck , cp_profiles');
//Set Jointure
$list_data_table->joint        = 'radcheck.profile = cp_profiles.id';
//Call all columns
$list_data_table->columns      = $array_column;
//Set main table of Query
$list_data_table->main_table   = 'radcheck';
//Set Task used for statut line
$list_data_table->task         = 'cp_users';
//Set File name for export
$list_data_table->file_name    = 'cp_users';
//Set Title of report
$list_data_table->title_report = 'Liste Utilisateurs CP';
//$list_data_table->debug = true;
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

