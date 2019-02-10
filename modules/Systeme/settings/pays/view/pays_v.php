<div class="page-header">
	<h1>
		Gestion des pays
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
					
						<?php TableTools::btn_add('addpays','Ajouter pays'); ?>
						<?php TableTools::btn_csv('pays','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('pays','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Pays" 
		</div>
		<div>
				<table id="pays_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Pays
						</th>
						<th>
							Nationalité
						</th>
						<th>
							Code du pays
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
	
	var table = $('#pays_grid').DataTable({
		bProcessing: true,
		notifcol : 4,
		serverSide: true,
		
		ajax_url:"pays",
		


                aoColumns: [
                    {"sClass": "left","sWidth":"5%"}, //ID
                    {"sClass": "left","sWidth":"35%"},//Pays
                    {"sClass": "left","sWidth":"35%"},//Nationalité
                    {"sClass": "center","sWidth":"10%"},//Code du pays
                    {"sClass": "center","sWidth":"10%"},//Statut
                    {"sClass": "center","sWidth":"5%"},//Action
                    ],
    });

      
        
    
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#pays_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('pays', table.cell($row, 0).data(), '.btn_action')
});


});

</script>