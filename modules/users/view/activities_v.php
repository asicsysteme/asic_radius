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
        'alias'  => 'operation',
        'width'  => '35',
        'header' => 'Operation',
        'align'  => 'L'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'nom',
        'width'  => '25',
        'header' => 'Utilisateur',
        'align'  => 'L'
    ),
    
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'date_operation',
        'width'  => '15',
        'header' => 'Date opération',
        'align'  => 'C'
    ),
    
 );
//Get info utilisateur to add into title
$user = new Musers();
$user->id_user = Mreq::tp('id');
$user->get_user();
$uesr_infos = $user->g('fnom').' '.$user->g('lnom').' -'.$user->g('id').'- #'.$user->g('nom');
//Creat new instance
$html_data_table = new Mdatatable();
$html_data_table->columns_html = $array_column;
$html_data_table->title_module = "Activités de : $uesr_infos";
$html_data_table->task = 'activities';
$html_data_table->js_extra_data = MInit::crypt_tp('id', Mreq::tp('id'));
//Set Button return if need
$html_data_table->btn_return = array('task'=>'compte', 'title'=>'Page de Profil', 'data'=> MInit::crypt_tp('id', Mreq::tp('id')));

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}










































