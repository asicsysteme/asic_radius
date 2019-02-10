<?php
/**
* DataTable code generator v1.0
*/
class Mdatatable 
{
	private $_data; //data receive from form
    //Declared Variable
    var $tables           = array();//tables of Query
    var $task             = null;//Task called for etat_line and notif status
    var $main_table       = null;//Main table used for notif and status
    var $list_table       = null;
    var $joint            = null;
    var $sqlTot           = null;
    var $sqlRec           = null;
    var $where            = null;
    var $where_s          = null;
    var $where_etat_line  = null;//Where Etat line used when need status and notif
    var $columns          = array();//columns of query array('column' => 'width[#]align')
    var $arr_used_columns = array();//Used final array 
    var $list_col         = null;
    var $need_notif       = true;
    var $order_notif      = null;//Used when call order statut column
    var $params           = array();//Array of $_REQUEST
    var $debug            = false;//Used when want see the full query probleme case
    var $file_name        = null;//Used for Export data 
    var $title_report     = null;//Used on Report exported
    var $error            = true;//Used to check error on methides
    var $log              = null;//return log error
    var $html_table       = null;//Generated html table view
    var $columns_html     = array();//Columns Html table array('title' => title, 'width'=>50, 'align'=>(L,R,C))
    var $js_code          = null;//Rend JS datatable jquery caller
    var $js_notif_col     = null;//Int set the column of notif embedded generally the last one 
    var $js_extra_data    = null;//When we want send more data to ajax datatable
    var $js_order         = null;//Used when we want ordering table by column start form 0 ex:[ 3, "desc" ]
    var $title_module     = null;//Used in HTML View (ex. Factures) 
    var $btn_return       = null;//Used to mak button return if null we use the Main task
    var $btn_add_data     = null;//Used to set more data to Button Add
    var $btn_add_text     = null;//Used to define text of button ADD
    var $btn_add_check    = false;//Used in submodul set to true if no btn add data then remove defalut add btn 
    var $btn_action       = true;//Swap to false if dont want show btn Action
    var $use_filter       = false;//set to true to enable filter (setting into view)
    var $data_filter      = array();// Set columns should be filtred (setting controller)
    var $where_f          = null;//fit with Function get where_filter
    var $tag_filter       = null;//Format Tag br show in header of table when filter applied
    var $task_add         = null;//Setup Task add if different of default task
    var $crypt_col        = null;//Used to crypt an column after DB Query 
    


    public function __construct($properties = array()){
    	$this->params = $_REQUEST;
    }

    

    private function get_list_table()
    {
    	$arr_table = $this->tables;
    	$cont_table = count($arr_table);
    	$i = 0;
    	foreach ($arr_table as $table) {
    		if(++$i === $cont_table)
    		{
    			$v = ' ';
    		}else{
    			$v = ', ';
    		}
    		$this->list_table .= " ".$table."$v";
    	}
        if($this->list_table == null)
        {
            $this->error = false;
            $this->log   = '<br\>Pas de table selectionée';
        } 
    }

    Private function format_col_link($col_array)
    {  
        $text     = $col_array['text'];
        $task     = $col_array['task'];
        $data     = $col_array['data'];
        $dyn_task = false;
        if(array_key_exists('dyn_task', $col_array))
        {
            $dyn_task = $col_array['dyn_task'];
        }       
             
        $task_c = MInit::crypt_tp('task', $task);
        $link = "CONCAT('<a href=\"#\" class=\"this_url_jump\" data=\"id=', $data, '\&$task_c\">', $text,'</a>')";
        //if is dynamique task format link like this
        if($dyn_task)
        {
            $link = "CONCAT('<a href=\"#\" class=\"this_url\" rel=\"', $task,'\" data=\"##', $data,'##\">',$text,'</a>')";
        }
        return $link;
    }

    private function get_list_column()
    {
    	$arr_columns = $this->columns;
    	
    	$list_col = null;
    	
    	$cont_columns = count($arr_columns);

    	$i = 0;

    	foreach ($arr_columns as $key => $value) {
    		
    		if(++$i === $cont_columns && $this->need_notif == false)
    		{
    			$v = ' ';
    		}else{
    			$v = ', ';
    		}

            
            if($value['column'] !== 'statut'){
                switch ($value['type']) {
                    case 'int':
                    $list_col .= " REPLACE(FORMAT(".$value['column'].",0), ',', ' ')  as ".$value['alias']."$v";
                    break;
                    case 'date':
                    $list_col .= " DATE_FORMAT(".$value['column'].",'%d-%m-%Y')  as ".$value['alias']."$v";
                    break;
                    case 'datetime':
                    $list_col .= " DATE_FORMAT(".$value['column'].",'%d-%m-%Y %H:%i:%s') as ".$value['alias']."$v";
                    case 'dateheur':
                    $list_col .= " DATE_FORMAT(".$value['column'].",'%d-%m-%Y %H:%i') as ".$value['alias']."$v";
                    break;
                    case 'link':
                        if(Mreq::tp('export') == 1)
                        {
                            $list_col .= " ".($value['column'])." as ".$value['alias']."$v";
                        }else{
                            $list_col .= $this->format_col_link($value['link'])."$v";
                        }
                    break;
                    case 'html':
                        if(Mreq::tp('export') == 1)
                        {
                            $list_col .= " ".($value['column'])." as ".$value['alias']."$v";
                        }else{
                            $list_col .= " ".($value['html'])." as ".$value['alias']."$v";
                        }
                    break;
                    default:
                    $list_col .= " ".$value['column']." as ".$value['alias']."$v";
                    
                    break;
                }

            }
    		
    		
    	}
       
    	if($this->need_notif && $this->task != null)
    	{
    		$notif_colms = TableTools::line_notif_new($this->main_table, $this->task);
    		$list_col .= $notif_colms;
    	}
    	$this->list_col = $list_col;
        if($this->list_col == null)
        {
            $this->error = false;
            $this->log   = '<br\>Pas de columns insérés';
        }
    }

    /**
     * [where_filter description]
     * @param  [type] $table     [description]
     */
    private function where_filter()
    {
        //Id Table HTML
        $table            = $this->main_table;
        //Col shold be filtres 
        $data_filter      = $this->data_filter;

        $data_form_filter = Cookie::Get($table.'_grid_flt', null);
        if(empty($data_form_filter)){
            return null;
        }
        //When FIlter not null format where filter based to data_filter
        //Format form data to array
        $data_form_filter = json_decode($data_form_filter, true);
        
        $input_name_arr = array_column($data_form_filter, 'name');
        $input_valu_arr = array_column($data_form_filter, 'value');
        
        $data_form_filter = array_combine($input_name_arr, $input_valu_arr);

        
        $base_columns = $this->columns;
        $columns = array_column($this->columns, 'alias');

        $render = $tag_filter = $tag_filter_ex = NULL;
        $tag_filter .= "      <div class=\"btn-group btn-overlap zone_tag_filter\">";
        $tag_filter_ex .= "<ul>";
        foreach ($base_columns as $key => $value)
        {
            
            if(array_key_exists($value['alias'], $data_filter))
            {
                $id    = $value['alias'];
                $type  = $data_filter[$id][0];
                $column = $value['column'];

                
                //$data_input = $data_form_filter[$id];
                
                switch ($type) {
                    case 'int':
                        if($data_form_filter[$id] != null)
                        {
                            $input = MySQL::SQLValue($data_form_filter[$id]);
                            $render .= " AND $column like $input ";
                            $tag_filter .= " <span class=\"label label-info spaced arrowed-right\">".$value['header']." Egale à: ".$data_form_filter[$id]."</span> ";
                            $tag_filter_ex .= " <li>".$value['header']." Egale à: ".$data_form_filter[$id]."</li> ";
                        }
                        break;
                    case 'text':
                        if($data_form_filter[$id] != null)
                        {
                            $input = MySQL::SQLValue("%".$data_form_filter[$id]."%");
                            $render .= " AND $column like $input ";
                            $tag_filter .= " <span class=\"label label-info spaced arrowed-right\">".$value['header']." Contient: ".$data_form_filter[$id]."</span> ";
                            $tag_filter_ex .= " <li>".$value['header']." Contient: ".$data_form_filter[$id]."</li> ";
                            
                        }    
                        
                    //var_dump($data_form_filter[$id]);
                        break;
                    case 'date':
                        if($data_form_filter[$id.'_s'] != null AND $data_form_filter[$id.'_e'] != null)
                        {
                            $date_s = MySQL::SQLValue(date('Y-m-d',strtotime($data_form_filter[$id.'_s'])));
                            $date_e = MySQL::SQLValue(date('Y-m-d',strtotime($data_form_filter[$id.'_e'])));
                            $render .= " AND ($column BETWEEN $date_s AND $date_e) ";
                            $tag_filter .= " <span class=\"label label-info spaced arrowed-right\">".$value['header']." Entre : ".$data_form_filter[$id.'_s']." Et : ".$data_form_filter[$id.'_e']."</span> ";
                            $tag_filter_ex .= " <li>".$value['header']." Entre : ".$data_form_filter[$id.'_s']." Et : ".$data_form_filter[$id.'_e']."</li> ";
                        }
                        
                    //var_dump($data_form_filter[$id.'_s'].'  '.$data_form_filter[$id.'_e']);
                        break;
                    default:
                        # code...
                        break;
                }
                
            }
                    
        }
        
        $tag_filter .= "</div>";
        $tag_filter_ex .= "</ul>";
        $this->where_f = $render;   
        $this->tag_filter = $tag_filter;    
        if(Mreq::tp('export') == 1){
            $this->tag_filter = $tag_filter_ex; 
        }


    }

    private function get_where()
    {
    	// check search value exist
    	$params = $this->params;
    	$where_s = null;
    	if( !empty($params['search']['value']) ) {
            $serch_value = str_replace('+',' ',$params['search']['value']);

            $arr_columns = $this->columns;

            $cont_columns = count($arr_columns);
            $i = 0;

            
            
            foreach ($arr_columns as $key => $value) {
                $operator = $i == 0 ? " AND ( " : " OR ";
                switch ($value['type']) {
                    case 'int':
                    $col = " REPLACE(FORMAT(".$value['column'].",0), ',', ' ')";
                    break;
                    case 'date':
                    $col = " DATE_FORMAT(".$value['column'].",'%d-%m-%Y')";
                    break;
                    case 'datetime':
                    $col = " DATE_FORMAT(".$value['column'].",'%d-%m-%Y %H:%i:%s')";
                    break;
                    default:
                    $col = $value['column'];
                    break;
                }
                if($value['column'] != 'statut')
                {
                    $where_s .=" $operator $col LIKE '%".$serch_value."%' ";
                }
                $i++; 
            }

    		

    		if($this->need_notif && $this->task != null)
    		{
    			$where_s .= TableTools::where_search_etat($this->main_table, $this->task, $serch_value);
    		}
            $where_s .= $this->need_notif == true ? NULL : ')';
    		$this->where_s = $where_s;

    	}
    	if($this->need_notif == true && $this->task != null)
    	{
    		$this->where_etat_line = TableTools::where_etat_line($this->main_table, $this->task);
    	}

    }

    private function get_order()
    {
    	$params = $this->params;
    	$columns = array_column($this->columns, 'column');
    	if($this->need_notif)
    	{
    		$this->order_notif = TableTools::order_bloc($params['order'][0]['column']);
    	}
    	$this->sqlRec .=  " ORDER BY $order_notif ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
        
    }

    private function export_data($format)
    {
    	//Export data to CSV File
        $file_name  = $this->file_name;
    	$title      = $this->title_report;
        $tag_filter = $this->tag_filter; 
        if($file_name == null or $title == null)
        {
            $this->error = false;
            $this->log   = '<br\>Paramètre export manquants';
        }
    	if($format == 'csv')
    	{
    		$header    = array_column($this->columns, 'header');
            
    		Minit::Export_xls($header, $file_name, $title);
    	}elseif(Mreq::tp('format')=='pdf'){
           
            $headers = array();
            $array_width = array_column($this->columns, 'width'); 
            //Return Error 
            $sum_width   = array_sum($array_width);
            
            foreach (array_column($this->columns, 'width') as $value) {
               if(!is_numeric($value)){
                    $this->error = false;
                    $this->log   = '<br\>Les largeurs des columns is_numeric';
                    return false;
                }
            }
            if($sum_width > 100)
            {
                $this->error = false;
                $this->log   = '<br\>La largeur total des columns dépasse 100';
                return false;
            }
            foreach ($this->columns as $key => $value) {
            	$titl  = $value['header'];
            	$width = $value['width'];
            	$align = $value['align'];
            	
                if($sum_width < 100 && $key ==  count($array_width) - 1)
                {                    
                    $width = (100 - $sum_width) + $value['width'];
               }
                $headers[$titl] = $width.'[#]'.$align;
            }
            
            
            
    		if(!Minit::Export_pdf($headers, $file_name, $title, $tag_filter))
            {
                $this->error = false;
                $this->log   = '<br\>Erreur export PDF';
            }

            if($this->error == false){
                return false;
            }


    	}
    }

    public function Query_maker()
    {
        

    	$params = $this->params;
        if(array_key_exists('serch', $params) && $params['serch'] == 1)
        {
            $output = $this->call_filter();
            if($output)
            {
                return $output;
            }
        }
    	$where = $where_s = $sqlTot = $sqlRec = NULL;
    	$data = array();
    	$this->get_list_table();
    	$tables = $this->list_table;
    	$this->get_list_column();
    	$columns = array_column($this->columns, 'column');
    	
    	$colms = $this->list_col;
    	$this->get_where();
        $this->where_filter();
        
    	$where .= $this->where_etat_line;
    	if($this->need_notif){
    		$where .= $this->joint == null ?'' : ' AND '.$this->joint;
    	}else{
    		
            $where .= $this->joint == null ?'' : ' WHERE '.$this->joint;
    	}
    	
    	$where .= $this->where_s == NULL ? NULL : $this->where_s;
        $where .= $this->where_f == NULL ? NULL : $this->where_f;
        
	    //getting total number records without any search
    	$sql = "SELECT $colms  FROM  $tables  ";
    	$sqlTot .= $sql;
    	$sqlRec .= $sql;
	    //concatenate search sql if value exist
    	if(isset($where) && $where != NULL) {

    		$sqlTot .= $where;
    		$sqlRec .= $where;
    	}
    	
       
    	if($this->need_notif)
    	{
    		array_push($columns, 'statut');
            array_push($columns, 'notif');
            $this->order_notif = TableTools::order_bloc($params['order'][0]['column']);
    	}
        $order_column = $order_dir = $order_by = null;
        if(array_key_exists('order', $params)){
            $order_column = $columns[$params['order'][0]['column']];
            $order_dir    = $params['order'][0]['dir'];
            $order_by = ' ORDER BY ';
        }
        
    	$order_notif = $this->order_notif;
    	$sqlRec .=  " $order_by $order_notif  $order_column   $order_dir  LIMIT ".$params['start']." ,".$params['length']." ";
        //Istance od DB connexion
        global $db;
    	if (!$db->Query($sqlTot)){

            $this->error = false;
            $this->log   = '<br\>Erreur SQL'.$db->Error()." $sqlTot";

        } 
	    //Get total of records
        $totalRecords = $db->RowCount();
        //Case Export call export methode
        if(Mreq::tp('export') == 1){
        	if(!$this->export_data(Mreq::tp('format')))
            {
                return false;
            }
        }

    	if($this->debug == true){
    		exit($sqlRec);
    	}
    	//Get only req for the LIMIT param
    	//if (!$db->Query($sqlRec)) $db->Kill($db->Error()." SQLREC $sqlRec");
	    if (!$db->Query($sqlRec)){

            $this->error = false;
            $this->log   = '<br\>Erreur SQL'.$db->Error()." $sqlRec";

        }

	    //iterate on results row and create new index array of data
    	while (!$db->EndOfSeek()) {
    		$row = $db->RowValue();
    		$data[] = $row;
    	}
		
        if($this->crypt_col != null)
        {
            function column_cryptor(&$arr_data, $key, $prefix)
            {
                if (preg_match('/##(.*?)##/', $arr_data, $match) == 1) 
                {                   
                    $crypt_data = MInit::crypt_tp('id', $match[1]);
                    $arr_data   = str_replace('##'.$match[1].'##', $crypt_data, $arr_data);
                }
            }
            array_walk_recursive($data, 'column_cryptor', 'field');
            //var_dump($data);
        }

        
    	$json_data = array(
            "filter"          => $this->tag_filter,
    		"draw"            => intval( $params['draw'] ),   
    		"recordsTotal"    => intval( $totalRecords ),  
    		"recordsFiltered" => intval( $totalRecords),
			"data"            => $data   // total data array
		);
        
        if($this->error == false)
        {
            return false;
        }
        
    	return json_encode($json_data);
    }


    private function get_html_column()
    {
        $header_table = array_column($this->columns_html, 'header');
        foreach ($header_table as $key => $value) {
            $this->list_col .="\t<th>\n\t$value\t</th>\n";
        }
        /*$statu_header = $this->btn_action ? '#' : 'Statut';
        $this->list_col .="\t<th>\n\t$statu_header\t</th>\n";*/
        if($this->btn_action)
        {
            $this->list_col .="\t<th>\n\t#\t</th>\n";
        }
        
    }



    public function js_render()
    {
        $count_col = count($this->columns_html) - 1;
        $order = $this->js_order == null ? null : '"order": ['.$this->js_order.'],';
        $extra_data = $this->js_extra_data == null ? null : 'extra_data :"'. $this->js_extra_data.'",';
        $notif_col = $this->need_notif == true ? $count_col : 0;
        $js = "<script type=\"text/javascript\">$(document).ready(function() {";
        //delete cookie filter if exist
        $js .= "ace.cookie.remove('".$this->task."_grid_flt');";
        $js .= "var table = $('#".$this->task."_grid').DataTable({";
        if(!$this->btn_action)
        {
            $js .= "aoColumnDefs : '',";
        }
               
        $js .= "bProcessing: true,notifcol : ".$notif_col.",serverSide: true,ajax_url:\"".$this->task."\", $extra_data $order aoColumns: [";
        
        $js_arr = $this->columns_html;

        foreach ($js_arr as $key => $value) {
            switch ($value['align']) {
                case 'C':
                    $aling = 'center';
                    break;
                case 'R':
                    $aling = 'alignRight';
                    break;
                default:
                    $aling = 'left';
                    break;
            }
            $sWidth = !is_numeric($value['width']) ? 10 : $value['width'];
            $js .= "{\"sClass\": \"$aling\",\"sWidth\":\"$sWidth%\"},";
        }
        if($this->btn_action){
            $js .= "{\"sClass\": \"center\",\"sWidth\":\"5%\"},";
        }
        
        $js .= "],});";
        //last blocjs
        $js .= "$('.export_csv').on('click', function() {csv_export(table, 'csv');});";
        $js .= "$('.export_pdf').on('click', function() {csv_export(table, 'pdf');});";
        $js .= "$('.show_zip').on('click', function() {exec_zip(table, '".$this->task."_grid' );});";
        if($this->use_filter)
        {
            $js .= "$('.btn_search').on('click', function() {exec_search(table, '".$this->task."_grid');});";
            $js .= "$('.btn_rmv_search').on('click', function() {exec_rmv_search(table, '".$this->task."_grid');});";
        }
        
        $js .= "$('#".$this->task."_grid').on('click', 'tr button', function() {
            var row = $(this).closest('tr');
            append_drop_menu('".$this->task."', table.cell(row, 0).data(),'.btn_action');});});</script>";
        $this->js_code = $js;
        
    }

    public function table_html()
    {
        $task_return = $this->btn_return == null ? $this->task : $this->btn_return;
        $html = "";
        $html .= $this->btn_reply();
        $html .= "\t<div class=\"page-header\">\n\t<h1>\n";
        $html .= $this->title_module;
        $html .= "<small><i class=\"ace-icon fa fa-angle-double-right\"></i></small>\t</h1>\n\t</div>\n";
        $html .= "\t<div class=\"row\">\n\t<div class=\"col-xs-12\"\n>\t<div class=\"clearfix table_zone_setting\">\n";
        $html .= "\t<div class=\"pull-left tableTools-container\"\n>\t<div class=\"btn-group btn-overlap\"\n>\t<a href=\"#\" class=\" btn btn-purple btn-bold  spaced btn_rmv_search hide\"\n>\t<span><i class=\"fa fa-refresh\"></i>  Afficher tout</span\n></a>\n\t</div>\n\t</div>\n";
        $html .= "\t<div class=\"pull-right tableTools-container\">\n";
        $html .= "\t<div class=\"btn-group btn-overlap\">\n";
        $task_add = $this->task_add == null ? 'add'.$this->task : $this->task_add;
        
        if($this->btn_add_data != null OR !$this->btn_add_check){
            $text_btn = $this->btn_add_text = NULL ? $this->title_module : $this->btn_add_text;
            $html .= $this->btn_add($task_add,'Ajouter '.$text_btn, $this->btn_add_data);
        }
        
        $html .= $this->btn_search();
        $html .= $this->btn_csv($this->task,'Exporter Liste');
        $html .= $this->btn_pdf($this->task,'Exporter Liste');
        $html .= $this->btn_zip();
        
       

        $html .= "\t</div>\n\t</div>\n\t</div>\n";
        $html .= "\t<div id=\"head_".$this->task."_grid\" class=\"table-header\">\tListe ".$this->title_module."</div>\n";
       
        $html .= "\t<div>\n<table id=\"".$this->task."_grid\" class=\"table table-bordered table-condensed table-hover table-striped dataTable no-footer\">\n";
        $html .= "\t<thead>\n\t<tr>\n";
        $this->get_html_column();
        $html .= $this->list_col;
        $html .="\t</tr>\n\t</thead>\n\t</table>\n\t</div>\n\t</div>\n\t</div>\n";
        $this->js_render();
        $html .= $this->js_code;
        return $html;
        
   
    }

    private function btn_reply()
    {
        $html = null;
        if($this->btn_return != null && is_array($this->btn_return))
        {
            $arr   = $this->btn_return;
            $task  = array_key_exists('task', $arr) ? $arr['task'] : 'tdb';
            $title = array_key_exists('title', $arr) ? $arr['title'] : 'Retour';
            $data  = array_key_exists('data', $arr) ? $arr['data'] : '';
            
            $html  = "\t<div class=\"pull-right tableTools-container\">\n\t<div class=\"btn-group btn-overlap\">";
            
            $html .='<a href="#" rel="'.$task.'&'.$data.'" class=" btn btn-white this_url btn-info btn-bold  spaced"><span><i class="fa fa-reply"></i> '.$title.'</span></a>'; 
            $html .= "\t</div>\n\t</div>\n";
        }

        return $html;
           
    
    }

    /**
     * [btn_add Add Btn to an table ]
     * @param  [string] $app     [App for ste _tsk]
     * @param  [text] $text    [Text of Button]
     * @param  [url setting] $add_set [Parameteres to be add to url]
     * @param  [int] $exec    [set to 1 is we want use ]
     * @param  [string] $icon    [icon]
     * @return [Html]          [render html or null]
     */
    private function btn_add($app, $text=NULL, $add_set=NULL, $exec = NULL, $icon = NULL){
        global $db;
        $userid = session::get('userid');
        $sql = "SELECT
        1
        FROM
        `sys_rules`
        INNER JOIN `sys_task` 
        ON (`sys_rules`.`appid` = `sys_task`.`id`)
        INNER JOIN `sys_task_action` 
        ON (`sys_task_action`.`appid` = `sys_task`.`id`) AND (`sys_rules`.`action_id` = `sys_task_action`.`id`)
        INNER JOIN `sys_users` 
        ON (`sys_rules`.`userid` = `sys_users`.`id`)
        WHERE (sys_users.id = $userid OR $userid = 1) 

        AND sys_task.app =  ".MySQL::SQLValue($app)." ";

        $permission = $db->QuerySingleValue0($sql);

        $exec_class = $exec == NULL ? 'this_url' : 'this_exec';
        $icon_class = $icon == NULL ? 'plus' : $icon;
        $output = null;
        if($permission == 1 OR $userid == 1 ){
            $output = '<a href="#" rel="'.$app.'&'.$add_set.'" class=" btn btn-white btn-info btn-bold '.$exec_class.' spaced"><span><i class="fa fa-'.$icon_class.'"></i> '.$text.'</span></a>';
        }      

        $render = ($output);


        return $render ;

    }
    
    /**
     * [btn_csv description]
     * @param  [type] $app  [description]
     * @param  [type] $text [description]
     * @return [type]       [description]
     */
    private function btn_csv($app, $text)
    {
        $output = '<a title="Export XLS" href="#"  class="ColVis_Button ColVis_MasterButton btn btn-white btn-info btn-bold export_csv"><span><i class="fa fa-file-excel-o fa-lg" style="color:green"></i></span></a>';


        $render = ($output);


        return $render ;
    }


    /**
     * [btn_pdf description]
     * @param  [type] $app  [description]
     * @param  [type] $text [description]
     * @return [type]       [description]
     */
    private function btn_pdf($app, $text)
    {
        $output = '<a title="Export PDF" href="#"  class="ColVis_Button ColVis_MasterButton btn btn-white btn-info btn-bold export_pdf"><span><i class="fa fa-file-pdf-o fa-lg" style="color:red"></i></span></a>';


        $render = ($output);


        return $render ;
    }

    /**
     * [btn_zip description]
     * @param  [type] $app  [description]
     * @param  [type] $text [description]
     * @return [type]       [description]
     */
    private function btn_zip()
    {
        //$output = '<a title="Afficher Archive" href="#"  class="btn_zip ColVis_Button ColVis_MasterButton btn btn-white btn-info btn-bold show_zip"><span><i class="fa fa-file-archive-o fa-lg blue" style="color:"></i></span></a>';

        $output = '<a title="Archive" href="#"  class="btn_zip btn btn-white btn-bold show_zip"><span><i class="fa fa-file-archive-o fa-lg blue"></i></span></a>';
        $render = ($output);


        return $render ;
    }
    /**
     * [btn_search description]
     * @return [type]       [description]
     */
    private function btn_search()
    {
        if(!$this->use_filter){
            return null;
        }
        
        $output = '<a title="Recherche avancée" href="#"  class="btn_search btn btn-white btn-bold "><span><i class="fa fa-search fa-lg purple"></i></span></a>';
        $render = ($output);


        return $render ;
    }  

    private function call_filter()
    {

        $base_columns = $this->columns;
        $columns = array_column($this->columns, 'alias');
        $arr_filter = $this->data_filter;
        $render_js = null;
        $render = '<form id="form_'.$this->main_table.'_grid" action="#" class="form-horizontal">';
        foreach ($base_columns as $key => $value)
        {
            
            if(array_key_exists($value['alias'], $arr_filter))
            {
                $id    = $value['alias'];
                $type  = $arr_filter[$id][0];
                $text  = $value['header'];
                $class = $arr_filter[$id][1];
                switch ($type) {
                    case 'text':
                        $render .= Mform::input_x($id, $text, $class);
                        break;
                    case 'int':
                        $render .= Mform::input_x($id, $text, $class.' is-number alignRight');
                        break;    
                    case 'date':
                        $render .= Mform::date_x($id, $text, $class);
                        $render_js .= '$(\'#'.$id.'_s, #'.$id.'_e\').datepicker()
                               .next().on(ace.click_event, function(){
                                    $(this).prev().focus();
                               });';
                        break;
                    default:
                        # code...
                        break;
                }
                
            }
                    
        }
        
        $render .= '</form>';
        $render .= '<script>';
        $render .= $render_js;
        $render .= '</script>';
        return $render ;
    }  
}