<ul class="dropdown-menu dropdown-menu-right">
<?php 


$service = new Mservice();
$service->id_service = Mreq::tp('id');
if(!$service->get_service()){
	exit('0#Erreur lecture de ligne');
}


$action = new TableTools();
$action->line_data = $service->service_info;

$action->action_line_table('services', 'sys_services', $service->service_info['creusr'], 'deleteservices')
?>

</ul>
