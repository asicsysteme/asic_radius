<div class="page-header">
	<h1>
		Gestion des régions
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
					
						<?php TableTools::btn_add('addregion','Ajouter région'); ?>
						<?php TableTools::btn_csv('regions','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('regions','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Régions" 
		</div>
		<div>
				<table id="regions_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Région
						</th>
						<th>
							Pays
						</th>
						<th>
							Statut
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
	
	var table = $('#regions_grid').DataTable({
		bProcessing: true,
		notifcol : 3,
		serverSide: true,
		
		ajax_url:"regions",
		


                aoColumns: [
                    {"sClass": "left","sWidth":"10%"}, //
                    {"sClass": "left","sWidth":"40%"},
                    {"sClass": "left","sWidth":"30%"},
                    {"sClass": "center","sWidth":"15%"},
                    {"sClass": "center","sWidth":"5%"},
                    ],
    });

            
 
            
           
        
    
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#regions_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('regions', table.cell($row, 0).data(), '.btn_action')
});


});

</script>
