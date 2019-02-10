<?php
//array colomn
$array_column = array(
	array(
        'column' => 'ref_pays.id',
        'type'   => 'int',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
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
        'column' => 'ref_pays.nationalite',
        'type'   => '',
        'alias'  => 'nationalite',
        'width'  => '15',
        'header' => 'Nationalité',
        'align'  => 'L'
    ),
    array(
        'column' => 'ref_pays.alpha',
        'type'   => '',
        'alias'  => 'alpha',
        'width'  => '15',
        'header' => 'Code du pays',
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
$list_data_table->tables = array('ref_pays');
//Set Jointure
$list_data_table->joint = '';
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'ref_pays';
//Set Task used for statut line
$list_data_table->task = 'pays';
//Set File name for export
$list_data_table->file_name = 'liste_pays';
//Set Title of report
$list_data_table->title_report = 'Liste Pays';
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>
	
