<h1>
    <img src="img/<?php echo Mcfg::get('logo')?>" width="149" height="40" />

    <span class="white"><?php echo SYS_TITRE?></span>
  </h1>
  <h4 class="blue"><?php echo CLIENT_TITRE?></h4>
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
            Nouveau mot de passe


          </h4>

          <div class="space-6"></div>
          <form novalidate="novalidate" id="recovery" action="#" method="post" />
          <fieldset>
            <input name="verif" type="hidden" value="1" />
            <input name="token" type="hidden" value="<?php echo MReq::tg('token'); ?>" />
            
            <div class="form-group">
              <label class="block clearfix">
                <span class="block input-icon input-icon-right">
                  <input type="password" id="pass" name="pass" class="form-control" placeholder="Nom d'utilisateur" />
                  <i class="ace-icon fa fa-lock"></i>
                </span>
              </label>
            </div>
            <div class="form-group">

              <label class="block clearfix ">
                <span class="block input-icon input-icon-right">
                  <input type="password" id="passc" name="passc" class="form-control" placeholder="Mot de passe" />
                  <i class="ace-icon fa fa-lock"></i>
                </span>
              </label>
            </div>  


            <div class="space"></div>


            <div class="clearfix">


              <button type="submit" class="width-50 pull-center btn btn-sm btn-primary">
                <i class="ace-icon fa fa-key"></i>
                <span class="bigger-100">Enregistrer</span>
              </button>
            </div>

            <div class="space-4"></div>
          </fieldset>
          <div id="messag_login"></div>
        </form>












      </div><!--/widget-main-->

      
    </div><!--/widget-body-->
  </div><!--/login-box-->


<script type="text/javascript">

//Affiche 
function show_box(id) {
  $('.widget-box.visible').removeClass('visible');
  $('#'+id).addClass('visible');
}

//function login
$(function () {

   $('#recovery').validate({
    
      execApp:"recovery",
      execRedi: true,
      
    rules: {

      pass: {
          required: true,
          minlength: 8,
          //remote:"pass"
        },
      passc: {
          required: true,
          minlength: 8,
          equalTo: "#pass"
        },
    },

    messages: {

      pass: {
          required: "Entrez un mot de passe",
          rangelength: "Le mot de passe doit contenir au moins 6 caractères",
          //remote:"Le mot de passe doit être alphanumériques compris entre 8 et 15 caractères"
        },
      passc: {
          required: "Confirmez mot de passe",
          minlength: "Le mot de passe doit contenir au moins 6 caractères",
          equalTo: "les deux mots de passe incompatibles"
        },
    },
  });
});


</script>     
