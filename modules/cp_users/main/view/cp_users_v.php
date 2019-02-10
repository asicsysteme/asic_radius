<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_users
//Created : 03-02-2019
//View
//array colomn
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
        'width'  => '15',
        'header' => 'username',
        'align'  => 'L'
    ),

    array(
        'column' => 'radcheck.nom',
        'type'   => '',
        'alias'  => 'nom',
        'width'  => '15',
        'header' => 'nom',
        'align'  => 'L'
    ),
    
    array(
        'column' => 'radcheck.service',
        'type'   => '',
        'alias'  => 'Profile',
        'width'  => '15',
        'header' => 'Profile',
        'align'  => 'L'
    ),
    array(
        'column' => 'radcheck.data_up',
        'type'   => 'int',
        'alias'  => 'data_up',
        'width'  => '15',
        'header' => 'Data Mb',
        'align'  => 'R'
    ),
   
    array(
        'column' => 'radcheck.etat_connect',
        'type'   => 'int',
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
$html_data_table               = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Liste Utilisateurs CP";
$html_data_table->task         = 'cp_users';
$html_data_table->btn_add_text =  'Utilisateur CP';
$html_data_table->task_add     = 'add_cp_users';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}


















































