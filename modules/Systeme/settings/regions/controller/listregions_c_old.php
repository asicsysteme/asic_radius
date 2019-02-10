<?php 
//SYS ASIC ERP
// Modul: clients => Controller Liste
		
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	//define index of column
$columns = array( 
		0 =>'id_region',
		1 =>'libelle', 
		2 =>'pays',
		3 =>'statut',
		
	);
		

//Format all variables

$colms = $tables = $joint = $where = $where_s = $sqlTot = $sqlRec = "";
// define used table.
	$tables .= " ref_region, ref_pays ";
    // define joint and rtable elation
	$joint .= " AND ref_region.id_pays = ref_pays.id ";
	// set sherched columns.(the final colm without comma)
	$colms .= " ref_region.id AS id_region, ";
	$colms .= " ref_region.region as libelle, ";
	$colms .= " ref_pays.pays as pays, ";
	

	
	
	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif_new('ref_region', 'regions');
    $colms .= $notif_colms;
    //difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('ref_region', 'regions');

	// check search value exist
	if( !empty($params['search']['value']) or Mreq::tp('id_search') != NULL) 
	{

		$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
	    $where_s .= $joint == NULL? " WHERE " : " AND ";


    $where_s .=" AND (ref_region.region LIKE '%".$serch_value."%' ";  
    $where_s .= " OR ref_pays.pays LIKE '%".$serch_value."%' ";
    $where_s .= TableTools::where_search_etat('reg_region', 'regions', $serch_value);
}
  
    
	//var_dump($where);

	//$where = $where == NULL ? NULL : $where;
	
	/**
	 * Check if Query have JOINT then format WHERE puting WHERE 1=1 before where_etat_line
	 * Check if Search active then and non JOINT format WHERE puting WHERE 1=1 before where_etat_line
	 */
	
	/*$where_etat_line =  $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;
	$where_etat_line =  $where_s == NULL && $joint == NULL ? " WHERE 1=1 ".$where_etat_line : $where_etat_line;

	$where .= $where_etat_line;*/
        $where .= $where_etat_line;
	$where .= $joint;
	$where .= $where_s == NULL ? NULL : $where_s;
	// getting total number records without any search
	$sql = "SELECT $colms  FROM  $tables  ";
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && $where != NULL) {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}

	//if we use notification we must ordring lines by nofication rule in first
	//Change ('notif', status) with ('notif', column where notif code is concated)
	//on case of order by other parametre this one is disabled (Check Export query)
	
    $order_notif = TableTools::order_bloc($params['order'][0]['column']);

 	$sqlRec .=  " ORDER BY $order_notif  ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";


    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
    $totalRecords = $db->RowCount();

    //Export data to CSV File
    if( Mreq::tp('export')==1 )
    {
    	
    	$file_name = 'region_list';
    	$title     = 'Liste des régions ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Region','Pays' ,'Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>10, 'Region'=>40, 'Pays'=>30, 'Statut'=>20);
    		Minit::Export_pdf($header, $file_name, $title);
    	}
    	  	

    }
    
	//exit($sqlRec);
    if (!$db->Query($sqlRec)) $db->Kill($db->Error()." SQLREC $sqlRec");
	//
    
	//iterate on results row and create new index array of data
	 while (!$db->EndOfSeek()) {
      $row = $db->RowValue();
	  $data[] = $row;
	 }
	//while( $row = mysqli_fetch_row($queryRecords) ) { 
		//$data[] = $row;
	//}	

	$json_data = array(
			"draw"            => intval( $params['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval( $totalRecords),
			"data"            => $data   // total data array
			);

	echo json_encode($json_data);  // send data as json format
		

?>
	
