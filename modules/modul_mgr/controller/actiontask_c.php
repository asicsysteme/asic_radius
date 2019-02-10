<ul class="dropdown-menu dropdown-menu-right">
<?php 

$id  = Mreq::tp('id');
$idc = MInit::crypt_tp('id',$id);


$task = new Mmodul();
$task->id_task = $id;
if(!$task->get_task())
{    
    exit('Infos Task ERROR');
}


if($task->task_info['type_view'] == 'list'){
	echo '<li><a href="#" class="this_url"  data="'.$idc.'" rel="taskaction" ><i class="ace-icon fa fa-external-link bigger-100"></i> Liste Task Action</a></li>';
	echo '<li><a href="#" class="this_url"  data="'.$idc.'" rel="addtaskaction" ><i class="ace-icon fa fa-plus bigger-100"></i> Ajouter Task Action</a></li>';
	echo '<li><a href="#" class="this_url"  data="'.$idc.'" rel="addetatrule" ><i class="ace-icon fa fa-eye bigger-100"></i> Ajouter affichage WF </a></li>';
	echo '<li><a href="#" class="this_url"  data="'.$idc.'" rel="edittask" ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Task </a></li>';
	echo '<li><a href="#" class="this_url"  data="'.$idc.'" rel="workflow" ><i class="ace-icon fa fa-pencil bigger-100"></i> Afficher Work Flow </a></li>';
	echo '<li><a href="#" class="this_exec red"  data="'.$idc.'" rel="deletetask" ><i class="ace-icon fa fa-remove red bigger-100"></i> Supprimer Task </a></li>';
	
}else{
	echo '<li><a href="#" class="this_url"  data="'.$idc.'" rel="edittask" ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Task </a></li>';
	echo '<li><a href="#" class="this_exec red"  data="'.$idc.'" rel="deletetask" ><i class="ace-icon fa fa-remove  bigger-100"></i> Supprimer Task </a></li>';
}
?>
</ul>
