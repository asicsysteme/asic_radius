<?php

function tooltip_dae_demander($id_projet){
global $db ;
$query="select dae_demander.carte, CONCAT(demploi.nom,' ',demploi.prenom) as nom,CONCAT('upload/demploi/',YEAR(demploi.datdem),'/',demploi.id,'/',demploi.photo) as photo from dae_demander,demploi where dae_demander.carte = demploi.id and dae_demander.id_projet = $id_projet";
if (! $db->Query($query)) $db->Kill($db->Error());

$countrow=$db->RowCount();
 if($countrow==0){
	$output = " Pas de données";
 }else{
  $output = '<table>';
    while (! $db->EndOfSeek()) {
    $row = $db->Row();
	$carte = $row->carte;
	$nom = $row->nom; 
	$link = "#";
	$photo = file_exists($row->photo)?
	criimg($row->photo,30,30,'png',$row->nom.' ('.$row->carte.')'):'<img src="img/user-thumb.png"  width="30" height="30" style="border:" title="'.$row->nom.' ('.$row->carte.')" >';
	 $output .= "<tr> <td><a href=\"$link\">$photo</a></td><td><a href=\"$link\"><span class=\" tooltipsterlink \">$nom ($carte)</span></td></tr>";
	}
	$output .=  "</table>";
 }
 return $output;

}
//Usage
//Toolip dae_demander
if(tg('tdd')!=0){echo tooltip_dae_demander(tg('tdd'));}


?>
