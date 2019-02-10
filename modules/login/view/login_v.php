<div class="login-box visible widget-box no-border">
	<div class="widget-body">
	<h1>
		<img src="img/<?php echo Mcfg::get('logo')?>" width="149" height="40" />

		<span class="white"><?php //echo SYS_TITRE?></span>
	</h1>
	<!-- <h4 class="blue"><?php echo CLIENT_TITRE?></h4> -->
</div>
</div>

<div class="space-6"></div>

<div class="row-fluid">
	<div class="position-relative">
		<div id="login-box" class="login-box visible widget-box no-border">
			<div class="widget-body">
				<div class="widget-main">
					<h4 class="header blue lighter bigger">
						<i class="icon-lock green"></i>
						Connexion


					</h4>
				<?php if(MReq::tp('alg')){ ?>
				
					<div class="alert alert-danger">
					vous avez été deconnecté du serveur pour une inactivité de plus de <?php echo MLogin::get_ses_time_autologout(MReq::tp('alg'));?>.
						<br>
					</div>
				<?php } ?>

					<div class="space-6"></div>
					<form novalidate="novalidate" id="login" action="#" method="post" />
					<fieldset>
						<input name="verif" type="hidden" value="<?php  MInit::form_verif('login');?>" />
						<input name="token" type="hidden" value="<?php echo $token= md5(uniqid(rand(), true));?>" />
						<div class="form-group">
							<label class="block clearfix">
								<span class="block input-icon input-icon-right">
									<input type="text" id="user" name="user" class="form-control" placeholder="Nom d'utilisateur" />
									<i class="ace-icon fa fa-user"></i>
								</span>
							</label>
						</div>
						<div class="form-group">

							<label class="block clearfix ">
								<span class="block input-icon input-icon-right">
									<input type="password" id="pass" name="pass" class="form-control" placeholder="Mot de passe" />
									<i class="ace-icon fa fa-lock"></i>
								</span>
							</label>
						</div>	


						<div class="space"></div>


						<div class="clearfix">


							<button id="btn_submit" type="submit" class="width-50 pull-center btn btn-sm btn-primary">
								<i class="ace-icon fa fa-key"></i>
								<span class="bigger-100">Connexion</span>
							</button>
						</div>

						<div class="space-4"></div>
					</fieldset>
					<div id="messag_login"></div>
				</form>












			</div><!--/widget-main-->

			<div class="toolbar clearfix">
				<div>
					<a href="#" onclick="show_box('forgot-box'); return false;" class="forgot-password-link">
						<i class="icon-arrow-left"></i>
						Mot de passe oublié
					</a>
				</div>

				<div>

				</div>
			</div>
		</div><!--/widget-body-->
	</div><!--/login-box-->

	<div id="forgot-box" class="forgot-box widget-box no-border">
		<div class="widget-body">
			<div class="widget-main">
				<h4 class="header red lighter bigger">
					<i class="icon-key"></i>
					Récupération Mot de Passe
				</h4>

				<div class="space-6"></div>
				<p>
					Entez votre E-mail pour recevoir les instructions 
				</p>

				<form action="#" id="forgot" method="post" />
				<input name="verif" type="hidden" value="1" />
				<fieldset>
					<div class="form-group">

						<label class="block clearfix">
							<span class="block input-icon input-icon-right">
								<input type="text" id="email" name="email" class="form-control" placeholder="Email ou Pseudo" />
								<i class="ace-icon fa fa-envelope"></i>
							</span>
						</label>
					</div>
					<div class="form-group">
						
						<label class="control-label">
							Code Anti-robots :
							<img class="capimg" id="capimg" src="img/captcha.png.php" alt="Recopiez le code"/>
						</label>
					</div>
					<div class="form-group">
						<label class="block clearfix">
							<span class="block input-icon input-icon-right">
								<input type="text" id="captcha" name="captcha" class="form-control" placeholder="Anti - robots" />
								<i class="ace-icon fa fa-key"></i>
							</span>
						</label>
					</div>
					<div class="clearfix">
						
						<button type="submit" class="width-50 pull-center btn btn-sm btn-danger">
							<i class="ace-icon fa fa-lightbulb"></i>
							<span class="bigger-100">Envoi</span>
						</button>
					</div>
				</fieldset>
				<div id="messag_forgot"></div>
			</form>
		</div><!--/widget-main-->

		<div class="toolbar center">
			<a href="#" onclick="show_box('login-box'); return false;" class="back-to-login-link">
				Retour à la page de connexion
				<i class="icon-arrow-right"></i>
			</a>
		</div>
	</div><!--/widget-body-->
</div><!--/forgot-box-->

<script type="text/javascript">

//Affiche 
function show_box(id) {
	$('.widget-box.visible').removeClass('visible');
	$('#'+id).addClass('visible');
}

//function login
$(function () {


	$('#login').validate({
		
		execApp:"login",
		execRedi: true,
		addFunct:function(){

			bootbox.process({
				message:'Working',
			});

		},

		rules: {

			user: "required",
			pass:  "required"
		},

		messages: {

			user: "Insérez votre nom",
			pass: "Insérez le password"
		}
	});


//Start form forgot
$('#forgot').validate({

	execApp:"forgot",
	execRedi: true,
	addFunct:function(){

		$("#capimg").attr("src", "img/captcha.png.php?timestamp=" + new Date().getTime());
		$("#captcha").val("");
			/*bootbox.process({
	    		    message:'Working',
	    		});*/


	    	},
	    	rules: {

	    		email: "required",
	    		captcha:  "required"
	    	},

	    	messages: {

	    		email: "Insérez Email ou Pseudo",
	    		captcha: "Insérez le Code"
	    	},

	    });	







});


</script>			
