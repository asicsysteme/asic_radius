<?php
global $db;
//Home Link							
$output = '<li><i class="ace-icon fa fa-home home-icon"></i><a href="./">Accueil</a></li>';
//get info from task table
if (!$db->Query("SELECT modul,dscrip FROM task
                 where  app='".MReq::tp('app')."' ")) 
	             $db->Kill($db->Error());

if ($db->RowCount() > 0 ) 
{
	$array = $db->RowArray();
	$output .= '<li class="active">'.$array['modul'].'</li>';
	$output .= '<li class="active">'.$array['dscrip'].'</li>';


} 

	//Print Tree Application
echo $output;	  
