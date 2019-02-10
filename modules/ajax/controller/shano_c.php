<?php

global $db ;


$appid= tp('model');
$artid= tp('id');
$log= 1;
$query="SELECT  CONCAT(users_sys.fnom, ' ',users_sys.lnom) as users, annotation.id, message,  usrid, dat, artid from users_sys, annotation, task where users_sys.id=annotation.usrid and annotation.app = task.id and task.app='".$appid."' and artid =".$artid." and annotation.logg=".$log." order by id  DESC";
if (! $db->Query($query)) $db->Kill('');
$countrow=$db->RowCount();
if($countrow>0){
//echo $query;
?>


	

	
	  
		<table class="table table-striped table-bordered table-hover">
		  <tr>
		    <td><strong>id</strong></td>
		    <td><strong>Utilisateur</strong></td>
		    <td><strong>message</strong></td>
		    <td><strong>date</strong></td>
	      </tr>
          	  <?php
	 while (! $db->EndOfSeek()) {
    $row = $db->Row(); ?>
		 
	
		  <tr>
		    <td width="10%"><?php echo $row->id; ?></td>
		    <td width="20%"><?php  echo $row->users;  ?></td>
		    <td width="40%"><?php  echo $row->message; ?></td>
		    <td width="23%"><?php  echo date('d-m-Y H:i',strtotime($row->dat)); ?></td>
	      </tr>
		 <?php }?>
	  </table>
		
<?php }else{ echo 'Pas de message pour cette ligne. ';}?>