<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_users
//Created : 03-02-2019
//Controller

echo '<ul class="dropdown-menu dropdown-menu-right">';
$cp_users = new Mcp_users();
$cp_users->id_cp_users = Mreq::tp('id');
if(!$cp_users->get_cp_users())
{
	exit('Problème get Data');
}



$action = new TableTools();
$action->line_data = $cp_users->cp_users_info;
$action->action_line_table('cp_users', 'radcheck', $cp_users->cp_users_info['creusr'], 'delete_cp_users');


echo '</ul>';