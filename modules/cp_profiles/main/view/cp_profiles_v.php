<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_profiles
//Created : 02-02-2019
//View
//array colomn
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
        'column' => 'cp_profiles.region',
        'type'   => '',
        'alias'  => 'region',
        'width'  => '30',
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Gestion des Profiles";
$html_data_table->task         = 'cp_profiles';
$html_data_table->btn_add_text =  'Profile';
$html_data_table->task_add     = 'add_cp_profiles';


if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































