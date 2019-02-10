<?php
//Function check existing fields on table
function get_fields($table, $colomn, $value, $colwher = null, $valwher = null)
{
	global $db;

	$return = Null;

	$colwher = $colwher == null ? 1 : $colwher;
	$valwher = $valwher == null ? 1 : $valwher;

	
	$q = MySQL::SQLValue("%".$value."%");

	$sound_x = " OR (SOUNDEX(r_social) = SOUNDEX( $q ))";

	$sql = "SELECT $table.$colomn as filds FROM $table 
		where $colomn LIKE  $q  AND $colwher = $valwher";
	if (! $db->Query($sql)) $db->Kill($sql );

	while (! $db->EndOfSeek()) {
		$row = $db->Row();
		$cname =  $row->filds;
		$return .= "$cname\n ";

	}
	//exit($sql);
	return print ($return);
}

//exit(Mreq::tp('tbl').'  '.Mreq::tp('col').'  '. Mreq::tp('q'));
get_fields(Mreq::tp('tbl'),Mreq::tp('col'), Mreq::tp('q'), Mreq::tp('colwer'), Mreq::tp('valwer'));




