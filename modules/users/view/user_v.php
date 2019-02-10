<div class="page-header">
	<h1>
		Gestion Utilisateurs
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
					
						<?php TableTools::btn_add('adduser','Ajouter utilisateur'); ?>
						<?php TableTools::btn_csv('user','Exporter Liste'); ?>
						<?php TableTools::btn_pdf('user','Exporter Liste'); ?>
					
			    </div>
			</div>
		</div>

		<div class="table-header">
			Liste "Utilisateurs" 
		</div>
		<div>
			<table id="user_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						
						<th>
							Nom & prénom
						</th>
						<th>
							Service
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
	
	var table = $('#user_grid').DataTable({
		bProcessing: true,
		notifcol : 3,
		serverSide: true,
		
		ajax_url:"user",
		//extra_data:"extra_data=1",
		//ajax:{},
		/*search_extra1 : [
		   {id:'service',val:'Etat: <input id="service" class="form-control input-sm" type="search" placeholder="Etat" />'},
		   {id:'etat',val:'Ann: <select id="etat"><option value="0">Inactif</option><option value="1">Active</option></select>'},
		],*/


                aoColumns: [
                    {"sClass": "center","sWidth":"3%"}, //
                    {"sClass": "left","sWidth":"32%"},
                    {"sClass": "left","sWidth":"30%"},
                    {"sClass": "left","sWidth":"25%"},
                    {"sClass": "center","sWidth":"5%"},
                    ],
                });

            

            
           
        
    
$('.export_csv').on('click', function() {
	csv_export(table, 'csv');
});
$('.export_pdf').on('click', function() {
	csv_export(table, 'pdf');
});

$('#user_grid').on('click', 'tr button', function() {
	var $row = $(this).closest('tr')
	//alert(table.cell($row, 0).data());
	append_drop_menu('user', table.cell($row, 0).data(), '.btn_action')
});

$('#user_grid tbody ').on('click', 'tr .this_del', function() {
	//alert($(this).attr("item"));
	stand_delet($(this),$(this).attr("table"),$(this).attr("item"))
});

$('#etat').on('keyup', function () {
            	table.column(0).search( $(this).val() )
                     .draw();
            
});
$('#an').on('change', function () {
            	table.column(0).search( $(this).val() )
                     .draw();
            
});


});

</script>















































