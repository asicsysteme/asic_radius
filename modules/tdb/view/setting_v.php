<?php 
//List Modules normal
//Creat new objet template
$modules = new Template();
//Call modul list methode
$modules->list_modul_have_setting();
//Fill Modul liste Array
$arr_modul = $modules->modul_have_setting;

?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">

	</div>
</div>
<div class="page-header">
	<h1>
		Espace Paramétrage des Réferenciel
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
<div class="row"><!-- Main ROW -->
    <div class="col-xs-8">
        <div class="widget-box">
			<div class="widget-header">
				<h5 class="widget-title"><?php echo 'Paramétrage Général' ; ?></h5>
			</div>
			<div class="widget-body">
				<div class="widget-main">
<?php 
//List Modules Setting
//Creat new objet template
$module_s = new Template();
//Call modul list methode
if($module_s->list_modul_setting('Systeme')){
	//Fill Modul liste Array
    $arr_modul_s = $module_s->moduls_setting;
    foreach ($arr_modul_s as $key => $value) {
    	$btn = '<a href="#" title="'.$value['descrip'].'" class="btn btn-white btn-info btn-bold this_url" rel="'.$value['app'].'" ><i class="ace-icon fa fa-'.$value['class'].' bigger-120 blue"></i>'.$value['descrip'].'
			</a>';
        print $btn;			
    }
}else{
	print('Vous ne disposez d\'aucun module dans cette section');
}
?>				
				</div>
            </div>
        </div>
<?php 
    foreach ($arr_modul as $key => $row){
?>
        <div class="widget-box">
			<div class="widget-header">
				<h5 class="widget-title"><?php echo $row['descrip'] ; ?></h5>
			</div>
			<div class="widget-body">
				<div class="widget-main">
<?php 
//List Modules Setting
//Creat new objet template
$module_s = new Template();
//Call modul list methode
if($module_s->list_modul_setting($row['modul'])){
	//Fill Modul liste Array
    $arr_modul_s = $module_s->moduls_setting;
    foreach ($arr_modul_s as $key => $value) {
    	$btn = '<a href="#" title="'.$value['descrip'].'" class="btn btn-white btn-info btn-bold this_url" rel="'.$value['app'].'" ><i class="ace-icon fa fa-'.$value['class'].' bigger-120 blue"></i>'.$value['descrip'].'
			</a>';
        print $btn;			
    }
}else{
	print('Vous ne disposez d\'aucun module dans cette section');
}

?>													
				</div>
            </div>
        </div>
<?php 
    }
?>
    </div>
</div><!-- End ROW -->

