<?php defined('_MEXEC') or die; ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('pays', 'Liste des pays', Null, $exec = NULL, 'reply');      
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
$form = new Mform('addpays', 'addpays','',  'pays', '');//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
//$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations pays');
//Titre bloc 
//$form->bloc_title('Informations pays');
//pays
$pays_array[]  = array('required', 'true', 'Insérer le pays' );
$pays_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$pays_array[]  = array('remote', 'pays#ref_pays#pays', 'Ce pays existe déja' );
$form->input('Pays', 'pays', 'text' ,6 , null, $pays_array);

//Nationalite

$nationalite_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Nationalité', 'nationalite', 'text' ,6 , null, $nationalite_array);

//Code pays 
$form->input('Code du pays', 'alpha', 'text' ,6 , null, null);

$form->step_end();
//Button submit 
$form->button('Enregistrer le pays');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
