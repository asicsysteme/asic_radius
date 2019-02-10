<?php
defined('_MEXEC') or die;
//Get all modul infos 
$info_modul= new Mmodul();
$info_modul->id_modul = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')  or !$info_modul->get_modul())
{   
    exit('3#'.$info_modul->log .'<br>Les  informations pour cette ligne sont erronées contactez l\'administrateur');
}
$id_modul   = Mreq::tp('id');
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
        'alias'  => 'descrip',
        'width'  => '20',
        'header' => 'Description',
        'align'  => 'L'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'code',
        'width'  => '7',
        'header' => 'Code Usage',
        'align'  => 'L'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'message_etat',
        'width'  => '15',
        'header' => 'Message Etat',
        'align'  => 'L'
    ),
    
    array(
        'column' => '',
        'type'   => 'int',
        'alias'  => 'etat_line',
        'width'  => '10',
        'header' => 'Etat Line',
        'align'  => 'C'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'notif',
        'width'  => '10',
        'header' => 'Notification',
        'align'  => 'C'
    ),
    
 );
//Get info Module to add into title

$modul_name = $info_modul->modul_info['description'];
$id_modul = $info_modul->modul_info['modul'];
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Workflow pour:   $modul_name";
$html_data_table->task = 'modul_workflow';
$html_data_table->btn_add_data = MInit::crypt_tp('id', Mreq::tp('id'));
$html_data_table->js_extra_data = MInit::crypt_tp('id', Mreq::tp('id'));
//$html_data_table->js_order = '[ 0, "DESC" ]';
//Set Button return if need
$html_data_table->btn_return = array('task'=>'modul', 'title'=>'Liste  modules');

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
?>
