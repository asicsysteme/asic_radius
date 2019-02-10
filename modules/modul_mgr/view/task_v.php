<?php

 
 $info_modul = new Mmodul();
 $info_modul->id_modul = Mreq::tp('id');
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_modul->get_modul())
 { 	

 	exit('3#'.$info_modul->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
 }
   
    $id_modul   = Mreq::tp('id');
    $id_modul_c = md5(MInit::cryptage($id_modul,1));
 ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
		TableTools::btn_add('modul', 'Liste Module', NULL, $exec = NULL, 'reply'); 
		

		?>

					
	</div>
</div> 
<div class="page-header">
	<h1>
		Applications de module : 
		<small>
			<i class="ace-icon fa fa-angle-double-left"></i>
		</small>
		<?php  echo $info_modul->modul_info['description'] .' - '.$id_modul.' -'?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			<div class="pull-right tableTools-container">
				<div class="btn-group btn-overlap">
					
					<?php TableTools::btn_add('addtask','Ajouter Application Task',MInit::crypt_tp('id',$id_modul));
					TableTools::btn_csv('task','Exporter Liste');
		            TableTools::btn_pdf('task','Exporter Liste');

					?>
					
				</div>
			</div>
		</div>

		<div class="table-header">
			Liste des Applications de Module : "<?php  $info_modul->Shw('modul',1) ?>"
		</div>
		<div>
			<table id="task_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Déscription
						</th>
						<th>
							Fichier
						</th>
						<th>
							Type d'affichage
						</th>
						<th>
							#
						</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">


$(document).ready(function(){
	
	    var table = $('#task_grid').DataTable({
		    bProcessing: true,
		    serverSide: true,
		    ajax_url:"task",
		    extra_data : "id=<?php echo Mreq::tp('id');?>",
		

            aoColumns: [
               {"sClass": "center","sWidth":"5%"}, //
                   
               {"sClass": "left","sWidth":"50%"},
               {"sClass": "left","sWidth":"20%"},
               {"sClass": "left","sWidth":"20%"},
               {"sClass": "center","sWidth":"5%"},
            ],

           

    });

	$('#task_grid').on('click', 'tr button', function() {
		var $row = $(this).closest('tr')
	    //alert(table.cell($row, 0).data());
	    append_drop_menu('task', table.cell($row, 0).data(), '.btn_action')
    });

    $('.export_csv').on('click', function() {
	    csv_export(table, 'csv');
    });
    $('.export_pdf').on('click', function() {
	    csv_export(table, 'pdf');
    });


});
</script>















































