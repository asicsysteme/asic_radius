
<?php
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	//
	
	//define index of column
	$columns = array( 
		0 =>'id_departement',
		1 =>'libelle',
		2 =>'region',
		3 =>'statut'
		
	);

	//Format all variables

	$colms = $tables = $joint = $where = $where_s=$sqlTot = $sqlRec = "";
    // define used table.
	$tables .= " ref_departement, ref_region ";
    // define joint and table relation
	$joint .= " AND ref_region.id = ref_departement.id_region";
	// set sherched columns.(the final colm without comma)
	$colms .= " ref_departement.id AS id_departement, ";	
	$colms .= " ref_departement.departement as libelle, ";
	$colms .= " ref_region.region as region, ";

	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
	$notif_colms = TableTools::line_notif_new('ref_departement', 'departements');

	$colms .= $notif_colms;

	//difine if user have rule to show line depend of etat 
	$where_etat_line = TableTools::where_etat_line('ref_departement', 'departements');

    

	// check search value exist
	if( !empty($params['search']['value']) or Mreq::tp('id_search') != NULL)  {

		$serch_value = str_replace('+',' ',$params['search']['value']);
		
        //Format where in case joint isset  
	    /*$where .= $joint == NULL? " WHERE " : " AND ";*/

		$where_s .="AND ( ref_departement.departement LIKE '%".$serch_value."%' ";
		$where_s .=" OR ref_region.region '%".$serch_value."%' )";

		$where_s .= TableTools::where_search_etat('ref_departement', 'departements', $serch_value);

	}

	$where .= $where_etat_line;
	$where .= $joint;
	$where .= $where_s == NULL ? NULL : $where_s;
	// getting total number records without any search
	$sql = "SELECT $colms  FROM  $tables";
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && $where != NULL) {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}

	//if we use notification we must ordring lines by nofication rule in first
	//Change ('notif', status) with ('notif', column where notif code is concated)
	//on case of order by other parametre this one is disabled 
    $order_notif = TableTools::order_bloc($params['order'][0]['column']);

 	$sqlRec .=  " ORDER BY $order_notif  ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";


    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
    $totalRecords = $db->RowCount();

    //Export data to CSV File
    if( Mreq::tp('export')==1 )
    {
    	
    	$file_name = 'departements_list';
    	$title     = 'Liste des departements ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Departement', 'Region','Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>10, 'Departement'=>45, 'Region'=>35, 'Statut'=>10);
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
	
