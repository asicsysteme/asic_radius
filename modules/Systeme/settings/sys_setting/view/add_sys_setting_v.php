<?php defined('_MEXEC') or die; ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('sys_setting', 'Liste des Paramètres', Null, $exec = NULL, 'reply');      
		 ?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			
		</div>
		<div class="table-header">
			Formulaire: "<?php echo ACTIV_APP; ?>"

		</div>
		<div class="widget-content">
			<div class="widget-box">
				
<?php
//
$form = new Mform('add_sys_setting', 'add_sys_setting','',  'sys_setting', '');//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal

//Key
$key_array[]  = array('required', 'true', 'Insérer clé' );
$key_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$key_array[]  = array('remote', 'key#sys_setting#key', 'Cette région existe déja' );
$form->input('Clé Paramètre', 'key', 'text' ,3 , null, $key_array);

//Value
$hard_code_value = '<span class="help-block returned_span">En cas d\'un paramètre array respectez le format: {"key":"val_string","key2":2,...}</span>';
$value_array[]  = array('required', 'true', 'Insérer la valeur' );
$form->input('Valeur Paramètre', 'value', 'text' ,12 , null, $value_array, $hard_code_value);

//Comment
$comment_array[]  = array('required', 'true', 'Insérer description' );
$form->input('Déscription Paramètre', 'comment', 'text' ,12 , null, $comment_array);

//Region active
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)

$modul_array[]  = array('required', 'true', 'Choisir le module' );
$form->select_table('Module', 'modul', 7, 'sys_modules', 'id', 'description' , 'description', $indx = '-' ,
$selected=null, $multi=NULL, $where='etat=0', $modul_array);


//Button submit 
$form->button('Enregistrer le Paramètre');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
