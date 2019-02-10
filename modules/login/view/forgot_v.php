<script type="text/javascript">
$(function () {
    // validate signup form on keyup and submit
    var validator1 = $("#signupform").validate({
		
        rules: {
            username: "required",
			captcha: "required",
			
        },
        messages: {
            username: "Entez le nom d'utilisateur",
			captcha: "Entrez le code anti-robot !",

            
       
        },

        // set this class to error-labels to indicate valid fields
        success: function (label) {
            // set &nbsp; as text for IE
            //label.html("&nbsp;").addClass("checked");
        },
		submitHandler: function(form) {
			$.ajax({
                url: $('#signupform').attr('action'),
                type: $('#signupform').attr('method'),
                data: $('#signupform').serialize(),
                dataType: 'text',
				
                success: function(data) {
					  if(data == 1) {
                       ajax_loadmessage(' Message envoyé dans votre boite vous serez redirigé vers la page de connexion. ',8000,'msgboxeok');
					   ajax_loader('./?_tsk=login');
					   }else if(data == 2){
						ajax_loadmessage('l\'utilisateur n\'existe pas !  ',5000,'msgboxerr')
					 
					  
					}else if(data == 3){
						ajax_loadmessage('Le code Anti-robot est incorrect !  ',5000,'msgboxerr')
						
    var img=$('#capimg');
    var src=img.attr('src');
    var i=src.indexOf('?dummy=');
    src=i!=-1?src.substring(0,i):src;

    d = new Date();
    img.attr('src', src+'?dummy='+d.getTime() );

					}else {
						
                         ajax_loadmessage(data + ' Erreur opération  ',5000,'msgboxerr')
						 
                    }
                }
            });
			
			
			}
    });



});

</script>

   


<div class="login-container">
	<div class="well-login">
   
<form novalidate="novalidate" method="post"  id="signupform" action="./?_tsk=forgot&ajax=1&tb=1">
 <fieldset>
 <input name="verif" type="hidden" value="1" />
 <input name="token" type="hidden" value="<?php echo $token= md5(uniqid(rand(), true));?>" />
		<div class="control-group">
			<div class="controls">
				<div>
                 
                  <input id="usrname" name="username" type="text" placeholder="Nom d'utlisateur" class="input-xlarge login-input user-name " value="">
				</div>
			</div>
		</div>
        

        
        <div class="control-group">
			<div class="controls">
				<div>
                <label class="control-label">Code Anti-robots :</label>
                <div id="imgcap"> <img class="capimg" id="capimg" src="img/captcha.png.php" alt="Recopiez le code"/></div>
				</div>
			</div>
		</div>
        
		<div class="control-group">
			<div class="controls">
				<div>
                
                 
                  <input type="text" id="captcha" name="captcha" placeholder="Code Anti-robot" class="input-xlarge login-input user-pass ">
                  
				</div>
			</div>
            <div class="control-group">
										
										
		</div>
		<div class="clearfix">
			<button class="btn btn-inverse login-btn" type="submit" >Envoyer</button>
		</div>
		

	
    </fieldset>
    </form>
    </div>
</div>