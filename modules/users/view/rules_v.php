<?php 


$info_user = new Musers;
$info_user->id_user = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_user->get_user())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
$moduls = new Mmodul;
$modul_array = $moduls->Get_list_modul($info_user->g('service'));
?>

<div class="page-header">
	<h1>
		Gestion Permissions Utilisateur 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>
		<?php echo ' ('.$info_user->Shw('fnom',1).'  '.$info_user->Shw('lnom',1).' -'.$info_user->id_user.'-)' . ' Service: '.$info_user->Shw('service_user',1);?>
	</h1>
</div><!-- /.page-header -->
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php TableTools::btn_add('user', 'Liste Utilisateurs', Null, $exec = NULL, 'reply');   ?>
		
	</div>
</div>
<div class="tableTools-container">
	<div class="btn-group btn-overlap">
		<?php
$selecte_modul = "<select id='go_to_modul'>";		
foreach ($modul_array as $key => $value) {
	$selecte_modul .= "<option value='".$value['modul']."'>".$value['description']."</option>";
}
$selecte_modul .= "</select>";
echo $selecte_modul;
		 if($info_user->g('service') == 1) {?>
		<input  name="form-field-checkbox" class="check-all-all ace ace-checkbox-2" type="checkbox">
		<span class="lbl"> Donner permission à tout les les modules -GRANT-</span>	
		<?php }

		?>

	</div>
</div>
<div class="row">
	
	<form novalidate="novalidate" method="post" class="form-horizontal" id="addrules" action="#"> 
	<div class="col-xs-12" id="table-permission">
		<!-- PAGE CONTENT BEGINS -->
		<input name="verif" type="hidden" value="<?php MInit::form_verif('addrules');?>" />
		<input name="userid" type="hidden" value="<?php echo Mreq::tp('id');?>"/>
		<input name="id" type="hidden" value="<?php echo Mreq::tp('id');?>"/>
		<input name="idc" type="hidden" value="<?php echo Mreq::tp('idc');?>"/>
		<input name="idh" type="hidden" value="<?php echo Mreq::tp('idh');?>"/>
		<div class="row">

			<?php 



$service_user = '-'.$info_user->Shw('service',1).'-';

foreach($modul_array as $check) {
   if (strpos($check['services'], $service_user)) {
      $found_service = true;
   }
}

if(!isset($found_service))
{
	MInit::big_message("Le Service de  ".$info_user->Shw('service_user',1)." ne dispose pas de module !", 'danger');
	exit();
	
}


			
foreach ($modul_array as $row) { 
//if(strpos($row['services'], '-'.$info_user->Shw('service',1).'-') && $row['modul'] != 'Systeme'){			
if($row['modul'] != 'Systeme'){
			?>
			<div id="<?php echo $row['modul']; ?>" class="col-xs-12 col-sm-6 widget-container-col">
				<div  class="widget-box widget-color-blue">
					<!-- #section:custom/widget-box.options -->
					<div  class="widget-header">
						<h5 class="widget-title bigger lighter">
							<i class="ace-icon fa fa-table"></i>
							<?php echo $row['description']; ?>
							<button class="btn pull-right btn-white btn-warning btn-bold" id="add_perm" type="submit"><i class="ace-icon fa fa-floppy-o bigger-120 blue"></i> Enregistrer les Permissions</button>
							
						</h5>


						
					</div>

					<!-- /section:custom/widget-box.options -->
					<div class="widget-body scrolling">
						<div class="widget-main no-padding">
							<table class="table table-striped table-bordered table-hover">
								<thead class="thin-border-bottom">
									<tr>
										

										<th>
											ID
										</th>
										<th>
											Description
										</th>
										<th width = "5%">
											<input  name="form-field-checkbox" class="check-all ace ace-checkbox-2" type="checkbox">
											<span class="lbl"></span>
										</th>
									</tr>
								</thead>

								<tbody>
<?php //liste user

$query_modul = new Mmodul;
global $db;

                    
if (!$db->Query($query_modul->Get_action_modul($row['id'], Mreq::tp('id')))) $db->Kill($db->Error());
while (!$db->EndOfSeek()) {

        $row = $db->Row();
        //if(strpos($row->service, '-'.$info_user->Shw('service',1).'-')){
        $type = $row->type == 0 ? ' <span class="pull-right label label-info arrowed-in-right arrowed">Lien menu</span>' : ' <span class="pull-right label label-success arrowed-in-right arrowed">Autorisation</span>'; 
        $etat_line = $row->type == 0 ? ' <span class="badge badge-pink">'.$row->etat_line.'</span>' : null;
?>
									<tr>
										<td>
											<?php echo $row->action_id; ?>
										</td>

										

										<td>

											<?php echo $row->app_name .' '.$etat_line.$type; ?>
											<input name="app_name<?php echo $row->action_id; ?>" type="hidden" value="<?php echo $row->app_name; ?>" />
											<input name="app_id<?php echo $row->action_id; ?>" type="hidden" value="<?php echo $row->app_id; ?>" />
											<input name="type<?php echo $row->action_id; ?>" type="hidden" value="<?php echo $row->type; ?>" />
											<input name="idf<?php echo $row->action_id; ?>" type="hidden" value="<?php echo $row->idf; ?>" />


										</td>

										<td class="hidden-480">
											<label>
												<input <?php  if($row->exist_rule == 1)echo 'checked'; ?>  name="action_id[]"  value="<?php echo $row->action_id; ?>" class="ace ace-checkbox-2" type="checkbox">
														<span class="lbl"></span>
													</label>
										</td>
									</tr>
<?php 
   // }//End if service
}//End While ?> 
									

									
								</tbody>
							</table>
						</div>
						
					</div>
				</div>
			</div><!-- /.span -->
<?php

}// end if check service
 }// end for each ?>
		</div><!-- /.row -->
		<!-- PAGE CONTENT END -->
		<div id="data"></div>
	</div>
    </form>
</div>
<script>

	$(function () {
		$('.check-all').on('click', function () {
			$(this).closest('table').find(':checkbox').prop('checked', this.checked);
		});

		$('.check-all-all').on('click', function () {
			$(':checkbox').prop('checked', this.checked);
		});

		$("#addrules").validate({
			execApp:"rules",
			execNext:"user",
		});
		$('.scrolling').ace_scroll({
			size: 300
		});
        
        $('#go_to_modul').on('change', function () {
        	
            $('html, body').animate({
                    scrollTop: $('#'+$(this).val()).offset().top
                }, 1000);
            
        });
		/*var $container = $('#addrules'),
		$scrollTo = $('#row_8');

		
 
// Or you can animate the scrolling:
        $container.animate({
        	scrollTop: $scrollTo.offset().top - $container.offset().top + $container.scrollTop()
        });​*/
});

</script>