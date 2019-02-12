<?php
//Get all Client info 
$info_cp_users = new Mcp_users();
$action = new TableTools();
$info_cp_users->id_cp_users = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$info_cp_users->get_cp_users())
{   
    // returne message error red to client 
    exit('0#'.$info_cp_users->log .'<br>Les informations pour cette ligne (Produit) sont erronées contactez l\'administrateur');
}
$tab_details_user        = view::tab_render('cp_users', 'Info Utilisateur', $add_set=NULL, 'paper-plane-o' , $active = true, 'home');
$tab_connexion_user      = view::tab_render(null, 'Historique connexions', $add_set=NULL, 'exchange' , $active = false, 'pan_cnx');
$tab_history_user        = view::tab_render(null, 'Historique log', $add_set=NULL, 'history' , $active = false, 'pan_log');
//$tab_arrivages     = view::tab_render(Null, 'Liste des arrivages', NULL, 'cart-plus' , false, 'arrivages');
?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php 
        TableTools::btn_action('cp_users', $info_cp_users->id_cp_users, 'detail_produit');
        TableTools::btn_add('cp_users','Liste des Utilisateurs CP', Null, $exec = NULL, 'reply'); 

        ?>

    </div>
</div><!-- /.tableTools-container -->
<div class="page-header">
    <h1>
        Détails Utilisateur CP: <?php $info_cp_users->s('username')?>
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small>

    </h1>
</div><!-- /.page-header -->

<div class="row">
    <?php Mmodul::get_statut_etat_line('cp_users', $info_cp_users->g('etat')); ?>
    <div id="main_div">
        <div id="user-profile-2" class="user-profile">
            <div class="tabbable">
                <ul class="nav nav-tabs padding-18">
                                        
                    <?php 
                    //Show all Tabs buttons
                    echo $tab_details_user['tab_index']; 
                    echo $tab_connexion_user['tab_index'];
                    echo $tab_history_user['tab_index'];                   
                    ?>
                    
                </ul>

                <div class="tab-content no-border padding-24">
                    <?php                     

                     if($tab_details_user['tb_rl'])
                     {
                        echo $tab_details_user['tcs'];
                        //Content (includ file - simple string - function return string)
                        include 'info_cp_users_v.php';                      
                        echo $tab_details_user['tce'];
                     } 
                     if($tab_connexion_user['tb_rl'])
                     {
                        echo $tab_connexion_user['tcs'];
                        include 'cp_users_connexions_v.php';                                            
                        echo $tab_connexion_user['tce'];
                     }
                     if($tab_history_user['tb_rl'])
                     {
                        echo $tab_history_user['tcs'];
                        //Content (includ file - simple string - function return string)
                        print Mlog::get_log('radcheck', $info_cp_users->id_cp_users);                    
                        echo $tab_history_user['tce'];
                     }
                     ?>

                </div><!-- /.tab-content no-border -->

            </div><!-- /#tabbable -->
        </div><!-- /.user-profile-2 -->
    </div><!-- /#main_div -->
</div><!-- /#main row -->


<!-- page specific plugin scripts -->
<script type="text/javascript">


</script>

