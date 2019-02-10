<?php $service = cryptage(session::get('service'),0);
$usrid= session::get('userid');
global $db;
 ?>
<script type="text/javascript">
//function sync





 $(document).ready(function() {
// Sync Data Base	  
	  var sync = function(){
	
	 $.ajax({
                url: './?_tsk=sync&ajax=1&tb=1',
                type: 'get',
                dataType:'html',
                  success: function(data) {
                    if(data) {}
					
				  }
               
            });
	
	
      }
	  setInterval(sync,30000);
});	  
//End Sync Data Base
// Last Active	
$(document).ready(function() {  
	  var lastactiv = function(){
	
	 $.ajax({
                url: './?_tsk=lastactiv&ajax=1&tb=1',
                type: 'get',
                dataType:'html',
                  success: function(data) {
                    if(data) {}
					
				  }
               
            });
	
	
      }
	setInterval(lastactiv,3000);  
 });	  
//End last active	  

	  
 
//Notification Aemploi	
<?php $sqlaemploinotif="Select userid from permission_users where userid=$usrid  and appid=44 ";
$aemploinotif = $db->QuerySingleValue0($sqlaemploinotif);
			
			if($aemploinotif>0){ ?>
$(document).ready(function() {	
	 var callnotification = function(){
	 var existvaluetsk = $('.notiftsk').text();
	 var existvaluemsg = $('.notifmsg').text();			
	 $.ajax({
                url: './?_tsk=notifier&ajax=1&tb=1&nae=1&tik=1',
                type: 'get',
                dataType:'json',
				
                success: function(data) {
                    if(data.n > existvaluetsk) {
						

                      //alert("Value for 'n': " + data.n + "\nValue for 'm': " + );
	                 $.sticky('<b>Vous avez '+data.n+'  '+data.m+'</b>');
	                 $('.notiftsk').text(data.n);
					
					 $('<audio id="sound"><source src="img/notify.mp3" type="audio/mpeg"><source src="img/notify.wav" type="audio/wav"></audio>').appendTo('body'); 
					 $('#sound')[0].play();
					}else{
					$('.notiftsk').text(data.n);
					
					}
                }
            });
	 }
	 setInterval(callnotification,2000);
 });	 
	  <?php } ?>
	                  
					  
					  

 //Notification Message
 $(document).ready(function() {

<?php  if  (cryptage($_SESSION['defapp'],0)!= 3){		
		echo    "$('#sidebar').addClass('side-hide');
			$('.top-nav').addClass('full-fluid');
			$('#main-content').addClass('full-fluid');";} ?>
			
	 var callnotification = function(){
	 var existvaluetsk = $('.notiftsk').text();
	 var existvaluemsg = $('.notifmsg').text();	 
	
	 $.ajax({
                url: './?_tsk=notifier&ajax=1&tb=1&nmsg=1&tik=1',
                type: 'get',
                dataType:'json',
				
                success: function(data) {
                    if(data.n != existvaluemsg) {
						

                      //alert("Value for 'n': " + data.n + "\nValue for 'm': " + );
	                 $.sticky('<b>Vous avez '+data.n+'  '+data.m+'</b>');
	                 $('.notifmsg').text(data.n);
					 $('<audio id="sound"><source src="img/notify.ogg" type="audio/ogg"><source src="img/notify.mp3" type="audio/mpeg"><source src="img/notify.wav" type="audio/wav"></audio>').appendTo('body'); 
					 $('#sound')[0].play();
					}
                }
            });
	 }
	                  setInterval(callnotification,10500);
 });
 
</script>
<?php 





?>
<div class="navbar navbar-fixed-top ">

  <div class="navbar-inner top-nav">
    <div class="container-fluid">
      <div class="branding">
        <div class="logo"> <a href="./" title="Acceuil"><img src="img/logo.png"  alt="Logo"></a> </div>
      </div>
      
      <ul class="nav pull-right">
      

         
         </li>
      <?php 
	  
	  
	// Nbr notification
global $db ;
 
$querynotif="SELECT count(users_sys.id) as nbrae ,users_sys.lnom as lnom ,users_sys.fnom as fnom,users_sys.id as iduser ,dat  from session , users_sys where  users_sys.nom = session.user  and expir is null order by session.id desc "; ?>
         <li class="dropdown"><a data-toggle="dropdown"  onclick="ajax_loader('./?_tsk=onligne');" class="dropdown-toggle" href="#"><i class="white-icons speech_bubble"></i>Messages<span class="alert-noty" ><?php echo $db->QuerySingleValue($querynotif)?></span><!-- <b class="caret"></b> --></a>
   
        </li>
  
<?php 
	// Nbr notification
 

$service = cryptage($_SESSION['service'],0);
$querynotif="SELECT count(aemploi.id) as nbrae FROM aemploi, rules WHERE aemploi.etat = rules.etat and rules.notif = 1 AND rules.service = $service and rules.app='aemploi' "; ?>
         <li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="white-icons speech_bubble"></i>Notifications<span class="alert-noty notiftsk"><?php echo $db->QuerySingleValue($querynotif)?></span><b class="caret"></b></a>
          <ul class="dropdown-menu">
<?php if($aemploinotif>0){
	// Nbr notification
 

$service = cryptage($_SESSION['service'],0);
$querynotif="SELECT count(aemploi.id) as nbrae FROM aemploi, rules WHERE aemploi.etat = rules.etat and rules.notif = 1 AND rules.service = $service and rules.app='aemploi' "; ?>          
            <li><a href="#" onclick="ajax_loader('./?_tsk=aemploi');">Autorisation d'emploi<span class="alert-noty notiftsk"><?php echo $db->QuerySingleValue($querynotif)?></span></a></li>
<?php } ?>  
          </ul>
        </li>
        <li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo  $_SESSION['username']; ?><i class="white-icons admin_user"></i><b class="caret"></b></a>
          <ul class="dropdown-menu">
          
            <li><a href="#" onclick="ajax_loader('./?_tsk=editprofile');"><i class="icon-pencil"></i> Edit Profile</a></li>
           
            <li class="divider"></li>
            <li><a href="./?_tsk=logout"><i class="icon-off"></i><strong>Déconnexion</strong></a></li>
          </ul>
        </li>
        
      </ul>
      <div style="width:100%;" align="center"><div id="message" style="display:none;" ></div></div>
        
      </div>
    </div>
  </div>
</div>