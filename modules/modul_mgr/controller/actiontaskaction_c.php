<ul class="dropdown-menu dropdown-menu-right">
<?php 

$id  = Mreq::tp('id');
$idc = MInit::crypt_tp('id',$id);


$task = new Mmodul();
$task->id_task_action = Mreq::tp('id');
$task->get_task_action();





	echo '<li><a href="#" class="this_url"  data="'.$idc.'" rel="edittaskaction" ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Task Action </a></li>';
	echo '<li><a href="#" class="this_url"  data="'.$idc.'" rel="dupliqtaskaction" ><i class="ace-icon fa fa-copy bigger-100"></i> Dupliquer Task Action </a></li>';
	echo '<li><a href="#" class="this_exec"  data="'.$idc.'" rel="deletetaskaction" ><i class="ace-icon fa fa-trash red bigger-100"></i> Supprimer Task Action </a></li>';

?>
</ul>