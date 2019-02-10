<ul class="dropdown-menu dropdown-menu-right">
<?php 


$sys_setting = new Msetting();
$sys_setting->id_setting= Mreq::tp('id');

$sys_setting->get_setting();



$action = new TableTools();
$action->line_data = $sys_setting->setting_info;
$action->action_line_table('sys_setting', 'sys_setting',$sys_setting->setting_info['creusr'],'delete_sys_setting');

?>

</ul>
