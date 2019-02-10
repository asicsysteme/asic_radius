<div class="page-header">
	<h1>
		Gestion des villes
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
					
						<?php TableTools::btn_add('addville','Ajouter ville'); ?>
						<?php TableTools::btn_csv('villes','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('villes','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Villes" 
		</div>
		<div>
				<table id="villes_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Ville
						</th>
						<th>
							Département
						</th>
						<th>
							Latitude
						</th>
						<th>
							Longitude
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
	
	var table = $('#villes_grid').DataTable({
		bProcessing: true,
		notifcol : 5,
		serverSide: true,
		
		ajax_url:"villes",
		


                aoColumns: [
                    {"sClass": "right","sWidth":"5%"}, //
                    {"sClass": "left","sWidth":"25%"},
                    {"sClass": "left","sWidth":"25%"},
                    {"sClass": "center","sWidth":"15%"},
                    {"sClass": "center","sWidth":"15%"},
                    {"sClass": "center","sWidth":"10%"},
                    {"sClass": "center","sWidth":"5%"},
                    ],

                    
    });

            
 
            
           
        
    
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#villes_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('villes', table.cell($row, 0).data(), '.btn_action')
});


});

</script>