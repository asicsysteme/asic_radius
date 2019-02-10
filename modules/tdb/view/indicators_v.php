<?php
global $db;

$sql = 'SELECT * FROM v_count_station_each_tech';
if(!$db->Query($sql)){
	var_dump($db->Error());
}else{
	if(!$db->RowCount())
	{
		exit('no data yet');
	}else{
		$brut_array       = $db->RecordsArray();
	}
}
$table_name = array_column($brut_array, 'TABLE_NAME');
$table_rows = array_column($brut_array, 'TABLE_ROWS');
$indic_arr  = array_combine($table_name, $table_rows);
//var_dump($table_name);
//var_dump($table_rows);
//var_dump($indic_arr);

?>

<div class="col-sm-12">
	<div class="widget-box">
		<div class="widget-header widget-header-flat widget-header-small">
			<h5 class="widget-title">
				<i class="ace-icon fa fa-signal"></i>
				Indicateurs clés
			</h5>
		</div>

		<div class="widget-body">
			<div class="widget-main">
			
				<!-- Indicateur Permissionnaires  -->
					<div class="infobox infobox-blue infobox-small infobox-dark ">
						<div class="infobox-icon">
							<i class="ace-icon fa fa-th_list"></i>
						</div>

						<div class="infobox-data">
							<div class="infobox-content">Nbr.Perm</div>
							<span class="infobox-data-number pull-right"><?php echo $indic_arr['permissionnaires'] ?></span>
						</div>
					</div>
				<!-- End Indicateur Permissionnaires -->
				<!-- Indicateur Installateurs  -->
					<div class="infobox infobox-purple infobox-small infobox-dark">
						<div class="infobox-icon">
							<i class="ace-icon fa fa-installer"></i>
						</div>

						<div class="infobox-data">
							<div class="infobox-content">Install</div>
							<span class="infobox-data-number pull-right"><?php echo $indic_arr['installateurs'] ?></span>
						</div>
					</div>
				<!-- End Indicateur Installateurs -->
				<!-- Indicateur Revendeurs  -->
					<div class="infobox infobox-grey infobox-small infobox-dark">
						<div class="infobox-icon">
							<i class="ace-icon fa fa-vendor"></i>
						</div>

						<div class="infobox-data">
							<div class="infobox-content">Revend</div>
							<span class="infobox-data-number pull-right"><?php echo $indic_arr['revendeurs'] ?></span>
						</div>
					</div>
				<!-- End Indicateur Revendeurs -->
				<!-- Indicateur VSAT -->
					<div class="infobox infobox-green infobox-small infobox-dark">
						<div class="infobox-icon">
							<i class="ace-icon fa fa-vsat"></i>
						</div>

						<div class="infobox-data">
							<div class="infobox-content">VSAT</div>
							<span class="infobox-data-number pull-right"><?php echo $indic_arr['vsat_stations'] ?></span>
						</div>
					</div>
				<!-- End Indicateur VSAT -->
				
				<!-- Indicateur BLR  -->
					<div class="infobox infobox-blue infobox-small infobox-dark">
						<div class="infobox-icon">
							<i class="ace-icon fa fa-blr"></i>
						</div>

						<div class="infobox-data">
							<div class="infobox-content">BLR</div>
							<span class="infobox-data-number pull-right"><?php echo 3 ?></span>
						</div>
					</div>
				<!-- End Indicateur BLR -->
				<!-- Indicateur ANONYM  -->
					<div class="infobox infobox-grey infobox-small infobox-dark">
						<div class="infobox-icon">
							<i class="ace-icon fa fa-gsm"></i>
						</div>

						<div class="infobox-data">
							<div class="infobox-content">GSM</div>
							<span class="infobox-data-number pull-right"><?php echo $indic_arr['gsm_stations'] ?></span>
						</div>
					</div>
				<!-- End Indicateur ANONYM -->
				<!-- Indicateur ANONYM  -->
					<div class="infobox infobox-purple infobox-small infobox-dark">
						<div class="infobox-icon">
							<i class="ace-icon fa fa-vhf"></i>
						</div>

						<div class="infobox-data">
							<div class="infobox-content">VHF/UHF</div>
							<span class="infobox-data-number pull-right"><?php echo $indic_arr['uhf_vhf_stations'] ?></span>
						</div>
					</div>
				<!-- End Indicateur ANONYM -->
				<!-- Indicateur ANONYM  -->
					<div class="infobox infobox-red infobox-small infobox-dark">
						<div class="infobox-icon">
							<i class="ace-icon fa fa-cloud"></i>
						</div>

						<div class="infobox-data">
							<div class="infobox-content">Inconnus</div>
							<span class="infobox-data-number pull-right"><?php echo $indic_arr['prm_anonyme'] ?></span>
						</div>
					</div>
				<!-- End Indicateur ANONYM -->
				<!-- Indicateur BLR  -->
					<!-- <div class="infobox infobox-blue infobox-small infobox-dark">
						<div class="infobox-icon">
							<i class="ace-icon fa fa-cloud"></i>
						</div>

						<div class="infobox-data">
							<div class="infobox-content">BLR</div>
							<span class="infobox-data-number pull-right"><?php echo $indic_arr['blr_stations'] ?></span>
						</div>
					</div> -->
				<!-- End Indicateur VSAT -->
				

			</div><!-- /.widget-main -->
		</div><!-- /.widget-body -->
	</div><!-- /.widget-box -->
</div>


