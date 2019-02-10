<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_profiles
//Created : 02-02-2019
//Controller Liste
$array_column = array(
    array(
        'column' => 'cp_profiles.id',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
        //Complete Array fields here
    array(
        'column' => 'cp_profiles.profile',
        'type'   => '',
        'alias'  => 'profile',
        'width'  => '25',
        'header' => 'Profile',
        'align'  => 'L'
    ),
    array(
        'column' => 'cp_profiles.quota_s',
        'type'   => '',
        'alias'  => 'quota_s',
        'width'  => '5',
        'header' => 'Quota',
        'align'  => 'C'
    ),


    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '10',
        'header' => 'Statut',
        'align'  => 'L'
    ),

 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables       = array('cp_profiles');
//Set Jointure
$list_data_table->joint        = '';
//Call all columns
$list_data_table->columns      = $array_column;
//Set main table of Query
$list_data_table->main_table   = 'cp_profiles';
//Set Task used for statut line
$list_data_table->task         = 'cp_profiles';
//Set File name for export
$list_data_table->file_name    = 'cp_profiles';
//Set Title of report
$list_data_table->title_report = 'Liste cp_profiles';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}

	

