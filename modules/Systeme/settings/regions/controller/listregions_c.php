<?php
//array colomn
$array_column = array(
	array(
        'column' => 'ref_region.id',
        'type'   => 'int',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
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
        'column' => 'ref_pays.pays',
        'type'   => '',
        'alias'  => 'pays',
        'width'  => '15',
        'header' => 'Pays',
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
$list_data_table->tables = array('ref_region','ref_pays');
//Set Jointure
$list_data_table->joint = 'ref_region.id_pays = ref_pays.id';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'ref_region';
//Set Task used for statut line
$list_data_table->task = 'regions';
//Set File name for export
$list_data_table->file_name = 'liste_regions';
//Set Title of report
$list_data_table->title_report = 'Liste des régions';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
