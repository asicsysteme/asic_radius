 <?php

 function shopdf($field){
    

    $iddoc = $field;
 	global $db ;

 	$query="select doc  from archive where id = $iddoc";

 	if (!$db->Query($query)) $db->Kill("0#".$db->Error());
 	$countrow = $db->RowCount();

 	if($countrow == 0){
 		exit("0#fichier n'existe pas dans la base de données");
 	}	
 	$array = $db->RowArray();
 	$targ  = $array['doc'];

 	if (!file_exists($targ))
 	{		 
 		exit("0#fichier n'existe pas dans les archives");
 	}else{
 		$path_parts = pathinfo($targ);
 		$ext = $path_parts['extension'];
 		$new_name = MD5(date('dd-m-Y H:i:s').$targ);
 		$new_targ = MPATH_TEMP.$new_name.'.'.$ext;
        //Copy to temp folder first
        if (!copy($targ, $new_targ)) {
            exit("0#La copie $file du fichier a échoué...");
        }
 		exit("1#$new_targ");
 	}

 }
//Execute
shopdf(Mreq::tp("f"));




 ?>