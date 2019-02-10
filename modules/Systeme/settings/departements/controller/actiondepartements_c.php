<ul class="dropdown-menu dropdown-menu-right">
<?php 

$departement = new Mdept();
$departement->id_departement= Mreq::tp('id');
$departement->get_departement();


$action = new TableTools();
$action->line_data = $departement->departement_info;
$action->action_line_table('departements', 'ref_departement',$departement->departement_info['creusr'],'deletedepartement');


?>

</ul>
