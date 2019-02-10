<ul class="dropdown-menu dropdown-menu-right">
<?php 


$region = new Mregion();
$region->id_region= Mreq::tp('id');
$region->get_region();



$action = new TableTools();
$action->line_data = $region->region_info;
$action->action_line_table('regions', 'ref_region',$region->region_info['creusr'],'deleteregion');

?>

</ul>
