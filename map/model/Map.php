<?php
/*
 * Modele de classe PHP : Map-2.php
 * Classe d'affichage des markers sur une Google Maps
 */
$db   =  new MySQL();
class Map {
//__variable lié à la classe
    var $lat    = "12.1130740204" ;
    var $lng    = "15.0434075655" ;
    var $zoom   = "16";
    var $radius = null ;
     
//__Affiche tous les points actifs sur  Maps Table
    function getAllMarkersActif($markerActif = "Oui", $iconeActif = "Oui" ) {
        global $db;
		$count = array();
		$sql = "SELECT * ";
		$sql .= "FROM `map_markers` as MK, `map_markers_icone` as MKI ";
		$sql .= "WHERE MK.marker_categorie = MKI.icone_id ";
		$sql .= "AND `marker_actif` = '".$markerActif."' ";
		$sql .= "AND `icone_actif` = '".$iconeActif."' ";

		if (!$db->Query($sql)) $db->Kill($db->Error());
	
	    while (!$db->EndOfSeek()) {
            $row = $db->RowArray();
	        $count[] = $row;
	    }
		
        
		
		return $count; // Accès au résultat
    }

    public function get_singl_marker($id)
    {
    	global $db;
    	$sql = "SELECT *, 'vsat' as icone_icon FROM map_markers as MK WHERE id = $id ";
    	if(!$db->Query($sql)){
    		var_dump($db->Error());
    	}else{
    		if(!$db->RowCount())
    		{
    			exit('no data yet');
    		}else{
    			$brut_array       = $db->RecordsArray();
    			
    			$this->zoom   = 17;
    			$this->lat    = $brut_array[0]['marker_latitude'];
    			$this->lng    = $brut_array[0]['marker_longitude'];
    			

    			return $brut_array;
    		}
    	}


    }

    public function get_multipl_marker($hash)
    {
    	global $db;
    	$get_data_sql = file_get_contents('../temp/'.session::get('ssid').'/'.$hash.'.data');
    	
    	$parts = explode('#',$get_data_sql);
    	$category = $parts['0'];
    	$id_sql = $parts['1'];
    	$array_id = $this->get_id_array($id_sql);
    	$sql = "SELECT *, 'vsat' as icone_icon FROM map_markers as MK WHERE id IN $array_id ";

    	if(!$db->Query($sql)){
    		var_dump($db->Error());
    	}else{
    		if(!$db->RowCount())
    		{
    			exit('no data yet');
    		}else{
    			$brut_array       = $db->RecordsArray();
    			
    			$this->zoom   = 10;
    			$this->lat    = $brut_array[0]['marker_latitude'];
    			$this->lng    = $brut_array[0]['marker_longitude'];
    			

    			return $brut_array;
    		}
    	}


    }

    Private function get_id_array($sql)
    {
    	global $db;
    	if(!$db->Query($sql)){
    		var_dump($db->Error());
    	}else{
    		$brut_array = $db->RecordsArray();
    		$arr_id = array_column($brut_array, 'id');
    		$arr_id = implode(',', $arr_id);
    		return '('.$arr_id.')';
    	}
    }

	
//__Affiche les points actifs selon la catégorie
    function getMarkersCategory($markerActif = "Oui", $iconeActif = "Oui", $category= "" ,$ville = "" ) {
        
        global $db;
		$count = array();
		
		$sql = "SELECT * ";
		$sql .= "FROM `map_markers` as MK, `map_markers_icone` as MKI ";
		$sql .= "WHERE MK.marker_categorie = MKI.icone_id ";
		$sql .= "AND `icone_id` = '".$category."'";
		if($ville != null ){
		$sql .= "AND `marker_ville` = '".$ville."' ";
		}
		$sql .= "AND `marker_actif` = 'Oui' ";
		$sql .= "AND `icone_actif` = 'Oui'";
		
        if (!$db->Query($sql)) $db->Kill($db->Error());
	
	    while (!$db->EndOfSeek()) {
            $row = $db->RowArray();
	        $count[] = $row;
	    }
		
		return $count; // Accès au résultat
    } 
    
	//__Affiche les points actifs selon la catégorie et la ligne 
    function getMarkersCategory_ln($markerActif = "Oui", $iconeActif = "Oui", $category= "",$ligne_marker="" ) {
        
        global $db;
		$count = array();
		
		$sql = "SELECT * ";
		$sql .= "FROM `map_markers` as MK, `map_markers_icone` as MKI ";
		$sql .= "WHERE MK.marker_categorie = MKI.icone_id ";
		$sql .= "AND `icone_id` = '".$category."'";
		$sql .= "AND `marker_line_id` = '".$ligne_marker."'";
		$sql .= "AND `marker_actif` = 'Oui' ";
		$sql .= "AND `icone_actif` = 'Oui'";
		
        if (!$db->Query($sql)) $db->Kill($db->Error());
	
	    while (!$db->EndOfSeek()) {
            $row = $db->RowArray();
	        $count[] = $row;
	    }
		//reconfiguration du variable d'initialisation 
                $lat = "13.2" ;
                $lng = "19.159999999999968" ;
                $zoom = "2";
		return $count; // Accès au résultat
    } 
//__Affiche les informations pour les catégories
    function getAllCategory( ) {
        global $db;
		$count = array();
		
		$sql = "SELECT * ";
		$sql .= "FROM `map_markers_icone` as MKI ";
		$sql .= "WHERE MKI.icone_actif = 'Oui' ";
		
        if (!$db->Query($sql)) $db->Kill($db->Error());
	
	    while (!$db->EndOfSeek()) {
            $row = $db->RowArray();
	        $count[] = $row;
	    }


		
		return $count; // Accès au résultat
    }
}