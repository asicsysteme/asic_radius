<?php 
/**
* MRT Highchart_Generator
*/
class MHighchart 
{
	var $titre           = NULL;
	var $width           = NULL;
	var $items           = NULL;
	var $container       = NULL;
	var $chart_rended    = NULL;
	var $chart_generated = NULL;
	var $id_chart        = NULL;
	var $name_serie      = null;
	var $name_x          = null;
	var $unite           = null;
	var $chart_only      = false;
    

    /**
	 * [Pie_render Draw an Pie Chart]
	 * @param [type]  $table_vue [Table view with tree column (name, y, nbr )]
	 * @param integer $width     [With of container]
	 */
	public function Pie_render_from_array($data_array, $total_part, $width = 6)
	{
		
		//Get Percentage from data value
		
		$arr_nbr_sta = array();
		$crc = 0;
		foreach ($data_array as $key => $value) {

			$percentage_part = ((int)$value['nbr'] / (int)$total_part) * 100;

			array_push($arr_nbr_sta,
			    array('name' => $value['name'], 'nbr'  => $value['nbr'], 'y' => $percentage_part)
			);
			$crc += $percentage_part;
			
		 	
		}
        
		if($crc < 100){
			$output = '<div class="alert alert-danger">Les valeurs ne donnent pas de résultat</div> ';
			return print($output);
		}

			

		$chart = new Highchart();
		$this->container = MD5(uniqid(rand(), true));
		$this->width = $width;
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');


		$chart->chart->renderTo = $this->container;
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->exporting->enabled = true;
		$chart->title->text = $this->titre;
		$item = $this->items;


		$chart->tooltip->formatter = new HighchartJsExpr(
			"function() {
				return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.point.nbr, 0) +' ' + this.series.options.item;}
            ");
		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = 1;
		$chart->plotOptions->pie->dataLabels->color = "#000000";
		$chart->plotOptions->pie->dataLabels->connectorColor = "#000000";
        
		$chart->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr(
			"function() {
				return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.y, 1) +' %'; }"
				);

        $chart->series[] = array(
        	'type' => "pie",
        	'item' => $item,
        	'data' => $arr_nbr_sta,

        	);
        $this->chart_generated = $chart->render();
        $this->Graph_render();
        return print ($this->chart_rended);

        
    }

	/**
	 * [Pie_render Draw an Pie Chart]
	 * @param [type]  $table_vue [Table view with tree column (name, y, nbr )]
	 * @param integer $width     [With of container]
	 */
	public function Pie_render($table_vue, $width = 6, $where = null)
	{
		global $db;
        $where = $where != null ? "WHERE ".$where : null;
		$sql = "SELECT * FROM $table_vue $where";
		if(!$db->Query($sql)){
			var_dump($db->Error());
			return false;
		}else{
			if($db->RowCount())
			{
				$brut_array       = $db->RecordsArray();

                //Format Y and nbr to float value
				foreach($brut_array as $k=>$arr)
				{

					$brut_array[$k]['y']  = (float) $arr['y'];
					$brut_array[$k]['nbr']  = (float) $arr['nbr'];     

				}

				$arr_nbr_sta = $brut_array;


			}else{
				exit('no data yet');
			}


		}


		$chart = new Highchart();
		$this->container = MD5(uniqid(rand(), true));
		$this->width = $width;
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');


		$chart->chart->renderTo = $this->container;
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->exporting->enabled = true;
		$chart->title->text = $this->titre;
		$item = $this->items;


		$chart->tooltip->formatter = new HighchartJsExpr(
			"function() {
				return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.point.nbr, 0) +' ' + this.series.options.item;}
            ");
		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = 1;
		$chart->plotOptions->pie->dataLabels->color = "#000000";
		$chart->plotOptions->pie->dataLabels->connectorColor = "#000000";
        
		$chart->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr(
			"function() {
				return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.y, 1) +' %'; }"
				);

        $chart->series[] = array(
        	'type' => "pie",
        	'item' => $item,
        	'data' => $arr_nbr_sta,

        	);
        $this->chart_generated = $chart->render();
        $this->Graph_render();
        return print ($this->chart_rended);

        
    }

    

    public function column_render($table_vue, $where, $width = 6)
    {
    	global $db;
    	$where = $where != null ? "WHERE ".$where : null;
    	$db->Query("SET lc_time_names = 'fr_FR';");
		$sql = "SELECT * FROM $table_vue $where";

		if(!$db->Query($sql)){
			var_dump($db->Error(). '  '.$sql);
			return false;
		}else{
			if($db->RowCount())
			{
				$brut_array       = $db->RecordsArray();

                //Format Y and nbr to float value
                $axes = array();
                $data = array();
				foreach($brut_array as $k=>$arr)
				{
					
					
                    array_push($axes, $arr['y']);

                    number_format($arr['nbr']);
                    array_push($data, (float) $arr['nbr']);

					  

				}
                
				$arr_nbr_sta = $brut_array;

			}else{
				return false;
			}


		}


		$chart = new Highchart();
		$this->container = MD5(uniqid(rand(), true));
		$this->width = $width;
		$chart->exporting->enabled = true;


		$chart->chart->renderTo = $this->container;
		$chart->chart->type = "column";
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = $this->titre;
		$chart->subtitle->text = null;
		$chart->xAxis->categories = $axes;
		$chart->yAxis->min = 0;
		$chart->yAxis->title->text = $this->name_x;
		$chart->legend->layout = "vertical";
		$chart->legend->backgroundColor = "#FFFFFF";
		$chart->legend->align = "left";
		$chart->legend->verticalAlign = "top";
		$chart->legend->x = 100;
		$chart->legend->y = 70;
		$chart->legend->floating = 1;
		$chart->legend->shadow = 1; 

		
		$chart->tooltip->formatter = new HighchartJsExpr("function() {
			return Highcharts.numberFormat(this.y, 0)+' ".$this->unite."';}"
            );
		
		

		$chart->series[] = array(
			'name' => $this->name_serie,
			'data' => $data
		);
        $this->chart_generated = $chart->render();
        $this->Graph_render();
        return print ($this->chart_rended);
    }

    /**
     * [Graph_render Generate HTML code include js rended]
     */
    private function Graph_render()
    {
    	$main_box_start = '<div class="col-sm-'.$this->width.'"><div class="widget-box">';
    	$main_box_end   = '</div><!-- /.widget-box --></div>';
    	$header = '<div class="widget-header widget-header-flat widget-header-small">
                        <h5 class="widget-title">
                            <i class="ace-icon fa fa-signal"></i>
                            '.$this->titre.'
                        </h5>
                        <div class="widget-toolbar no-border">
                            			
                            <a href="#"  class="filter_highchart" this_c="'.$this->container.'" id_chart="'.MInit::crypt_tp('chart', $this->id_chart).'">
								<i class="ace-icon fa fa-filter"></i>
							</a>
                            <a href="#" data-action="reload" class="refrech_highchart" this_c="'.$this->container.'" id_chart="'.MInit::crypt_tp('chart', $this->id_chart).'">
								<i class="ace-icon fa fa-refresh"></i>
							</a>
						</div>
					</div>';
        $start_body =  '<div class="widget-body">
                        <div class="widget-main">';
        $end_body =  '</div><!-- /.widget-main -->
                    </div><!-- /.widget-body -->' ;   
        $chart = '<div id="'.$this->container.'"></div>
                            <script type="text/javascript">'.$this->chart_generated.'</script>';  
        if(!$this->chart_only)
        {
        	$this->chart_rended = $main_box_start
        	.$header
        	.$start_body
        	.$chart
        	.$end_body
        	.$main_box_end;

        }else{
        	$this->chart_rended = $chart;
        }                                                    
    }

    public function call_chart($chart)
    {
    	//Format file link
    	$file_tplt = MPATH_THEMES.'chart_template/'.$chart.'_chart.php';
    	if(!file_exists($file_tplt)){
    		exit('0#<br>Le Graph n\'existe pas, contactez l\'administrateur'.$file_tplt);
    	}

        //Evry thing ok load template
    	include_once $file_tplt;
    }

    public function table_rank_render($table_vue, $width = 6)
    {
    	global $db;
    	$this->container = MD5(uniqid(rand(), true));
    	$output = $table_header = $table_body = null;
		$sql = "SELECT * FROM $table_vue";
		if(!$db->Query($sql)){
			return false;
		}else{
			if(!$db->RowCount())
			{
				return false;
				
			}
			$brut_array       = $db->RecordsArray();
			
			$headers = array_keys($brut_array[0]);
			$table_header .= '<th class="text-center"><strong>Rank</strong></th>';
			foreach ($headers as $key => $value) {
				$table_header .= '<th class="text-center"><strong>'.$value.'</strong></th>';
				
			}
            $i = 1;
			foreach ($brut_array as $key => $value) {
				
				$table_body .= '<tr>';
                $table_body .= '<td>'.$i.'</td>';
				foreach ($headers as $key_1 => $value_1) {

					$table_body .= '<td>'.$brut_array[$key][$value_1].'</td>';
				}
				                    
				$table_body .= '</tr>';
				$i++;
			}

                
            $main_box_start = $this->chart_only == false ? '<div class="col-sm-'.$width.'"><div class="widget-box">' : null;
    	    $main_box_end   = $this->chart_only == false ? '</div><!-- /.widget-box --></div>' : null;
    	
    	    $output = '
            '.$main_box_start .'
                    <div class="widget-header widget-header-flat widget-header-small">
                        <h5 class="widget-title">
                            <i class="ace-icon fa fa-signal"></i>
                            '.$this->titre.'
                        </h5>
                        <div class="widget-toolbar no-border">
                            			
                            <a href="#"  class="filter_highchart" this_c="'.$this->container.'" id_chart="'.MInit::crypt_tp('chart', $this->id_chart).'">
								<i class="ace-icon fa fa-filter"></i>
							</a>
                            <a href="#" data-action="reload" class="refrech_highchart" this_c="'.$this->container.'" id_chart="'.MInit::crypt_tp('chart', $this->id_chart).'">
								<i class="ace-icon fa fa-refresh"></i>
							</a>
						</div>
                    </div>
                    <div class="widget-body">  
                        <div id="'.$this->container.'">
                            <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
                                <thead>
                                    <tr>
                                        '.$table_header.'
                                    </tr>
                                </thead>
                                <tbody>
                                        '.$table_body.'

                                </tbody>
                            </table>
                        </div>                          
                    </div><!-- /.widget-body -->
                '.$main_box_end.'';

		}
                
		return print($output);
    }
}