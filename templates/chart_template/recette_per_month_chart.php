<?php 
if(Mreq::tp('filtr') == 1){
	$form = new Mform('chart', 'chart', '', 'devis', '0', '');
	
	$mail_array[]  = array('email', 'true', 'Adresse Email non valide' );
    $form->input('Email ', 'email', 'text', 6, null, $mail_array);
    $form->button();
    $form->render();
	exit();
}

$chart = new MHighchart();
$chart->titre = 'Evolution des recettes par mois';
$chart->id_chart = 'recette_per_month';
$chart->items = 'Fcfa';
//Set to true if called from Ajax after filter
if(Mreq::tp('chart') != NULL)
{
	$chart->chart_only = true;
}

$chart->column_render('v_recet_per_month', 6);