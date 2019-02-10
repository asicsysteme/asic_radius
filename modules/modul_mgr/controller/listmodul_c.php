
<?php
	
	global $db;
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;
	//
	//SELECT `fnom`, `lnom`, `servic`,`active` FROM `users_sys`



	//define index of column
	$columns = array( 
		0 =>'id',
		1 =>'modul',
		2 =>'description',
		3 =>'app_modul',
		4 =>'setting',
		5 =>'modul_setting',
		
	);

	//Format all variables

	$colms = $tables = $joint = $where = $sqlTot = $sqlRec = "";
    // define used table.
	$tables .= " sys_modules ";
    // define joint and rtable elation
	$joint .= "";
	// set sherched columns.(the final colm without comma)
	$colms .= " sys_modules.id, ";
	//$colms .= " CONCAT('<div class=\"user\"><img class=\"nav-user-photo\" alt=\"\" src=\"./upload/useres/',users_sys.id,'/',MD5(users_sys.photo),'48x48.png\"></div>') as photo, ";
	
	$colms .= " sys_modules.modul, ";
	$colms .= " sys_modules.description, ";
	//$colms .= " users_sys.active as statut ";
	$colms .= " sys_modules.app_modul, ";
	$colms .= " CASE sys_modules.is_setting WHEN 0 THEN 'Base' WHEN 1 THEN 'Paramétrage' ElSE 'Sous Modul' END as setting ,  ";
	$colms .= " sys_modules.modul_setting ";



    
    
	// check search value exist
	if( !empty($params['search']['value']) ) {

		$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
	    $where .= $joint == ""? " WHERE " : " AND ";


		$where .=" sys_modules.modul LIKE '%".$serch_value."%' ";    
		$where .=" OR sys_modules.description LIKE '%".$serch_value."%' ";
        $where .=" OR  sys_modules.app_modul LIKE '%".$serch_value."%' ";
        $where .=" OR  sys_modules.modul_setting LIKE '%".$serch_value."%' ";
        

	}


	// getting total number records without any search
	
	$sql = "SELECT $colms  FROM  $tables $joint ";
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && $where != '') {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}


 	$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
    if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
    $totalRecords = $db->RowCount();

    //Export data to CSV File
    if( Mreq::tp('export')==1 )
    {
    	
    	$file_name = 'modul_list';
    	$title     = 'Liste Modules ';
    	if(Mreq::tp('format')=='csv')
    	{
    		$header    = array('ID', 'Nom Module', 'APP_BASE', 'Statut');
    		Minit::Export_xls($header, $file_name, $title);
    	}else{
    		$header    = array('ID'=>10, 'Nom Module'=>50, 'App_Base'=>20, 'Statut'=>20);
    		Minit::Export_pdf($header, $file_name, $title);
    	}
    	  	

    }

	//
    if (!$db->Query($sqlRec)) $db->Kill($db->Error()." SQLREC $sqlRec");
	//
   
	//iterate on results row and create new index array of data
	 while (!$db->EndOfSeek()) {
      $row = $db->RowValue();
	  $data[] = $row;
	 }
	
	//exit($sqlRec);

	$json_data = array(
			"draw"            => intval( $params['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data   // total data array
			);

	echo(json_encode($json_data));  // send data as json format
		

?>
	
