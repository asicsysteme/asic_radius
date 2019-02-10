<div class="page-header">
	<h1>
		Gestion Modules
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
					
					<?php 
					TableTools::btn_add('addmodul', 'Ajouter Module');
					TableTools::btn_add('addmodulsetting', 'Ajouter Sous Module ', Null, Null, 'cogs');
					TableTools::btn_add('importmodul', 'Importer Module', Null, 1, 'download');
					TableTools::btn_csv('modul','Exporter Liste');
					TableTools::btn_pdf('modul','Exporter Liste');
                    ?>
					
					
				</div>
			</div>
		</div>

		<div class="table-header">
			Liste "Modules" 
		</div>
		<div>
			<table id="modul_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						
						<th>
							Nom Modul
						</th>
						<th>
							Déscription
						</th>
						<th>
							App Base
						</th>
						<th>
							M.Param
						</th>
						<th>
							Modul Base
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
	
	var table = $('#modul_grid').DataTable({
		bProcessing: true,
		serverSide: true,
		ajax_url:"modul",
		

                aoColumns: [
                    {"sClass": "center","sWidth":"3%"}, //
                   // {"sClass": "center","sWidth":"5%"},
                   {"sClass": "left","sWidth":"22%"},
                   {"sClass": "left","sWidth":"30%"},
                   {"sClass": "left","sWidth":"15%"},
                   {"sClass": "left","sWidth":"10%"},
                   {"sClass": "left","sWidth":"10%"},
                   {"sClass": "center","sWidth":"5%"},



                   ],

                   




    });

	$('#modul_grid').on('click', 'tr button', function() {
		var $row = $(this).closest('tr')
	    //alert(table.cell($row, 0).data());
	    append_drop_menu('modul', table.cell($row, 0).data(), '.btn_action')
    });
    $('.export_csv').on('click', function() {
	    csv_export(table, 'csv');
    });
    $('.export_pdf').on('click', function() {
	    csv_export(table, 'pdf');
    });

    $('#modul_grid').on('click', 'tr .export_mod', function(e) {
		var $row  = $(this).closest('tr')
		var $id   = table.cell($row, 0).data();
		var $data = $(this).attr("data"); 
		//alert($(this).attr("data"));
	    exec_ajax('modul', $data, $confirm = 1, '' );
	    e.preventDefault();

	    
    });

    $('#modul_grid').on('click', 'tr .bootbox-regular', function() {
    	var $row = $(this).closest('tr')
    	alert($(this).attr("rel"));

    	ajax_bbox_loader($(this).attr("rel"),'', $(this).attr("item"),'large');

	
	});

	$('#user_grid tbody ').on('click', 'tr .this_del', function() {
		//alert($(this).attr("item"));
	    stand_delet($(this),$(this).attr("table"),$(this).attr("item"))
    });


});
</script>















































