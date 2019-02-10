<ul class="dropdown-menu dropdown-menu-right">
<?php 


$modul = new Mmodul();
$modul->id_modul = Mreq::tp('id');
$modul->get_modul();


$action = new TableTools();
$action->line_data = $modul->modul_info;
$action->action_line_table('modul', 'sys_modules');


?>
</ul>
