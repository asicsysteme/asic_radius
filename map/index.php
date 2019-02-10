<?php
session_start();
/*
 * Contrôleur de notre page de maps
 * gère la dynamique de l'application. Elle fait le lien entre l'utilisateur et le reste de l'application
 */

	//include_once("model/BDD.php");
include_once("../mincludes/config.php");
include_once("../libraries/Mysql.class.php");
include_once("../libraries/session.class.php");
include_once("../libraries/init.class.php");
include_once("../libraries/Mreq.class.php");
include_once("model/Map.php");
include_once("model/Debug.php");

//First thing check session exist and is valid with the user else go to index off app
$session_tester = NULL;
if(session::get('ssid') == false)
{
	$session_tester = 1;
}

if($session_tester == 1)
{
	exit('<h1> Erreur de connexion </h1>');
}




$titre       = "Index";
$page        = "index"; 
$description = "Stations Radios - Maps";
$keyword     = "";
$author      = "DATA Connect Tchad";
$title       = "ARCEP Google MAP";



//Start instance MAP
//Chek if is Singl marker
if(MInit::crypt_tp('singl', null, 'D'))
{
	//Check if id is been the correct id compared with idc
	if(!MInit::crypt_tp('id', null, 'D') )
	{  
        //returne message error red to client 
		exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}
	$map = new Map();

	$id_marker =  Mreq::tp('id');
//Get single marker
	$markers        = $map->get_singl_marker($id_marker);
	$allMarkersJson = json_encode($markers);
	$lat            = $map->lat;
	$lng            = $map->lng;
	$zoom           = $map->zoom;
    

}
if(Mreq::tp('mult', null, 'D'))
{
	$hash = Mreq::tp('mult');
	
	
	$map = new Map();

	
//Get single marker
	$markers        = $map->get_multipl_marker($hash);
	$allMarkersJson = json_encode($markers);
	$lat            = $map->lat;
	$lng            = $map->lng;
	$zoom           = $map->zoom;
    

}
//End Singl marker


//var_dump($markers);
require_once("view/vueIndex.php");