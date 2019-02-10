<ul class="dropdown-menu dropdown-menu-right">
<?php 

$ville = new Mville();
$ville->id_ville= Mreq::tp('id');
$ville->get_ville();


$action = new TableTools();
$action->line_data = $ville->ville_info;
$action->action_line_table('villes', 'ref_ville',$ville->ville_info['creusr'],'deleteville');


?>

</ul>
