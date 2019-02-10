<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS ASIC ERP
// Modul: modul_mgr
//Created : 30-11-2018
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$modul_workflow = new Mmodul();
$modul_workflow->id_modul_workflow = Mreq::tp('id');
$modul_workflow->get_info_workflow();



$action = new TableTools();
$action->line_data = $modul_workflow->info_workflow;
$action->action_line_table('modul_workflow', 'sys_workflow', $modul_workflow->info_workflow['creusr'], 'deletemodul_workflow');


echo '</ul>';