<?php
defined('_MEXEC') or die;
//Get all region info 
 $info_setting = new Msetting();
//Set ID of Module with POST id
 $info_setting->id_setting = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D')  or !$info_setting->get_setting())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_setting->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 ?>
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
$form = new Mform('edit_sys_setting', 'edit_sys_setting','',  'sys_setting', '');//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal
$form->input_hidden('id', $info_setting->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));
//Key
$key_array[]  = array('required', 'true', 'Insérer clé' );
$key_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$key_array[]  = array('remote', 'key#sys_setting#key', 'Cette région existe déja' );
$form->input('Clé Paramètre', 'key', 'text' ,3 , $info_setting->g('key'), $key_array);

//Value
$value_array[]  = array('required', 'true', 'Insérer la valeur' );
$form->input('Valeur Paramètre', 'value', 'text' ,12 , htmlentities($info_setting->g('value')), $value_array);

//Comment
$comment_array[]  = array('required', 'true', 'Insérer description' );
$form->input('Déscription Paramètre', 'comment', 'text' ,12 , $info_setting->g('comment'), $comment_array);

//Region active
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)

$modul_array[]  = array('required', 'true', 'Choisir le module' );
$form->select_table('Module', 'modul', 7, 'sys_modules', 'id', 'description' , 'description', $indx = '-' ,
$selected=$info_setting->g('modul'), $multi=NULL, $where='etat=0', $modul_array);


//Button submit 
$form->button('Modifier  le Paramètre');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
