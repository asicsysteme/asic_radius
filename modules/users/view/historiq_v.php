<?php $service = cryptage($_SESSION['service'],0);
$usrid= $_SESSION['userid'];
global $db;


$usr=tg('u');
//detirmine Service user
if (!$db->Query("SELECT nom,servic FROM users_sys where id= ".$usr)) $db->Kill("error");
if ( $db->RowCount() > 0 ) {
	$array = $db->RowArray();
	define('USER_SERV',$array['servic']);
	define('NOMUSER',$array['nom']);
}

?>
 ?>
  <ul class="breadcrumb" style="margin-top:1px;">
         <li><a href="./">Acceuil</a><span class="divider">&raquo;</span></li>
         <li class="active"><?php echo ACTIV_APP; ?></li>
       </ul>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-block">
          <div class="widget-head">
            <h5 style="position:relative; float:left;"><i class="black-icons users"></i>Historique Connexion pour <span style="color:#F00;"><?php echo NOMUSER; ?></span></h5>
     
            
           
          </div>
 <?php 

$fullquery="SELECT fnom,lnom,nom,active,ctc,servic,services.service as service,users_sys.id,agence.agence FROM users_sys, services, agence where servic=services.id and users_sys.agence=agence.id and sa is NULL ";
 
if (! $db->Query($fullquery)) $db->Kill('Error1');

$pagination = new pagin();
$to = $pagination->byPage = 20;
$allcount= $db->RowCount();
$pagination->rows = $db->RowCount();; // nombre d'enregistrement dans une table retourner par mysql_num_rows() par exemple ou autre , a vous de jouer
$from = $pagination->fromPagination(); // sert pour les requetes sql exemple LIMIT $from, $pagination->byPage
$pages = $pagination->pages();


if (! $db->Query(" $fullquery limit ".$from.",".$to)) $db->Kill('Error');
//list user
 ?>         
          
          <div class="widget-content">
          <div class="widget-selectbox">
              <ul>
              
              
                <li class="row-counts">Total <?php echo ACTIV_APP .' :'.$allcount; ?></li>
                
                
               </ul>
               
            </div>
            <div class="widget-box">
              <table class="table post-tbl table-striped">
                <thead>
                  <tr>
                    
                    <th class="center"> Utilisateur </th>
                    <th>Nom & Prénom </th>
                    <th>Service</th>
                    <th>Nom d'utilisateur</th>
                    <th>Lieu d'affectation</th>
                    <th>Etat</th>
                    <th class="center"> Action </th>
                  </tr>
                </thead>
                <tbody>
                <?php //liste user
				
				


				 while (!$db->EndOfSeek()) {
         $row = $db->Row();
 ?>

                
                  <tr class="lst">
                    
                    <td class="center"><img src="img/user-thumb.png" width="30" height="30" alt="User"></td>
                    <td><?php echo $row->fnom.'  '.$row->lnom ; ?></td>
                    <td><span class="user-position"><?php echo $row->service; ?></span></td>
                    <td><h4><?php echo $row->nom; ?></h4></td>
                    <td><span class="user-ste"><?php echo $row->agence; ?></span></td>
                    
                    <?php
					$class= $row->active == 1?"label-success":"label-important";
					$class= $row->ctc == 0?$class:"label-warning";
					
					$status= $row->active == 1?"Compte Actif":"Compte Inactif";
					$status= $row->ctc == 0?$status:"Compte Bloqué";
					
					 ?>
                    
                    <td ><span class="label <?php echo $class; ?>"><?php echo $status; ?></span></td>
                    
                    
                    
                    
                    <td><div class="btn-group pull-right">
                        <button data-toggle="dropdown" class="btn dropdown-toggle"><i class="icon-cog"></i><span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <li><a href="#" onclick="ajax_loader('./?_tsk=edituser&u=2&i=<?php echo $row->id; ?>');"><i class="icon-file"></i>Afficher Détails</a></li>
                          <li><a href="#" onclick="ajax_loader('./?_tsk=rule&u=<?php echo $row->id; ?>');"><i class="icon-edit"></i>Editer permissions</a></li>
                          <?php if ($row->ctc == 5){ ?>
                          <li><a href="#" class="tryit" onclick="active('Vous êtes sûr de débloquer  le compte <?php echo $row->nom; ?> ?', {'confirm':true}, <?php echo $row->id ?>);"><i class="color-icons lock_unlock_co"></i>Débloquer le compte </a></li>
                          <?php }?>
                    
                          <li class="divider"></li>
                          <?php if ($row->active == 1){ ?>
                          <li><a href="#" class="tryit" onclick="delet('Vous êtes sûr de désactiver le compte <?php echo $row->nom; ?> ?', {'confirm':true}, <?php echo $row->id ?>);"><i class="color-icons cross_co"></i>Désactiver le compte </a></li>
                          <?php }else{ ?>
                          <li><a href="#" class="tryit" onclick="active('Vous êtes sûr d\'activer le compte <?php echo $row->nom; ?> ?', {'confirm':true}, <?php echo $row->id ?>);"><i class="color-icons accept_co"></i>Activer le compte </a></li>
                          <?php }?>
                          
                        </ul>
                      </div></td>
                  </tr>
                 <?php 
} 



?>

                  
                  
                  
                </tbody>

              </table>
            </div>
            
            <div class="widget-bottom">
            <?php if(isset($pages)) {?>
              <div class="pagination">
                <ul>
                <?php foreach ($pages as $key){?>
                <?php if($key['current'] == 1) {?>
                  <li class="active"><a href="#" ><?=$key['page']?></a></li>
                  <?php } else { ?>
                  <li><a href="#" onclick="ajax_loader('./?_tsk=user&p=<?=$key['p']?>');"><?=$key['page']?></a></li>
                  <?php }?>
                  <?php }?>
                  </ul>
              </div>
              <?php }?>
            </div>
          </div>
        </div>
      </div>
    </div>

<script type="text/javascript">
//delet line

function delet(string, args, id,etat) {
		apprise(string, args, function(r) {
		if(r) { 
		ajax_loader('./?_tsk=user&del='+id)
		ajax_loadmessage(' Désactivation réussi ',5000,'msgboxeok');		
				
				}
		else 
			{ $('#returns').text('False');}
	});
	
}
function active(string, args, id,etat) {
		apprise(string, args, function(r) {
		if(r) { 
		ajax_loader('./?_tsk=user&act='+id)
		ajax_loadmessage(' Activation réussi ',5000,'msgboxeok');		
				
				}
		else 
			{ $('#returns').text('False');}
	});
	
}
</script>