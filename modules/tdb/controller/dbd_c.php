<?php

global $db;

$sql = 'SELECT * FROM v_data_consom_this_day_per_user';
if(!$db->Query($sql) OR !$db->RowCount()){
    //var_dump($db->Error());
    MInit::big_message('Le système n\'a pas encore enregistré des donnée', 'info');
    exit('');
}else{
    
    $brut_array       = $db->RecordsArray();
    //var_dump($brut_array);
    $cp_users         = array_column($brut_array, 'username');
    $cp_data_today    = array_column($brut_array, 'today_data');
    $cp_data_yestrday = array_column($brut_array, 'yesterday_data');
    $cp_users_name    = array_column($brut_array, 'name');
    $brut_html_table  = $db->GetHTMLTABLE(array('Pseudo' => 1, 'Nom & Prénom' => 1 , 'Aujourd\'hui'=> 1, 'Hier'=> 1));
    
    $cp_data_today = array_map('floatval', $cp_data_today);
    $cp_data_yestrday = array_map('floatval', $cp_data_yestrday);
    $cp_data_today_f = ($cp_data_today);
    $cp_data_yestrday_f = ($cp_data_yestrday);
    
}

//fit Tableau etat de connect users

$sql =  'SELECT * FROM v_etat_connect_cp_users';
if(!$db->Query($sql) OR !$db->RowCount()){
    var_dump($db->Error());
}else{
    $brut_connect_array       = $db->RecordsArray();
}


$chart = new Highchart();
$chart->chart->renderTo = "container";
$chart->chart->type = "column";
$chart->title->text = "Consomation quotidienne de données";
//$chart->subtitle->text = "Source: Database";

$chart->xAxis->categories = $cp_users;

$chart->yAxis->min = 0;
$chart->chart->height = 370;
$chart->yAxis->title->text = "Data on (Mb)";
$chart->legend->layout = "vertical";
$chart->legend->backgroundColor = "#FFFFFF";
$chart->legend->align = "right";
$chart->legend->verticalAlign = "top";
$chart->legend->x = 0;
$chart->legend->y = 70;
$chart->legend->floating = 1;
$chart->legend->shadow = 1;

$chart->tooltip->formatter = new HighchartJsExpr("function() {
    return '' + this.x +': '+ Math.floor(this.y/1024/1024) +' Mb';}");

$chart->plotOptions->column->pointPadding = 0.2;
$chart->plotOptions->column->borderWidth = 0;


$chart->series[] = array(
    'name' => "Aujourd'hui",
    'data' => $cp_data_today
    );
$chart->series[] = array(
    'name' => "Hier",
    'data' => $cp_data_yestrday
    );


    ?>
    <div class="col-sm-4">
        <div class="widget-box">
            <div class="widget-header widget-header-flat widget-header-small">
                <h5 class="widget-title">
                    <i class="ace-icon fa fa-signal"></i>
                    Tableau de consomation 
                </h5>


            </div>

            <div class="widget-body">


               <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
                <tbody>
                    <tr>

                        <td class="text-center"><strong>Nom &amp; Prénom</strong></td>
                        <td class="text-center"><strong>Ce jour</strong></td>
                        <td class="text-center"><strong>Hier</strong></td>
                    </tr>
                    <?php 

                    $line = NULL;

                    foreach ($brut_array as $key => $value)
                    {
                        $line .= '<tr>';
                        $line .= '<td><a href="#" class="this_url" rel="cp_users_connexions" data="'.MInit::crypt_tp('id',$value['id_user'] , 'C').'">'.$value['nom'].'</a></td>';

                        $line .= '<td class="text-right">'.MInit::formatBytes($value['today_data'],2,true).'</td>';
                        $line .= '<td class="text-right">'.MInit::formatBytes($value['yesterday_data'],2,true).'</td>';
                        $line .= '</tr>';

                    }

                    echo $line;

                    ?>


                </tbody>
            </table>

            <!-- /.widget-main -->
        </div><!-- /.widget-body -->
    </div><!-- /.widget-box -->
</div>
<div class="col-sm-4">
    <div class="widget-box">
        <div class="widget-header widget-header-flat widget-header-small">
            <h5 class="widget-title">
                <i class="ace-icon fa fa-signal"></i>
                Grapique de Consomation
            </h5>


        </div>

        <div class="widget-body">
            <div class="widget-main">

                <div id="container"></div>
                <script type="text/javascript"><?php echo $chart->render("chart"); ?></script>

            </div><!-- /.widget-main -->
        </div><!-- /.widget-body -->
    </div><!-- /.widget-box -->
</div>
<div class="col-sm-4">
    <div class="widget-box">
        <div class="widget-header widget-header-flat widget-header-small">
            <h5 class="widget-title">
                <i class="ace-icon fa fa-users"></i>
                Utilisateurs Connectés
            </h5>


        </div>

        <div class="widget-body scrolling">
            

              <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
                <tbody>
                    <tr>

                        <td class="text-center"><strong>Nom &amp; Prénom</strong></td>
                        <td class="text-center"><strong>Etat connexion</strong></td>

                    </tr>
                    <?php 

                    $line = NULL;

                    foreach ($brut_connect_array as $key => $value)
                    {
                        $line .= '<tr>';
                        $line .= '<td><a href="#" class="this_url" rel="cp_users_connexions" data="'.MInit::crypt_tp('id',$value['id_user'] , 'C').'">'.$value['nom'].'</a></td>';

                        $line .= '<td class="text-center">'.$value['etat_connect'].'</td>';
                        
                        $line .= '</tr>';

                    }

                    echo $line;

                    ?>


                </tbody>
            </table>

       
    </div><!-- /.widget-body -->
</div><!-- /.widget-box -->
</div>

<script type="text/javascript">
$(document).ready(function(){
     $('.scrolling').ace_scroll({
                    size: 390,
       });

});
         
</script>
