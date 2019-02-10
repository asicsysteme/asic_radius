<div class="widget-box transparent">
    
            <div class="widget-body">
                        <div class="widget-main padding-24">
                            <div class="row">
                                
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                            <b>Informations générales</b>
                                        </div>
                                    </div>

                                    <div>
                                        <ul class="list-unstyled  spaced">
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Nom 
                                                    <b class="blue pull-right"><?php  $info_cp_users->s('nom');?></b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Prénom
                                                    <b class="blue pull-right"><?php  $info_cp_users->s('prenom');?> </b>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Pseudo
                                                   <b class="blue pull-right"><?php  $info_cp_users->s('username');?></b>
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Profile
                                                   <b class="blue pull-right"><?php  $info_cp_users->s('profil_s');?></b>
                                            </li>


                                            <li>
                                                <i class="ace-icon fa fa-caret-right green"></i>Total consomation
                                                    <b class="blue pull-right"><?php  echo MInit::formatBytes($info_cp_users->g('data_up') + $info_cp_users->g('data_down'));?></b>
                                            </li>
                                           <!--  <li>
                                                <i class="ace-icon fa fa-caret-right green"></i> Cout de revient 
                                                    <b class="blue pull-right"><?php  $info_cp_users->s('cout_revient');?> Fcfa</b>
                                            </li> -->                                          

                                        </ul>
                                    </div>
                                </div><!-- /.col sm 6-->
                                <div>
                                	<?php 
$chart = new MHighchart();
$chart->titre      = 'Evolution consomation 10 dérniers jours';
$chart->id_chart   = 'consomation_ten_days';
$chart->items      = 'Mb';
$chart->name_serie = 'Consomation';
$chart->name_x     = 'Data (Mb)';
$chart->unite      = 'Mb';
$chart->column_render('v_consom_ten_days_per_user',  'username = \''.$info_cp_users->g('username').'\'', 6);

                                	?>
                                </div>

                            </div><!-- /.row -->
                        </div><!--widget main-->
                    </div><!--widget body-->
                </div><!--widget-box transparent-->