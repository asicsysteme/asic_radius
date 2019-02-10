<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_profiles
//Created : 02-02-2019
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$cp_profiles = new Mcp_profiles();
$cp_profiles->id_cp_profiles = Mreq::tp('id');
$cp_profiles->get_cp_profiles();



$action = new TableTools();
$action->line_data = $cp_profiles->cp_profiles_info;
$action->action_line_table('cp_profiles', 'cp_profiles', $cp_profiles->cp_profiles_info['creusr'], 'delete_cp_profiles');


echo '</ul>';