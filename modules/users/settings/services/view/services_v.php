<?php
//array colomn
$array_column = array(
	array(
        'column' => '',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'service',
        'width'  => '20',
        'header' => 'Service',
        'align'  => 'L'
    ),
    array(
        'column' => '',
        'type'   => 'int',
        'alias'  => 'count_memb',
        'width'  => '5',
        'header' => 'Nbr Comptes',
        'align'  => 'C'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'sign',
        'width'  => '5',
        'header' => 'Signature',
        'align'  => 'C'
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
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Services";
$html_data_table->task = 'services';

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}










































