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
        'width'  => '20',
        'header' => 'Statut',
        'align'  => 'L'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'nom',
        'width'  => '15',
        'header' => 'Utilisateur',
        'align'  => 'L'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'connexion',
        'width'  => '15',
        'header' => 'Heure Connexion',
        'align'  => 'C'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'deconnection',
        'width'  => '15',
        'header' => 'Heure Déconnexion',
        'align'  => 'C'
    ),
    array(
        'column' => '',
        'type'   => '',
        'alias'  => 'duree',
        'width'  => '15',
        'header' => 'Durée',
        'align'  => 'L'
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
$html_data_table->title_module = "Connexions $uesr_infos";
$html_data_table->task = 'history';
$html_data_table->btn_add_check = true;
$html_data_table->js_extra_data = MInit::crypt_tp('id', Mreq::tp('id'));
$html_data_table->js_order = '[ 0, "DESC" ]';
//Set Button return if need
$html_data_table->btn_return = array('task'=>'compte', 'title'=>'Page de Profil', 'data'=> MInit::crypt_tp('id', Mreq::tp('id')));

if(!$data = $html_data_table->table_html())
{
    exit("0#".$html_data_table->log);
}else{
    echo $data;
}

