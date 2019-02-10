<?php 

global $db ;
$lastmaj = $db->QuerySingleValue("SELECT TIMESTAMPDIFF(MINUTE,max(dat),NOW()) as diff_in_hours FROM maj_sys");
if($lastmaj > 20 ){
 
 
//  Base de données Distante
// ---------------------------------------------------
 	$hostd = 'localhost';
	$userd = 'root';
	$passd = 'soniko';
	$dbased = 'onapesite';
	

	
	define('HOSTD',$hostd);
	define('USERDBD',$userd);
	define('PASSD',$passd);
	define('DBASED',$dbased);
	$dbd = new MySQL(true, DBASED, HOSTD, USERDBD, PASSD);
// ---------------------------------------------------


// Execute local requete on remote server
// --------------------------------------------------- 
  global $db;
$fullquery="SELECT req,id from temprequet where stat=0 ";
 
if (! $db->Query($fullquery)) $db->Kill($db->Error());
$nbrlocalreq = $db->RowCount();
 while (! $db->EndOfSeek()) {
    $row = $db->Row();
	
	$dbd->Query($row->req);
	
	
	
	
	
 }
$db->Query("update temprequet set stat=1 where stat=0"); 
// ---------------------------------------------------


// Execute Remote requete on locoal server
// --------------------------------------------------- 
  global $db;
$fullquery="SELECT req,id from temprequet where stat=0 ";
 
if (! $dbd->Query($fullquery)) $dbd->Kill($dbd->Error());
$nbrremotreq = $dbd->RowCount();
 while (! $dbd->EndOfSeek()) {
    $row = $dbd->Row();
	
	if (! $db->Query($row->req))
	{ $db->Kill($db->Error());
	}else{ 
	  $dbd->Query("update temprequet set stat=1 where id=".$row->id);
	}
	
	
 }
// ---------------------------------------------------

// Insert log Last update
// ---------------------------------------------------
$nbrreq=$nbrlocalreq+$nbrremotreq;
$lastmaj="Insert into maj_sys(nbrreq,user)values($nbrreq,".$_SESSION['userid'].")";
if (! $db->Query($lastmaj)) $db->Kill($lastmaj);

exit("ok");
}else{exit("not yet");}
?>

