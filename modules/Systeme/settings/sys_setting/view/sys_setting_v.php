<div class="page-header">
	<h1>
		<?php echo ACTIV_APP?>
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
					
						<?php TableTools::btn_add('add_sys_setting','Ajouter Paramètre'); ?>
											
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Paramètres" 
		</div>
		<div>
				<table id="sys_setting_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Clé
						</th>
						<th>
							Valeur
						</th>
						<th>
							Description
						</th>
						<th>
							Module
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


$(document).ready(function() {
	
	var table = $('#sys_setting_grid').DataTable({
		bProcessing: true,
		notifcol : 3,
		serverSide: true,
		
		ajax_url:"sys_setting",
		


                aoColumns: [
                    {"sClass": "left","sWidth":"5%"}, //
                    {"sClass": "left","sWidth":"10%"},
                    {"sClass": "left","sWidth":"20%"},
                    {"sClass": "center","sWidth":"35%"},
                    {"sClass": "left","sWidth":"10%"},
                    
                    {"sClass": "center","sWidth":"5%"},
                    ],
    });

            
 
            
           
        
    
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#sys_setting_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('sys_setting', table.cell($row, 0).data(), '.btn_action')
});


});

</script>
