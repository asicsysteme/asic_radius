<?php

global $db;
$sql = 'SELECT * FROM v_count_s_vsat_per_cat_perm';
if(!$db->Query($sql)){
    var_dump($db->Error());
}else{
    if($db->RowCount())
    {
       $brut_array       = $db->RecordsArray();
    //var_dump($brut_array);
       $names = array_column($brut_array, 'name');
       $nbr   = array_column($brut_array, 'nbr');
               
       $nbr = array_map('floatval', $nbr);
      
       

      
      
    }else{
        exit('no data yet');
    }


}


$chart = new Highchart();
$chart->chart->renderTo = "container";
$chart->chart->type = "column";
$chart->title->text = "Consomation quotidienne de données";
//$chart->subtitle->text = "Source: Database";

$chart->xAxis->categories = $names;

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
    return '' + this.x +': '+ this.y +' Mb';}");

$chart->plotOptions->column->pointPadding = 0.2;
$chart->plotOptions->column->borderWidth = 0;


$chart->series[] = array(
    'name' => "Aujourd'hui",
    'data' => $nbr
    );

?>


    <div class="col-sm-6">
        <div class="widget-box">
            <div class="widget-header widget-header-flat widget-header-small">
                <h5 class="widget-title">
                    <i class="ace-icon fa fa-signal"></i>
                    Station VSAT par Catégorie Permissionnaires
                </h5>


            </div>

            <div class="widget-body">
                <div class="widget-main">

                    <div id="container"></div>
                    <script type="text/javascript"><?php echo $chart->render("chart1"); ?></script>

                </div><!-- /.widget-main -->
            </div><!-- /.widget-body -->
        </div><!-- /.widget-box -->
    </div>

</div>


