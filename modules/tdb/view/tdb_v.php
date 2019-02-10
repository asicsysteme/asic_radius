 <?php 

 



/*              echo TableTools::count_notif('users_sys', 'user'); 
             */
?>

<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			
		</div>
		
		<div class="widget-content">
			<div class="widget-box">
				
<?php
$table = 'ste_info';
$sql = "SHOW FULL COLUMNS FROM $table";
global $db;
$arr_fields = array();
if(!$db->Query($sql))
{
			var_dump($db->Error());
}else{
	$arr_fields = $db->RecordsSimplArray();		
				
}
$line_action = "'fields'  => Mreq::tp('fields') ,";
$line_select = '$colms'. ".= \" $table.fields, \"";
$line_modul = '$values["fields"]       = MySQL::SQLValue($this->_data[\'fields\']);';

foreach ($arr_fields as $key => $value) {
	print str_replace('fields', $value[0], $line_action).'<br>';
}
print '<br>================<br>';
foreach ($arr_fields as $key => $value) {
	print str_replace('fields', $value[0], $line_modul).'<br>';
}
print '<br>================<br>';

foreach ($arr_fields as $key => $value) {
	print str_replace('fields', $value[0], $line_select).'<br>';
}
?>

			</div>
		</div>
	</div>	
</div>
