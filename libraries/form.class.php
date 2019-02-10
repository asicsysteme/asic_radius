<?php
/**
* Mform Class V1.0.0
* Generate dynamique Form
* 
* 
*/
class Mform
{
	private $_id_form;
    private $_app_exec;
    private $_is_edit;
    private $_app_redirect;
    private $_is_wizard;
    private $_is_modal;
    var $form_bloc             = Null;
    var $form_js_bloc          = Null;
    var $input_js_file         = Null;
    var $input_js_date         = Null;
    var $input_js_editor       = Null;
    var $input_js_autocomplete = Null;
    var $select_js_onchange    = Null;
    var $input_tag             = Null;
    var $input_chosen_class    = Null;
    var $js_rules              = Null;
    var $js_message            = Null;
    var $js_addfunct           = Null;
    var $js_datatable          = Null;
    var $form_fields           = Null;
    var $form_button           = Null;
    var $gallery_bloc          = Null;
    var $gallery_bloc_js       = Null;
    var $error                 = Null;
    var $wizard_steps_bloc     = Null;
    var $wizard_steps          = null;
    var $form_subbloc          = null;
    var $verif_value           = null;
    var $extra_html            = null;
    var $start_bloc            = null;
    var $end_bloc              = null;
    
    


	/**
     * [__construct description]
     * @param [string] $id_form      [Id de formulaire]
     * @param [string] $app_exec     [Action de formulaire]
     * @param [int] $is_edit [set to id of item if is form edit]
     * @param [string] $app_redirect [Application de redirection] 
     * @param [string] $is_wizard [Set to 1 if is an Wizard Form]
     * @param [string] $is_modal [Set to 1 if is an Modal Form]
     */
	function __construct($id_form, $app_exec, $is_edit, $app_redirect, $is_wizard, $is_modal=null)
	{
      $this->_id_form      = $id_form;
      $this->_app_exec     = $app_exec;
      $this->_is_edit      = $is_edit;
      $this->_app_redirect = $app_redirect;
      $this->_is_wizard    = $is_wizard;
      $this->_is_modal     = $is_modal;
    }
  function __destruct() {

  }

	/**
	 * Function form render
	 * @return html [Form bloc all fields]
	 */

	public function render()
	{
        //Set Form Token
        $ssid = 'f_v'.$this->_id_form;
        session::clear($ssid);
        session::set($ssid,session::generate_sid());
        $verif_value       = session::get($ssid);
        $this->verif_value = $verif_value;
        

        //If is Wizard start with bloc Wizard 
        if($this->_is_wizard == 1)
        {
            $this->form_bloc .= '<div class="widget-body">
            <div class="widget-main">
            <div id="fuelux-wizard-container">';
            $this->form_bloc .= $this->wizard_form_steps();                            
        }


        $this->form_bloc .= '<form novalidate="novalidate" method="post" class="form-horizontal" id="'.$this->_id_form.'" action="#">';
        $this->form_bloc .= '<input name="verif" type="hidden" value="'.$verif_value.'" />';
        if($this->_is_wizard == 1)
        {
            $this->form_bloc .= '<div class="step-content pos-rel">';
        }

        $this->form_bloc .= '<fieldset>';
        $this->form_bloc .= $this->form_fields;
        $this->form_bloc .= '</fieldset>';

        if($this->_is_wizard == 1)
        {
            $this->form_bloc .= '</div>';
        }else{
            $this->form_bloc .= $this->form_button;
        }

        $this->form_bloc .= '</form>';

        //If is Wizard End with bloc Wizard 
        if($this->_is_wizard == 1)
        {
            $this->form_bloc .= '</div>
            <hr />
            <div class="wizard-actions">
            <button class="btn btn-prev">
            <i class="ace-icon fa fa-arrow-left"></i>
            précédent
            </button>
            <button type="submit" class="btn btn-success btn-next" data-last="Finish">
            Suivant
            <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
            </button>
            </div>
            </div>
            </div>';
        }




        $this->form_bloc .= $this->js_render();

        return print($this->form_bloc);  
    }

    /*public function ($value='')
    {
      # code...
    }*/

    /**
     * Function wizard_form_steps
     * @return [HTML] [Bloc Steps for form Wizard]
     */

    public function wizard_form_steps()
    {
        $this->wizard_steps_bloc .= '<div><ul class="steps">';
        $array_steps = $this->wizard_steps; 


        foreach ($array_steps as $step) {
            //Step
            $active = '';
            if(isset($step[2]) && $step[2] == 'active')
            {
                $active = ' class="active" '; 
            }
            
            $this->wizard_steps_bloc .= '<li data-step="'.$step[0].'" '.$active.'>
            <span class="step">'.$step[0].'</span>
            <span class="title">'.$step[1].'</span>
            </li>';
        }                                                
        $this->wizard_steps_bloc .= '</ul></div><hr />';




        return $this->wizard_steps_bloc;
    }


    /**
    * [step_start Start Step Bloc and put fields inside]
    * @param  [int] $id    [Number of step]
    * @param  [text] $titre [Title of this step]
    * @return [Html]        [Bloc Html render]
    */
    public function step_start($id, $titre)
    {
        $start_step  = '<div class="step-pane" data-step="'.$id.'">';
        $start_step .= '<h3 class="lighter block green">'.$titre.'</h3>';
        $this->form_fields .= $start_step;
    }


    /**
    * @return append closed div to step wizard
    */
    public function step_end()
    {
        $this->form_fields .= '</div>';

    }


    /**
     * [bloc_title description]
     * @param  [string] $title     [description]
     * @param  [stirng] $sub_title [description]
     * @return push Html into form_bloc
     */
    public function bloc_title($title, $sub_title = null)
    {
        $this->form_fields .=   '<h3 class="header smaller lighter blue">
        '.$title.'
        <small>'.$sub_title.'</small>
        </h3>';
    }


    public function sub_bloc_start($large, $title)
    {
        $this->form_fields .= '<div class="col-sm-'.$large.'">
        <div class="widget-box">
        <div class="widget-header widget-header-flat widget-header-small">
        <h5 class="widget-title">'.$title.'</h5>
        </div>
        <div class="widget-body">
        <div class="widget-main">
        <div id="container">';

    }

    public function sub_bloc_end()
    {
        $this->form_fields .= '</div></div>
        </div>
        </div>
        </div>';
    }

	/**
	 * Function form JS render
	 * @return JS  [JavaScript]
	 * 
	 */
	private function js_render()
	{
		

		$this->form_js_bloc .= '<script type="text/javascript">jQuery(function($) {'; //Start bloc
		$this->form_js_bloc .= '$("#'.$this->_id_form.'").validate({'; // Start Validate Bloc
		$this->form_js_bloc .= 'execApp:"'.$this->_app_exec.'",'; 
		$this->form_js_bloc .= 'execNext:"'.$this->_app_redirect.'",';
        $this->form_js_bloc .= 'isedit:"'.$this->_is_edit.'",';
        $this->form_js_bloc .= 'ismodal:"'.$this->_is_modal.'",';
        $this->form_js_bloc .= 'addFunct:function(){'.$this->js_addfunct.'},';
		$this->form_js_bloc .= 'rules:{'; // Start Rules Bloc
		$this->form_js_bloc .= $this->js_rules;
		$this->form_js_bloc .= '},'; //ENd Rules Bloc
		$this->form_js_bloc .= 'messages:{'; // Start Rules Bloc
		$this->form_js_bloc .= $this->js_message;
		$this->form_js_bloc .= '},'; //ENd Rules Bloc
		$this->form_js_bloc .= '});'; //End Validate Bloc
    $this->form_js_bloc .= $this->input_js_file; // Bloc of format input files
    $this->form_js_bloc .= $this->input_js_date; // Bloc of Date input element
    $this->form_js_bloc .= $this->input_js_editor; // Bloc of Editor input element
    $this->form_js_bloc .= $this->input_js_autocomplete; //Bloc of Autocomplete Input
    $this->form_js_bloc .= $this->select_js_onchange; //Bloc of Autocomplete Input
    $this->form_js_bloc .= $this->input_tag; //Bloc of input Tag
    $this->form_js_bloc .= $this->gallery_bloc_js; //Bloc of gallery_bloc 
    $this->form_js_bloc .= $this->js_datatable; //Bloc of DataTable JS
    $this->form_js_bloc .= "$('.chosen-select').chosen({allow_single_deselect:true});";

        //$this->form_js_bloc .= "$('.chosen-select').chosen({allow_single_deselect:true});";


        //Check if is Wizard form insert JS for wizard
    if($this->_is_wizard == 1)
    {
        $this->form_js_bloc .= '$(\'#fuelux-wizard-container\')
        .ace_wizard({
                    //step: 2 //optional argument. wizard will jump to step "2" at first
                    //buttons: \'.wizard-actions:eq(0)\'
            })
            .on(\'actionclicked.fu.wizard\' , function(e, info){
                if(!$(\'#'.$this->_id_form.'\').valid()) e.preventDefault();
                })
                .on(\'finished.fu.wizard\', function(e) {
                    if(!$(\'#'.$this->_id_form.'\').valid()) e.preventDefault();
                    $(\'#'.$this->_id_form.'\').submit();

                    }).on(\'stepclick.fu.wizard\', function(e){
                    //e.preventDefault();//this will prevent clicking and selecting steps
                    });';


                }



        $this->form_js_bloc .= '});</script>'; //End Bloc
        return $this->form_js_bloc;


    }

    public function js_rules($field, $array_rules )
    {




      $this->js_rules .= $field. ':{';
      $this->js_message .= $field. ':{';
      foreach ($array_rules as $rule) {

			//Rule
         $value = $rule[1] == 'true' ? $rule[1] : '\''. $rule[1].'\'';
         $depend_rule = isset($rule[3]) ? $rule[3] : null;
         $value = $depend_rule == null ? $value : $depend_rule;
         $this->js_rules .= $rule[0].' : '.$value.',';
		  //Message
         $this->js_message .= $rule[0].' : \''.$rule[2].'\''.',';

     }
     $this->js_rules .= '},';
     $this->js_message .= '},';



 }
    /**
     * JS Render Input type File
     * @param string $[input_id] [Id of input file]
     * @param int $[file_size] [Size of uplaoded file]
     * @param string $[file_type] [Type of uploaded file]
     * @return JS code [Append JS Render]
     * 
     */
    public function file_js($input_id, $file_size, $file_type, $value = null, $edit = NULL)
    {

        $this->input_js_file .= 'fliupld(\''.$input_id.'\', '.$file_size.', \''.$file_type.'\' , \''.$value.'\', \''.$edit.'\');';
    }



    /**
     * [js_add_funct Add special JS Function for validate form]
     * @param  [string] $function [JS code from render]
     * @return [JS CODE]           [Append js_render ]
     */
    public function js_add_funct($function = Null)
    {
        $this->js_addfunct .= $function;
    }

    /**
     * Function Button
     * @param strin $[btn_value] [text of button]
     * @return append [append form_button html]
     */

    public function button($btn_value='')
    {
    	$this->form_button .=
    	'<div class=" clearfix form-actions">
    	<div class="col-md-offset-3 col-md-9">
       <button class="btn btn-primary" type="submit">
       <i class="ace-icon fa fa-check bigger-110"></i>'.$btn_value.'
       </button>
       </div>
       </div>';

   }

   public function gallery_bloc($txt_button = NULL, $label_arr = null, $array_edit = null)
   {
  //Label for files be uploaded
      if($label_arr == null)
      {
         $label = null;
     }else{
      $label = '<ul class="list-unstyled spaced1">';
      foreach ($label_arr as $key => $lbl) {
          $label .= '<li class="text-warning bigger-110 blue"><i class="ace-icon fa fa-exclamation-triangle"></i>'.$lbl[0].'</li>';
      }
      $label .= '</ul>';
  }
  $bloc_pic_edit = $array_edit == NULL ? NULL : MInit::get_pictures_gallery($array_edit);
  //Creat input photo_id[] and photo_titl[]
  $txt_btn = $txt_button == NULL ? 'Ajouter Image' : $txt_button;
  $this->form_fields .= '<div class="col-xs-12 " id="btn_add_pic">
  <span class="btn btn-white btn-info btn-bold" rel="add_pic">
  <span class="this_add_pic">
  <i class="fa fa-camera-retro "></i> '.
  $txt_btn.
  '</span>
  </span>
  <div>
  '.$label.'
  <ul class="ace-thumbnails clearfix">'.$bloc_pic_edit.'</ul>
  </div>
  </div>';

  $this->gallery_bloc_js .= "$('#btn_add_pic').on('click', '.this_add_pic', function() {bb_add_pic('add_pic','Ajouter une Image','mm')});";

}

    /**
     * Generate Hidden Input
     * @param string $[input_id] [id / name of input]
     * @param string $[input_value] [Value of input]
     * @return append string into form_fields [Input render]
     */

    public function input_hidden($input_id, $input_value = Null)
    {
        $input = '<input type="hidden" name="'.$input_id.'" id="'.$input_id.'" value="'.$input_value.'"  />';
        $this->form_fields .= $input;
    }

    public function extra_html($id_bloc, $html_code)
    {

      $input = '<div id="'.$id_bloc.'" class="widget-main padding-24">'.$html_code.'</div>';
      
      $this->form_fields .= $input;

  }

    /**
     * Function Input
     * Generate input field
     * @param string $[input_id] [id of input]
     * @param string input_type (text, password)
     * @param integer $[input_class] [class of input]
     * @param string $[input_value] [value of input default NULL]
     * @return append string into form_fields [Input render]
     * 
     */
    
    public function input($input_desc, $input_id, $input_type, $input_class, $input_value = null, $js_array = null, $hard_code = null, $readonly = null)
    {
    	$value = $input_value == null ? null : 'value = "'.$input_value.'"';
      $readonly_use = $readonly == null ? null : 'readonly=""';
      $input = '<div class="space-2"></div>
      <div class="form-group">
      <label id="label_'.$input_id.'" class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">'.$input_desc.':</label>

      <div class="col-xs-12 col-sm-9">
      <div class="clearfix">';
      if($input_type == 'checkbox')
      {
        $checked = $input_value == null ? null : 'checked';
        $input .= '<div class="checkbox">
        <label>
        <input '.$checked.' '.$readonly_use.' name="'.$input_id.'" id="'.$input_id.'" class="ace ace-checkbox-2" type="checkbox">
        <span class="lbl"> '.$input_desc.'</span>
        </label>
        </div>
        ';


    }else{

        $input .= '<input type="'.$input_type.'" name="'.$input_id.'" id="'.$input_id.'" class="col-xs-'.$input_class.' col-sm-'.$input_class.'" '.$value.' '.$readonly_use.' autocomplete="off" />';
        $input .= $hard_code;
    }
    $input .= '</div>
    </div>
    </div>';
    $this->form_fields .= $input;
    if($js_array != null)
    {
        $this->js_rules($input_id, $js_array);
    }



}

/**
 * [alert_message Show message Alert ]
 * @param  [string] $message [Message to be show]
 * @param  [string] $style   [Style of tag 
 * (red => danger, orange => warning , green => success, blue => info)]
 * @return [type]          [description]
 */
public function alert_message($message, $style)
{
  $message = '<div class="alert alert-'.$style.'">
  <button type="button" class="close" data-dismiss="alert">
  <i class="ace-icon fa fa-times"></i>
  </button>
  '.$message.'
  <br>
  </div>';
  $this->form_fields .= $message;                  
}

public function message_info($msg, $color)
{
                      $message = '<div class="alert alert-block alert-'.$color.'">
                      <button type="button" class="close" data-dismiss="alert">
                      <i class="ace-icon fa fa-times"></i>
                      </button>
                      '.$msg.'
                      </div>';
                      $this->form_fields .= $message;
}

public function input_date($input_desc, $input_id, $input_class, $input_value = null, $js_array = null, $hard_code = null)
{
    $value = $input_value == null ? null : 'value = "'.date('d-m-Y',strtotime($input_value)).'"';
    $input = '<div class="space-2"></div>
    <div class="form-group">
    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">'.$input_desc.':</label>

    <div class="col-xs-12 col-sm-3">
    <div class="clearfix input-group">';


    $input .= '<input type="text" name="'.$input_id.'" id="'.$input_id.'" class="form-control col-xs-12 col-sm-'.$input_class.'" '.$value.' />';
    
    $input .= '<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>';
    
    $input .= '</div></div></div>';
    $input .= $hard_code;
    $this->form_fields .= $input;
    $this->input_js_date .= '$(\'#'.$input_id.'\').datepicker()
                //show datepicker when clicking on the icon
    .next().on(ace.click_event, function(){
        $(this).prev().focus();
    });';
    if($js_array != null)
    {

        $date_array  = array('dateISO', 'true', 'Insérer date Valid' );
        array_push($js_array, $date_array);
        $this->js_rules($input_id, $js_array);
    }



}


public function inline_input($main_label=null, $array_input)
{
    $input = '<div class="space-2"></div>
    <div class="form-group">';
    if ($main_label) {
        $input .= '<label class="control-label col-xs-12 col-sm-3 no-padding-right">'.$main_label.':  </label>';
    }

    $input .= '<div class="col-xs-12 col-sm-9">';
    foreach ($array_input as $input_inline) {
        $label       = $input_inline[0] == Null ?Null : '<label class="control-label col-xs-12 col-sm-6 no-padding-left">'.$input_inline[0].': </label>';
        $label_end   = $input_inline[0] == Null ?Null : '<span class="lbl">'.$input_inline[0].'</span></label>';
        $input_id    = $input_inline[1];
        $input_type  = $input_inline[2];
        $input_class = $input_inline[3];
        $input_value = $input_inline[4];
        $class_first_input = $input_inline[0] == Null ? '12': '6';
        $value = $input_value == null ? null : 'value = "'.$input_value.'"';

        $js_array    = $input_inline[5];

        if($input_type == 'checkbox'){
            $checked = $input_value == null ? null : 'checked';
            $input .= '<div class="checkbox col-sm-'.$input_class.' no-padding-left">
            <label>
            <input '.$checked.' name="'.$input_id.'" id="'.$input_id.'" class="ace ace-checkbox-2" type="checkbox">
            <span class="lbl"> '.$input_inline[0].'</span>
            </label>
            </div>';


        }else{
                //$input .= '<div class="col-sm-'.$input_class.' no-padding-left">'.$label.'<input type="'.$input_type.'" name="'.$input_id.'" id="'.$input_id.'" class="col-xs-12 col-sm-'.$class_first_input.'" '.$value.' /></div>';
            $input .= '<div class="col-sm-'.$input_class.' no-padding-left">
            <label>
            '.$label.'
            <input name="'.$input_id.'" id="'.$input_id.'" class="col-xs-12 col-sm-'.$class_first_input.'" '.$value.' type="'.$input_type.'">

            </label>
            </div>';

        }



        if($js_array != null)
        {
            $this->js_rules($input_id, $js_array);
        }


    }

    $input .=' </div>
    </div>';
    $this->form_fields .= $input;
}

public function radio($radio_desc, $radio_id, $radio_value = null, $array_radio,  $js_array = null)
{
    $input = '<div class="space-2"></div>
    <div class="form-group">
    <label class="control-label col-xs-12 col-sm-3 no-padding-right">'.$radio_desc.':  </label>
    <div class="col-xs-12 col-sm-9">';
    foreach ($array_radio as $radio) {
        $checked = $radio[1] == $radio_value ? 'checked' : null;
        $input .= '<div>
        <label class="line-height-1 blue">
        <input id="'.$radio_id.'" name="'.$radio_id.'" '.$checked.' value="'.$radio[1].'" type="radio" class="ace" />
        <span class="lbl"> '.$radio[0].'</span>
        </label>
        </div>';



    }
    if($js_array != null)
    {
        $this->js_rules($radio_id, $js_array);
    }
    $input .= '</div>
    </div>';
    $this->form_fields .= $input;

}

/**
* Function select_option_only used generally when have load on select
     * Generate Select options from Table 
     
     
     * @param string table Table name
     * @param string $[id_table] [<column of value option>]
     * @param string $[order_by] [<column of order>]
     * @param string $[txt_table] [<column of text option>]
     * @param string $[indx] [<First option if declared default = NUll>]
     * @param string $[selected] [<column of value option>]
     * @return append string into form_fields [Input render]
*/

public function select_option_only($table, $id_table, $order_by , $txt_table ,$selected = NULL, $multi = NULL, $where = NULL)
{
  if($multi != NULL && $selected != NULL)
  {
    $array_exist = str_replace('[-', '"', $selected);
    $array_exist = str_replace('-]', '"', $array_exist);
    $array_exist = '['.str_replace('-', '","', $array_exist).']';
    $array_exist = json_decode($array_exist,true);

}
$multiple = $multi == NULL ? NULL : 'multiple=""';


$output = null;
$where   =  $where == NULL ? NULL: " WHERE ".$where." ";


global $db;
$sql = "SELECT $id_table as id, $txt_table as text FROM $table $where order by $order_by limit 0,1000 ";
if (!$db->Query($sql)){
 $db->Kill($db->Error());
}
if(!$db->RowCount()){
    $output .='<option value=""></option>';

}else{
  while (! $db->EndOfSeek()) {
      $row = $db->Row();
      if($selected != NULL){
        if($multiple != NULL) 
        {                    


            if(in_array($row->id, $array_exist))
            {
               $bloc_multipl_show .= ' '.$row->text.' - '; 
           }

       }else{
        $select =  $row->id == $selected ? "selected":"";
    }


}else{
    $select ="";
}
$output .= '<option '.$select.' value="'.$row->id.'">'.$row->text.'</option>';               
}

}
return $output;

}

    /**
     * Function Select_table
     * Generate Select field from Table 
     * @param string $[input_id] [id of input]
     * @param integer $[input_class] [class of input]
     * @param string $[input_value] [value of input default NULL]
     * @param string table Table name
     * @param string $[id_table] [<column of value option>]
     * @param string $[order_by] [<column of order>]
     * @param string $[txt_table] [<column of text option>]
     * @param string $[indx] [<First option if declared default = NUll>]
     * @param string $[selected] [<column of value option>]
     * @return append string into form_fields [Input render]
     */

    public function select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null, $hard_code = null ) 
    {
        $class_chosen = ($input_class * 100) / 12;
        $bloc_multipl_show = $select = $array_exist = NULL ;
        
        if($multi != NULL && $selected != NULL)
        {
            $array_exist = str_replace('[-', '"', $selected);
            $array_exist = str_replace('-]', '"', $array_exist);
            $array_exist = '['.str_replace('-', '","', $array_exist).']';
            $array_exist = json_decode($array_exist,true);

        }
        
        $multiple = $multi == NULL ? NULL : 'multiple=""';
        $output = '<div class="space-2"></div>
        <div class="form-group">
        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">'.$input_desc.':</label>

        <div class="col-xs-12 col-sm-9">
        <div class="clearfix">
        <select '.$multiple.' id="'.$input_id.'" name="'.$input_id.'" class="chosen-select col-xs-12 col-sm-'.$input_class.'" chosen-class="'.$class_chosen.'"  >';

        $option_idex = $indx != NULL ? '<option value="">'.$indx.'</option>' : NULL;

        $output .= $option_idex;
        $where   =  $where == NULL ? NULL: " WHERE ".$where." ";


        global $db;
        $sql = "SELECT $id_table as val, $txt_table as txt FROM $table $where order by $order_by limit 0,1000 ";
        if (!$db->Query($sql)){
         $db->Kill($db->Error());
     }
     if(!$db->RowCount()){
        $output .='<option value=""></option>';

    }else{
      $select = null;
      $options_arr = $db->RecordsSelectArray();

      foreach ($options_arr as $key => $value) {
        if($multiple != NULL && $selected != NULL && is_array($array_exist)){
          $select = in_array($key, $array_exist) ? 'selected' : null;
      }
      if($multiple == NULL && $selected != NULL){
          $select =  $key == $selected ? "selected" : null;
      }

      $output .= '<option  '.$select.'  value="'.$key.'">'.$value.'</option>';
  }               

} 

$output .='</select>';
$output .= $hard_code;
        //If select multipl selected add this bloc to show existing elements
        //<span class="help-block">Example block-level help text here.</span>
           /*if($multiple != NULL && $selected != NULL)
           {
            $output .= '<span class="help-block">Eléments enregitrés : '.$bloc_multipl_show.'.</span>';
        }*/
        $output .='</div>
        </div>
        </div>';
        $this->form_fields .= $output;
        if($js_array != null)
        {
            $this->js_rules($input_id, $js_array);
        }

    } 


    /**
     * Function Select_counter
     * Generate Select field from counter 
     * @param string $[input_id] [id of input]
     * @param integer $[input_class] [class of input]
     * @param string $[input_value] [value of input default NULL]
     * @param integer count [number of option]
     * @return append string into form_fields [Input render]
     */

    public function select_count($input_desc, $input_id, $input_class, $count, $indx = NULL ,$selected = NULL, $no_zero = false ) 
    {
    	$class_chosen = ($input_class * 100) / 12;
      $start_zero = !$no_zero ? 0 : 1 ;
      $output = '<div class="space-2"></div>
      <div class="form-group">
      <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">'.$input_desc.':</label>

      <div class="col-xs-12 col-sm-9">
      <div class="clearfix">
      <select  id="'.$input_id.'" name="'.$input_id.'" class="chosen-select col-xs-12 col-sm-'.$input_class.'" chosen-class="'.$class_chosen.'" tabindex="1" >';

      $idex = $indx != NULL ? '<option value="">'.$indx.'</option>' : NULL;

      $output .= $indx;


      for($i = $start_zero; $i <= $count; $i++ ) {

          if($selected != NULL){  
             $select =  $i == $selected ? "selected":""; 
         }else{
             $select="";
         }
         $output .= '<option '.$select.' value="'.$i.'">'.$i.'</option>';               
     }

     $output .='</select>';
     $output .='</div>
     </div>
     </div>';
     $this->form_fields .= $output;

 }  



    /**
     * Function Select 
     * Costum Select define options
     * @param string $[input_id] [id of input]
     * @param integer $[input_class] [class of input]
     * @param string $[input_value] [value of input default NULL]
     * @param integer count [number of option]
     * @return append html into form_fields [Input render]
     */
    public function select($input_desc, $input_id, $input_class, $options, $indx = NULL ,$selected = NULL, $multi = NULL, $hard_code = null ) 
    {
    	$multiple = $multi == NULL ? NULL : 'multiple=""';
        //Get class for chosen plugin

        $class_chosen = ($input_class * 100) / 12;
        
        
        $output = '<div class="space-2"></div>
        <div class="form-group">
        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">'.$input_desc.':</label>

        <div class="col-xs-12 col-sm-9">
        <div class="clearfix">
        <select '.$multiple.'  id="'.$input_id.'" name="'.$input_id.'" class="chosen-select col-xs-12 col-sm-'.$input_class.' " chosen-class="'.$class_chosen.'" tabindex="1" >';

        $index = $indx != NULL ? '<option value="">'.$indx.'</option>' : NULL;

        $output .= $index;
        if(!$options){
          $output .='<option  value=""></option>';

      }else{
          foreach($options as $value => $text):
              if($selected != NULL){  
                 $select =  $value == $selected ? "selected":""; 
             }else{
                 $select="";
             }
                    $output .='<option '.$select.' value="'.$value.'">'.$text.'</option>'; //close your tags!!
                endforeach;

            }





            $output .='</select>';
            $output .= $hard_code;
            $output .='</div>
            </div>
            </div>';
            $this->form_fields .= $output;

        }

        public function input_editor($input_desc, $input_id, $input_class, $input_value = null, $js_array = null,  $input_height = 100)
        {
          $value = $input_value == null ? 'value =""default"' : 'value = "'.$input_value.'"';
          $input = '<div class="space-2"></div>
          <div class="form-group">
          <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">'.$input_desc.':</label>

          <div class="col-xs-12 col-sm-'.$input_class.'">
          <div class="clearfix">';


              // $input .= '<input type="text" name="'.$input_id.'" id="'.$input_id.'" class="form-control col-xs-12 col-sm-'.$input_class.'" '.$value.' />';
          $input .='<textarea  name="'.$input_id.'" id="'.$input_id.'">'.$input_value.'</textarea>';


          $input .= '</div></div></div>';

          $this->form_fields .= $input;
          $this->input_js_editor .="$('#".$input_id."').summernote({height: $input_height});";


          if($js_array != null)
          {
              $this->js_rules($input_id, $js_array);
          }
      }

      public function input_autocomplete($input_id, $table, $column, $colwher = null, $valwher = null)
      {
          $this->input_js_autocomplete .= "$('#".$input_id."').autocomplete('".$table."#".$column."#".$colwher."#".$valwher."');";


      }

      public function input_tag($input_id, $class = NULL)
      {
          $class = $class == NULL ? 6 : $class;


          $this->input_tag .= "$('#".$input_id."').tag({
            tagClass: 'col-xs-".$class."'
        });";
    }

    public function select_onchange($input_id)
    {


     $this->select_js_onchange .= "$('body').on( 'change', '#".$input_id."', function() {
      load_onselect($(this));
  });";

}

public function draw_datatabe_form($id_table, $verif_value, $columns = array(), $url_data = null, $url_addrow = null, $titr_addrow = null, $add_js_func = null)
{
  if($this->_is_edit != null){
    $verif_value;
}else{
    $ssid = 'f_v'.$this->_id_form;
    session::clear($ssid);
    session::set($ssid,session::generate_sid());
    $verif_value       = md5(session::get($ssid));
}

$button_action = "$('#".$id_table."').on('click', 'tr button', function() {
  var row = $(this).closest('tr')
  append_drop_menu('".$url_data."', t.cell(row, 0).data(), '.btn_action')
});";
$button_add_row = '<a id="addRow" href="#" rel="'.$url_addrow.'" data="&tkn='.$verif_value.'" data_titre="'.$titr_addrow.'" class=" btn btn-white btn-info btn-bold  spaced "><span><i class="fa fa-plus"></i> '.$titr_addrow.'</span></a><input type="hidden" name="tkn_frm" value="'.$verif_value.'">';
$js_table = "var t = $('#".$id_table."').DataTable({";
$js_table .= "bSort: false, bProcessing: true, serverSide: true, ajax_url:'".$url_data."', extra_data:'tkn_frm=".$verif_value."',aoColumns: [";
$table = '<div class="space-2"></div>';
$table .= '<div class="col-xs-12 '.$id_table.'">'.$button_add_row.'<table id="'.$id_table.'" class="display table table-bordered table-condensed table-hover table-striped dataTable no-footer" cellspacing="0">';
$table .= '<thead><tr>';

foreach ($columns as $column => $width) {

    $table .= '<th>'.$column.'</th>';
    $js_table .= '{"sClass": "center","sWidth":"'.$width.'%"},';
}

$js_table .= ']}); '. $button_action;
$js_table .= $add_js_func;
$table .= '</tr></thead></table></div>';
$this->form_fields .= $table;
$this->js_datatable = $js_table;

}

static public function load_select($table, $value, $text, $where = null)
{
  global $db;
  $output = array();
  $where = null ? null : ' WHERE '.$where;
  $sql = "SELECT $value as val, $text as txt FROM $table $where order by $text limit 0,1000 ";
  if ($db->Query($sql) && $db->RowCount()){
      $output = $db->RecordsSelectArray();
      
  }else{
    $output = $db->Error();
}
return $output;

}

static public function input_x($input_id, $description, $class = 5)
{
  $input = '<div class="space-2"></div>
  <div class="form-group">
  <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">'.$description.':</label>
  <div class="col-xs-12 col-sm-'.$class.'">
  <div class="clearfix">';
  $input .= '<input type="text" name="'.$input_id.'" id="'.$input_id.'" class="col-xs-12 col-sm-'.$class.'"/>';
  $input .= '</div></div></div>';
  return $input;
}



static public function date_x($input_id, $description, $class = 5)
{
  $value = null;

  
  $input = '<div class="space-2"></div>
  <div class="form-group">
  <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">'.$description.' entre:</label>
  <div class="col-xs-12 col-sm-3">
  <div class="clearfix input-group">';
  $input .= '<input type="text" name="'.$input_id.'_s" id="'.$input_id.'_s" class="form-control col-xs-12 col-sm-3" '.$value.'  />';       
  $input .= '<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>';
  $input .= '</div></div>';
  $input .= '<label class="control-label col-xs-12 col-sm-1" for="email">Et :</label>';
  $input .= '<div class="col-xs-12 col-sm-3">
  <div class="clearfix input-group">';
  $input .= '<input type="text" name="'.$input_id.'_e" id="'.$input_id.'_e" class="form-control col-xs-12 col-sm-3" '.$value.'  />';
  $input .= '<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>';
      //$input .= '<a class="input-group-addon"><i class="fa fa-search bigger-110"></i></a>';
  $input .= '</div></div></div>';

  return $input;
}



}


?>