<?php
//array colomn
$array_column = array(
	array(
        'column' => 'ref_ville.id',
        'type'   => 'int',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'ref_ville.ville',
        'type'   => '',
        'alias'  => 'ville',
        'width'  => '15',
        'header' => 'Ville',
        'align'  => 'L'
    ),
    array(
        'column' => 'ref_departement.departement',
        'type'   => '',
        'alias'  => 'departement',
        'width'  => '15',
        'header' => 'Département',
        'align'  => 'L'
    ),
    array(
        'column' => 'ref_ville.latitude',
        'type'   => '',
        'alias'  => 'latitude',
        'width'  => '15',
        'header' => 'Latitude',
        'align'  => 'C'
    ),
    array(
        'column' => 'ref_ville.longitude',
        'type'   => '',
        'alias'  => 'longitude',
        'width'  => '15',
        'header' => 'Longitude',
        'align'  => 'C'
    ),
    array(
        'column' => 'statut',
        'type'   => '',
        'alias'  => 'statut',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'C'
    ),
    
 );
//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('ref_ville','ref_departement');
//Set Jointure
$list_data_table->joint = 'ref_departement.id = ref_ville.id_departement';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'ref_ville';
//Set Task used for statut line
$list_data_table->task = 'villes';
//Set File name for export
$list_data_table->file_name = 'liste_villes';
//Set Title of report
$list_data_table->title_report = 'Liste Villes';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
