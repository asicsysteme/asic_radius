<?php
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D'))
 {  
    // returne message error red to client 
    exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
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
        'alias'  => 'message_etat',
        'width'  => '15',
        'header' => 'Message Etat',
        'align'  => 'L'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'type',
        'width'  => '10',
        'header' => 'Client',
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
//Get info utilisateur to add into title
$info_task = new Mmodul();
$info_task->id_task = Mreq::tp('id');
$info_task->get_task();
$task_name = $info_task->task_info['dscrip'];
$id_modul = $info_task->task_info['modul'];
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Task action pour  $task_name";
$html_data_table->task = 'taskaction';
$html_data_table->btn_add_data = MInit::crypt_tp('id', Mreq::tp('id'));
$html_data_table->js_extra_data = MInit::crypt_tp('id', Mreq::tp('id'));
//$html_data_table->js_order = '[ 0, "DESC" ]';
//Set Button return if need
$html_data_table->btn_return = array('task'=>'task', 'title'=>'Liste Task modul', 'data'=> MInit::crypt_tp('id', $id_modul));

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}
?>
















































<?php 
//SYS MRN ERP
// Modul: Modul MGR => View