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



	
	$titre = "Index";
	$page = "index"; //__variable pour la classe "active" du menu-header
	
//__variables pour les balises méta
	$description = "Stations Radios - Maps";
	$keyword = "";
    $author = "DATA Connect Tchad";
    $title = "ARCEP Google MAP";


 //__variables pour l'initialisation du map 
    $lat = "12.1130740204" ;
    $lng = "15.0434075655" ;
    $zoom = "16";
    $raduis = null ;
	//exit(Mreq::tg('tech'));
    try {
    	$map=new Map();
		$categories = $map->getAllCategory();
		
		if (Mreq::tp('marker')!= null  && Mreq::tp('marker')!= "0") {
			 if(MInit::form_verif('form', false)) {

			$tabCheckbox = Mreq::tp('marker');
		    $tabville = Mreq::tp('ville');
			$markers = array();
			if($tabCheckbox  == 'all') {

	           $catMarkers = $map->getAllMarkersActif("Oui", "Oui");
			   $allMarkersJson = json_encode($catMarkers);
			  
			}
			else {
				$catMarkers = $map->getMarkersCategory("Oui", "Oui", $tabCheckbox ,$tabville);
				$markers = array_merge($markers, $catMarkers);
				$allMarkersJson = json_encode($markers);

			}
			
				}	}

				// affichage des cordonnéer geographic d'un seul ligne (Action localisation)
		elseif  (Mreq::tg('tch')!= null  && Mreq::tg('tch')!= "0" ){
            
             if(md5(MInit::cryptage(Mreq::tg('id'),1)) != Mreq::tg('idc')  or md5(MInit::cryptage(Mreq::tg('tch'),1)) != Mreq::tg('tchc')  )
             {
             	 	exit('3#'.'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
             }
                
			    $tabCheckbox = Mreq::tg('tch');
			    $ligne_marker = Mreq::tg('id');
			    $markers = array();
                $catMarkers = $map->getMarkersCategory_ln("Oui", "Oui", $tabCheckbox,$ligne_marker);
                $markers = array_merge($markers, $catMarkers);
                if(empty( $markers)) {
                	exit('3#'.'<br>Les informations pour cette ligne sont incomplète contactez l\'administrateur');
                }
                //reconfiguration du variable d'initialisation 
                 $lat = $markers[0][5]; 
                 $lng = $markers[0][4]; 
                 $raduis= 20 ;
                 $zoom = "19";
				 $allMarkersJson = json_encode($markers);


        } 
		else {
			$catMarkers = $map->getAllMarkersActif("Oui", "Oui");
			$allMarkersJson = json_encode($catMarkers);
		}
		

		
		require_once("view/vueIndex.php");
		
       
    } catch (Exception $e) {
        $msgErreur = $e->getMessage();
        require_once("view/vueErreur.php");
    }
?>

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




$titre = "Index";
$page = "index"; //__variable pour la classe "active" du menu-header
	
//variables pour les balises méta
$description = "Stations Radios - Maps";
$keyword = "";
$author = "DATA Connect Tchad";
$title = "ARCEP Google MAP";


//variables pour l'initialisation du map 

//Start instance MAP
$map = new Map();
//Get single marker
$markers        = $map->get_singl_marker(5);
$allMarkersJson = json_encode($markers);
$lat            = $map->lat;
$lng            = $map->lng;
$zoom           = $map->zoom;
//$raduis       = $markers->lat;

var_dump($markers);
//require_once("view/vueIndex.php");


array (size=36)
      0 => string '9' (length=1)
      'id' => string '9' (length=1)
      1 => string '9' (length=1)
      'marker_id' => string '9' (length=1)
      2 => string '4' (length=1)
      'marker_categorie' => string '4' (length=1)
      3 => string '1' (length=1)
      'marker_line_id' => string '1' (length=1)
      4 => string '1' (length=1)
      'marker_ville' => string '1' (length=1)
      5 => string '18.78662109375' (length=14)
      'marker_longitude' => string '18.78662109375' (length=14)
      6 => string '14.64736838389663' (length=17)
      'marker_latitude' => string '14.64736838389663' (length=17)
      7 => string 'Site Batha-Ouest, Tchad' (length=23)
      'marker_text' => string 'Site Batha-Ouest, Tchad' (length=23)
      8 => string '0' (length=1)
      'marker_actif' => string '0' (length=1)
      9 => string '35' (length=2)
      'marker_radius' => string '35' (length=2)
      10 => string '15' (length=2)
      'marker_network' => string '15' (length=2)
      11 => string '14.64736838389663' (length=17)
      'marker_line_lat' => string '14.64736838389663' (length=17)
      12 => string '18.78662109375' (length=14)
      'marker_line_long' => string '18.78662109375' (length=14)
      13 => string '4' (length=1)
      'icone_id' => string '4' (length=1)
      14 => string 'oui' (length=3)
      'icone_actif' => string 'oui' (length=3)
      15 => string 'VSAT' (length=4)
      'icone_categorie' => string 'VSAT' (length=4)
      16 => string 'vsat' (length=4)
      'icone_icon' => string 'vsat' (length=4)
      17 => string 'vsat_station_vsat' (length=17)
      'icone_table' => string 'vsat_station_vsat' (length=17)


      array (size=28)
  0 => string '5' (length=1)
  'id' => string '5' (length=1)
  1 => string '5' (length=1)
  'marker_id' => string '5' (length=1)
  2 => string '9' (length=1)
  'marker_categorie' => string '9' (length=1)
  3 => string '0' (length=1)
  'marker_line_id' => string '0' (length=1)
  4 => string '0' (length=1)
  'marker_ville' => string '0' (length=1)
  5 => string '16.43396000000007' (length=17)
  'marker_longitude' => string '16.43396000000007' (length=17)
  6 => string '11.19351' (length=8)
  'marker_latitude' => string '11.19351' (length=8)
  7 => string 'Gayak, Tchad' (length=12)
  'marker_text' => string 'Gayak, Tchad' (length=12)
  8 => string '0' (length=1)
  'marker_actif' => string '0' (length=1)
  9 => string '0' (length=1)
  'marker_radius' => string '0' (length=1)
  10 => null
  'marker_network' => null
  11 => string '12.087083' (length=9)
  'marker_line_lat' => string '12.087083' (length=9)
  12 => string '15.0148322' (length=10)
  'marker_line_long' => string '15.0148322' (length=10)
  13 => string 'vsat' (length=4)
  'icone_icon' => string 'vsat' (length=4)	
?>
