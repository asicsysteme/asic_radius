<?php
//array colomn
$array_column = array(
	array(
        'column' => 'ref_departement.id',
        'type'   => 'int',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
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
        'column' => 'ref_region.region',
        'type'   => '',
        'alias'  => 'region',
        'width'  => '15',
        'header' => 'Région',
        'align'  => 'L'
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
$list_data_table->tables = array('ref_departement','ref_region');
//Set Jointure
$list_data_table->joint = 'ref_region.id = ref_departement.id_region';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'ref_departement';
//Set Task used for statut line
$list_data_table->task = 'departements';
//Set File name for export
$list_data_table->file_name = 'liste_departements';
//Set Title of report
$list_data_table->title_report = 'Liste des départements';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
