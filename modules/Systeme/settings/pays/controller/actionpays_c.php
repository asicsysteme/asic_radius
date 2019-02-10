<ul class="dropdown-menu dropdown-menu-right">
<?php 


$pays = new Mpays();
$pays->id_pays= Mreq::tp('id');
$pays->get_pays();



$action = new TableTools();
$action->line_data = $pays->pays_info;
$action->action_line_table('pays', 'ref_pays',$pays->pays_info['creusr'],'deletepays');
?>

</ul>