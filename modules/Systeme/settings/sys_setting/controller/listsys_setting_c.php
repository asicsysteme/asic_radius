<?php 
//SYS ASIC ERP
// Modul: clients => Controller Liste

global $db;
$params = $columns = $totalRecords = $data = array();

$params = $_REQUEST;

	//define index of column
$columns = array( 
	0 =>'id_setting',
	1 =>'keyt', 
	2 =>'valuet',
	3 =>'commentt',
	4 =>'modul',
	3 =>'statut',

);


//Format all variables

$colms = $tables = $joint = $where = $where_s = $sqlTot = $sqlRec = "";
// define used table.
$tables .= " sys_setting, sys_modules";
    // define joint and rtable elation
$joint .= " WHERE sys_setting.modul = sys_modules.id ";
	// set sherched columns.(the final colm without comma)
$colms .= " sys_setting.id AS id_setting, ";
$colms .= " sys_setting.key as keyt, ";
$colms .= " sys_setting.value as valuet, ";
$colms .= " sys_setting.comment as commentt, ";
$colms .= " sys_modules.modul as modul ";




	//define notif culomn to concatate with any colms.
	//this is change style of button action to red
//$notif_colms = TableTools::line_notif_new('sys_setting', 'sys_setting');
//$colms .= $notif_colms;
    //difine if user have rule to show line depend of etat 
//$where_etat_line = TableTools::where_etat_line('sys_setting', 'sys_setting');

	// check search value exist
if( !empty($params['search']['value']) or Mreq::tp('id_search') != NULL) 
	{

		$serch_value = str_replace('+',' ',$params['search']['value']);
        //Format where in case joint isset  
		//$where_s .= $joint == NULL? " WHERE " : " AND ";


		$where_s .=" AND (sys_setting.id LIKE '%".$serch_value."%' ";  
		$where_s .= " OR sys_setting.key LIKE '%".$serch_value."%' ";
		$where_s .= " OR sys_setting.value LIKE '%".$serch_value."%' ";
		$where_s .= " OR sys_setting.comment LIKE '%".$serch_value."%' ";
		$where_s .= " OR sys_modules.modul LIKE '%".$serch_value."%' ";
		$where_s .= TableTools::where_search_etat('sys_setting', 'sys_setting', $serch_value);
	}


	
	//$where .= $where_etat_line;
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
	
	$order_notif = null;

	$sqlRec .=  " ORDER BY $order_notif  ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";


	if (!$db->Query($sqlTot)) $db->Kill($db->Error()." SQLTOT $sqlTot");
	//
	$totalRecords = $db->RowCount();

    

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
	
