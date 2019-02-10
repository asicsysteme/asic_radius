<?php
//First check target no Hack
if(!defined('_MEXEC'))die();


$import_modul = new Export_modul;

if(!$import_modul->import_modul('test'))
{
	exit('0#Erreur Opération '.$import_modul->log);
}else{
	exit('1#Opération réussie '.$import_modul->log);
}
	//exit('1#'.$export_modul->export_mod($mod_id));
