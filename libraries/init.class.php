<?php
/**
                          * Class Initiales functions
                          */
                          class MInit 
                          {


                          // Check Browser if MSIE return False
                            static public function check_browser()
                            {
                              if (isset($_SERVER['HTTP_USER_AGENT'])) {
                                $agent = $_SERVER['HTTP_USER_AGENT'];
                              }
                              if (strlen(strstr($agent, 'MSIE')) > 0 ) {
                                return false;
                              }else{
                                return true;
                              }
}

                    // ---------------------------------------------------
                    //  Cryptage chaine
                    // ---------------------------------------------------
                      static function cryptage($chaine, $sens = 0)
                      {
                        $output = false;
                        $encrypt_method = "AES-256-CBC";
                        $secret_key = MCfg::get('secret');
                        $secret_iv = '5312';
                    // hash
                        $key = hash('sha256', $secret_key);
                    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                        $iv = substr(hash('sha256', $secret_iv), 0, 16);
                        if ( $sens == 1 ) {
                          $output = openssl_encrypt($chaine, $encrypt_method, $key, 0, $iv);
                          $output = base64_encode($output);
                        } else {
                          $output = openssl_decrypt(base64_decode($chaine), $encrypt_method, $key, 0, $iv);
                        }
                        return $output;
                      }





                    // ---------------------------------------------------
                    //  Message box Ajax
                    // ---------------------------------------------------
                      static public function msgbox($url){
                    //0 pour erreur 1 pour OK

                        require_once MPATH_MSG.$url.'.php';
                        exit($fullmessage);
                      } 

                    /**
                    * [Copy uploaded file and auto archive]
                    * @param  [type] $temp_file     [temp file name uploaded]
                    * @param  [type] $new_name_file [format new file name]
                    * @param  [type] $folder        [archive folder]
                    * @param  [type] $id_line       [id of line on module table]
                    * @param  [type] $title         [title of file on archive table]
                    * @param  [type] $table         [table of modul]
                    * @param  [type] $column        [column of modul table for save archive id]
                    * @param  [type] $type          [type of file uploaded]
                    * @param  [type] $edit          [if is edit form use this]
                    * @return [booleen]             [true if ok]
                    */
                    static public function save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, $modul, $table, $column, $type, $edit = null)
                    {
                    $old = umask(0); //
                    $return = null; //rturned srting value;
                    $mode = 0777; // Mode systeme file
                    //when create new folder copy file and return true
                    if(!file_exists($folder)  && !@mkdir($folder, $mode, true))
                    {
                      echo '0#</br>Impossible de créer répertoir';
                      exit();

                    }
                    umask($old);
                    if(!file_exists($temp_file))
                    {                      
                      exit('0#</br>Fichier '.$temp_file.' introuvable');
                    }
                    //Determine Type of file (Image, Document)
                    if(@is_array(getimagesize($link))){
                      $type = 'Image';
                    } else {
                      $type = 'Document';
                    }
                    $path_parts = pathinfo($temp_file);
                    $extention = '.'.$path_parts['extension'];
                    $new_file_saved = $folder.SLASH.$new_name_file.$extention; 
                    if(!rename($temp_file, $new_file_saved))
                    {
                      echo '0#</br>Impossible de copie le fichier';
                      exit();
                    }
                    global $db;
                    //Check if exist entry for this item delet it and insert again
                    if($db->QuerySingleValue0("SELECT id FROM archive WHERE doc = '$new_file_saved' AND idm = $id_line") != "0")
                    {
                      $db->Query("DELETE FROM archive WHERE doc = '$new_file_saved' AND idm = $id_line"); 

                    }
                    
                    
                    $values["doc"]     = MySQL::SQLValue($new_file_saved);
                    $values["titr"]    = MySQL::SQLValue($title);
                    $values["idm"]     = MySQL::SQLValue($id_line);
                    $values["modul"]   = MySQL::SQLValue($modul);
                    $values["table"]   = MySQL::SQLValue($table);
                    $values["type"]    = MySQL::SQLValue($type);
                    $values["col"]     = MySQL::SQLValue($column);
                    $values["creusr"]  = MySQL::SQLValue(session::get('userid'));
                    $values["service"] = MySQL::SQLValue(session::get('service'));
                    
                    
                    if(!$result = $db->InsertRow("archive", $values))
                    {

                      $db->Kill($db->Error().' ARCHIVER');
                      return false;

                    }else{

                      if(!$db->UpdateSinglRows($table, $column, $result, $id_line))
                      { 
                        $db->Kill($db->Error());
                        return false;
                      }
                    }
                    return true;
                  }

                  static public function save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
                  {
                    if($arr_link == null)
                    {
                      return true;
                    }
                    
                    global $db;
                    //Delete Existing files from DB Archive
                    if($edit != Null){
                    //$edit must be like (1,2,3)
                      $db->Query("DELETE FROM archive WHERE id IN $edit ");
                    }
                    $old = umask(0); //
                    $return = null; //rturned srting value;
                    $mode = 0777; // Mode systeme file
                    //when create new folder copy file and return true
                    if(!file_exists($folder)  && !@mkdir($folder, $mode, true))
                    {
                      return '0#</br>Impossible de créer répertoire';

                    }
                    umask($old);
                    $log_error = false;
                    $arr_archives  = array();
                    foreach ($arr_link as $index => $link) {
                    //$chaine .= "Valeur : ".$link." => ".$arr_titl[$index] ."<br/>";
                      if($edit == null OR !strpos($arr_titl[$index], $modul.'_'.$id_line))
                      {
                        $title = $arr_titl[$index].' -'.$modul.'_'.$id_line;
                      }else{
                        $title = $arr_titl[$index];
                      }

                      if(!file_exists($link))
                      {              
                        exit('0#</br>Fichier introuvable =>'.$link);
                      }
                    //Determine Type of file (Image, Document)
                      if(@is_array(getimagesize($link))){
                        $type = 'Image';
                      } else {
                        $type = 'Document';
                      }





                      $new_name_file =  md5($link.$title.date('d-m-Y H:i:s'));
                      $path_parts = pathinfo($link);
                      $extention = '.'.$path_parts['extension'];
                      $new_file_saved = $folder.SLASH.$new_name_file.$extention; 
                      if(!rename($link, $new_file_saved))
                      {
                        exit('0#</br>Impossible de copie le fichier');
                      }

                      if($db->QuerySingleValue0("SELECT id FROM archive WHERE doc = '$new_file_saved' AND idm = $id_line") != "0")
                      {
                        $db->Query("DELETE FROM archive WHERE doc = '$new_file_saved' AND idm = $id_line"); 

                      }
                      $values["doc"]     = MySQL::SQLValue($new_file_saved);
                      $values["titr"]    = MySQL::SQLValue($title);
                      $values["idm"]     = MySQL::SQLValue($id_line);
                      $values["modul"]   = MySQL::SQLValue($modul);
                      $values["table"]   = MySQL::SQLValue($table);
                      $values["type"]    = MySQL::SQLValue($type);
                      $values["creusr"]  = MySQL::SQLValue(session::get('userid'));
                      $values["service"] = MySQL::SQLValue(session::get('service'));
                      if(!$result        = $db->InsertRow("archive", $values))
                      {

                        $db->Kill($db->Error().' ARCHIVER');
                        $log_error = true;

                      }else{
                        array_push($arr_archives, $result);
                      }
                    //If all fils as archived update row of item associated with array archives





                    }
                    
                    $data_archive = '('.join(",",array_filter($arr_archives)).')';
                    
                    if(!$db->UpdateSinglRows($table, $column, $data_archive, $id_line))
                    { 
                      $db->Kill($db->Error());
                      return false;
                    }
                    if($log_error == true)
                    {
                      return false; 
                    }else{
                      return true;

                    }
                    
                  }


                    // ---------------------------------------------------
                    //  Copy uploaded file to last destination
                    // ---------------------------------------------------
                  static public function pub_copy_file($old_file, $new_file, $path, $mode = 0777){


                    $old = umask(0); //
                    $return = ''; //rturned srting value;
                    //when create new folder copy file and return true
                    if(!file_exists($path)  && !@mkdir($path, $mode, true))
                    {
                      return '0#</br>Impossible de créer répertoir';

                    }
                    umask($old);
                    if(!file_exists($old_file))
                    {
                      return '0#</br>Fichier introuvable';
                    }
                    //get extention of file 
                    $path_parts = pathinfo($old_file);
                    $extention = '.'.$path_parts['extension'];
                    $new_file_saved = $path.SLASH.$new_file.$extention; 
                    if(!rename($old_file, $new_file_saved))
                    {
                      return '0#</br>Impossible de copie le fichier';
                    }
                    
                    
                    
                    return '1#</br>fichier copié avec success#'.$path.SLASH.$new_file.$extention;
                    
                  }
                    // ---------------------------------------------------
                    //  Resieze Image create thumbail
                    // ---------------------------------------------------   
                  static public function creat_thumbail($img,$x,$y)
                  {

                    $path_parts = pathinfo($img);
                    $path       = $path_parts['dirname'];
                    $image      = new Image($img);
                    $imgname    = md5($img).$x."X".$y;
                    if(!file_exists($path.SLASH.$imgname.'.png')){
                      $image->resize($x,$y,'corp');
                      $image->save($imgname, $path,'png');

                    }
                    
                    return $path.SLASH.$imgname.'.png';
                    
                    
                  } 

                  static public function get_file_archive($id)
                  {
                    global $db;
                    $file = $db->QuerySingleValue0("SELECT doc FROM archive WHERE id = $id ");
                    if( $file != "0" && file_exists($file))
                    {
                      return $file ;
                    }else{
                      return false;
                    }
                  }


                  static public function get_pictures_gallery($array_image, $show = false)

                  {
                    global $db;
                    $bloc_pic = null;
                    $sql_query = "SELECT archive.id, archive.doc, archive.titr FROM archive WHERE id in $array_image ";
                    if(!$result = $db->Query($sql_query ))
                    {
                      return false;             
                    }else{
                      if(!$db->RowCount())
                      {
                        return false;
                      }else{
                        foreach ($db->RecordsArray() as $key => $val) {
                    //check if for show or edit format button del
                          if($show == false)
                          {
                            $bloc_pic .= '<li><a href="#" class="show_pic" rel="'.$val['doc'].'"><img width="150" height="150" alt="150x150" src="'.$val['doc'].'" /><div class="text"><div class="inner"><input name="photo_id[]" value="'.$val['doc'].'" type="hidden"><input  name="photo_titl[]" value="'.$val['titr'].'" type="hidden">'.$val['titr'].'</div></div></a><div class="tools tools-bottom"><a class="del_pic" rel="'.$val['id'].'" href="#"><i class="ace-icon fa fa-times red"></i></a></div></li>'; 
                          }else{
                            $bloc_pic .= '<li><a href="#" class="show_pic" rel="'.$val['doc'].'"><img width="150" height="150" alt="150x150" src="'.$val['doc'].'" /><div class="text"><div class="inner">'.$val['titr'].'</div></div></a></li>';
                          }

                        }
                        if($show == true){
                          $bloc_pic = '<div class="col-xs-12 "><ul class="ace-thumbnails clearfix">'.$bloc_pic.'</ul></div>';
                        }
                        return $bloc_pic;
                      }
                    }
                  }

                  static public function get_pictures_gallery_table($array_image)
                  {
                    global $db;
                    $bloc_pic = null;
                    $sql_query = "SELECT archive.doc, archive.titr FROM archive WHERE id in $array_image ";
                    
                    $bloc_pic .= '<table><tr>';
                    $i=0;
                    
                    if(!$result = $db->Query($sql_query ))
                    {
                      return false;             
                    }else{
                      if(!$db->RowCount())
                      {
                        return false;
                      }else{

                        foreach ($db->RecordsArray() as $key => $val) {
                          $bloc_pic .= '<td><a href="#" class="show_pic" rel="'.$val['doc'].'"><img width="150" height="150" alt="150x150" src="'.$val['doc'].'" /><div class="text"><div class="inner"><input name="photo_id[]" value="'.$val['doc'].'" type="hidden"><input  name="photo_titl[]" value="'.$val['titr'].'" type="hidden">'.$val['titr'].'</td><td>&nbsp;&nbsp;&nbsp;</td>';
                          $i=$i+1;

                          if ($i==6)
                          {
                            $bloc_pic .= '</tr><tr>';
                            $i=0;
                          }


                        } 

                        $bloc_pic .= '</tr></table></div>';

                        return $bloc_pic;
                      }
                    }
                  }

                    // ---------------------------------------------------
                    // Save Document on Archive
                    // ---------------------------------------------------
                  static public function auto_archive($file,$titr, $idm, $table, $col){
                    global $db;
                    //Check if exist entry for this item
                    if($db->QuerySingleValue0("Select id from archive where doc = '$file' and idm = $idm") != "0")
                    {
                      return true;
                    }
                    
                    
                    $values["doc"] = MySQL::SQLValue($file);
                    $values["titr"] = MySQL::SQLValue($titr);
                    $values["idm"] = MySQL::SQLValue($idm);
                    $values["addby"] = MySQL::SQLValue(session::get('userid'));
                    $values["service"] = MySQL::SQLValue(session::get('service'));
                    
                    // Execute the insert
                    if(!$result = $db->InsertRow("archive", $values))
                    {
                      $db->Kill($db->Error().' ARCHIVER');
                      return false;

                    }
                    else
                    {

                      if(!$db->UpdateSinglRows($table, $col, $result, $idm))
                      { 
                        $db->Kill($db->Error());
                        return false;
                      }
                    }
                    return true;
                    
                    
                  }
                    /*
                    *
                    * Alert Message on CORE without tiger
                    *
                    */
                    static public function msg_cor($msg, $err = "", $return = "")
                    {
                      $err = $err == ""?"nok" : "ok";
                      $box = '<script type="text/javascript">'."ajax_loadmessage(\"".$msg."\",\"".$err."\");ajax_loader(\"".$return."\");</script>";
                      return $box;
                    }
                    
                    /**
                    * [big_message Show message in main page]
                    * @param  [string] $msg   [Message]
                    * @param  [string] $color [Color]
                    * @return [Print String]  [Print message box]
                    */
                    static public function big_message($msg, $color)
                    {
                      $message = '<div class="alert alert-block alert-'.$color.'">
                      <button type="button" class="close" data-dismiss="alert">
                      <i class="ace-icon fa fa-times"></i>
                      </button>
                      '.$msg.'
                      </div>';
                      return print($message);
                    }
                    
                    /**
                    * Function Format verif form get an set
                    * using session 
                    * return string true.
                    */
                    static public function form_verif($form_id, $sens = true)
                    {
                      $ssid = 'f_v'.$form_id;
                      if($sens == true)
                      {
                        session::clear($ssid);
                        session::set($ssid,session::generate_sid());
                        $str = session::get($ssid);
                        echo $str;
                        return false;
                      }else{
                        if(Mreq::tp('verif') != null )
                        {
                          if(Mreq::tp('verif')  == session::get($ssid))
                          {
                            return true;
                          }else{
                            exit('3#Token non valid');
                          }
                          return true;

                        }

                      }


                    }
                    
                    
                    static public function Export_xls($header, $file_name, $title = NULL)
                    {
                      global $db; 


                    //Check if recodcount is 0 return Error message
                      if($db->RowCount() == 0)
                      {
                        exit('0#Aucun résultat trouvé');
                      }

                      $file_name   = $file_name.'_' .date('d_m_Y_H_i_s');
                      $file        = $db->GetCSV($header, $file_name.'.csv');
                      $file_export = MPATH_PDF_REPORT.$file_name.'.xls';
                      $count_col   = count($header)-1;
                      $alphabet = range('A', 'Z');
                      $end_rang_header = $alphabet[$count_col];






                      $objReader = PHPExcel_IOFactory::createReader('CSV');

                    // If the files uses a delimiter other than a comma (e.g. a tab), then tell the reader
                      $objReader->setDelimiter(";");
                    // If the files uses an encoding other than UTF-8 or ASCII, then tell the reader
                      $objReader->setInputEncoding('UTF-8');

                      $objPHPExcel = $objReader->load($file);

                      $objPHPExcel->getActiveSheet()
                      ->getStyle('A1:'.$end_rang_header.'1')
                      ->getFill()
                      ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                      ->getStartColor()
                      ->setARGB('#F7EF0A');


                      $objPHPExcel->getActiveSheet()->insertNewRowBefore(1, 2);
                      $objPHPExcel->getActiveSheet()->mergeCells("A1:J1");
                      $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
                      $objPHPExcel->getActiveSheet()->getStyle("A3:Z3")->getFont()->setBold(true);
                      $objPHPExcel->getDefaultStyle()
                      ->getAlignment()
                      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                      $objPHPExcel->getActiveSheet()->setCellValue('A1', $title. ' - Généré le :'.date('d-m-Y H:i:s').' Par: '.session::get('username'));

                      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                      $objPHPExcel->setActiveSheetIndex(0);
                      foreach(range('B',$end_rang_header) as $columnID) {
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
                      }


                      $objWriter->save($file_export);
                      if(file_exists($file_export))
                      {
                        exit("1#CSV#$file_export");
                      }else{
                        exit("2#Erreur Opération !");
                      }

                    }
                    
                    static public function Export_pdf($headers, $file_name, $title = NULL, $tag_filter = NULL)
                    {
                      global $db;

                    //$db->Query("SELECT id, nom, ste, refonape from aemploi LIMIT 401");
                      if($db->RowCount() > 400)
                      {
                        exit("2#Le résultat dépasse 400 lignes, merci d'exporter en format XLS. ");
                      }
                      $file_export = MPATH_PDF_REPORT.$file_name.'_' .date('d_m_Y_H_i_s').'.pdf';

                      $tableau_head = MySQL::make_table_head($headers);
                      $tableau_body = $db->GetMTable_pdf($headers);
                      $title_report = $title;

                      $html = $tableau_body;

                    //Load template 
                      include_once MPATH_THEMES.'pdf_template/export_list_pdf.php';


                      if(file_exists($file_export))
                      {
                        exit("1#PDF#$file_export");
                      }else{
                        exit("2#Erreur Opération !");
                      }

                    }
                    
                    /**
                    * Check if is REG EXPRESSION
                    * return bool
                    */
                    static public function is_regex($string){
                      if(preg_match('/^[a-zA-Z]+[ -]?+[a-zA-Z0-9._é -]+$/', $string))
                      {
                        return true;
                      }else{
                        return false;
                      }
                    }
                    
                    static public function crypt_tp($post, $value = null, $cryptage = 'C')
                    {
                      $output = false;
                      $encrypt_method = "AES-256-CBC";
                      $secret_key = MCfg::get('secret');
                      $secret_iv = '5312';
                      $chaine = $value;
                      $post_value = $value;
                    // hash

                    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                      $iv = substr(hash('sha256', $secret_iv), 0, 16);
                      if ( $cryptage == 'C' ) {
                        $key = hash('sha256', $secret_key);
                        $hash = md5($key);
                        $output = openssl_encrypt($chaine, $encrypt_method, $hash, 0, $iv);
                        $output = base64_encode($output);
                        $post_crypt = MD5($output);
                        $retour = $post.'='.$post_value.'&'.$post.'h='.$hash.'&'.$post.'c='.$post_crypt;
                        return $retour;
                      } else {
                        $chaine = Mreq::tp($post);
                        $key = $hash = Mreq::tp($post.'h');
                        $output = openssl_encrypt($chaine, $encrypt_method, $key, 0, $iv);
                        $output = base64_encode($output);
                        $post_crypt = MD5($output);

                        if($post_crypt != Mreq::tp($post.'c')){
                          /*var_dump($post_crypt);
                          var_dump(Mreq::tp($post.'c'));*/
                          return false;
                        }else{
                          return true;
                        }

                      }
                    }
                    
                    //Function check existing fields on table
                    static public function exist_select($table, $value)
                    {
                      global $db;
                      $result = $db->QuerySingleValue0("SELECT $table.id FROM $table 
                        where id = '$value' ");

                      if($result == "0" )
                      {
                        return false;
                      }else{
                        return true;
                      }
                    }
                    
                    static public function check_date($date, $temps = null, $date_avant = null, $date_apres = null)
                    {
                      $formated_date = date('d-m-Y',strtotime($date));
                      if($temps != null && $temps == 'P'){
                        if(strtotime($date) > strtotime(date('d-m-Y')))
                        {
                    //exit('Date doit être dans le passé '.$formated_date.' > '.date('d-m-Y'));
                          return false;
                        }
                      }elseif($temps != null && $temps == 'F'){
                        if(strtotime($date) < strtotime(date('d-m-Y')))
                        {
                    //exit('Date doit être dans le Future '.$formated_date.' > '.date('d-m-Y'));
                          return false;
                        }

                      }elseif($date_avant != null){
                        if(strtotime($date) > strtotime($date_avant))
                        {
                    //exit('Date doit être avant la date '.$date_avant.'  ex: '. $formated_date.' avant '.$date_avant);
                          return false;
                        }

                      }elseif($date_apres != null){
                        if(strtotime($date) <= strtotime($date_apres))
                        {
                    //exit('Date doit être après la date '.$date_apres.'  ex: '. $formated_date.' aprés '.$date_apres);
                          return false;
                        }

                      }

                    }
                    
                    
                    public static function deleteDir($dir) {
                      if (is_dir($dir) AND is_readable($dir)) { 
                        $objects = scandir($dir); 
                        foreach ($objects as $object) { 
                          if ($object != "." && $object != "..") { 
                            if (is_dir($dir."/".$object))
                              self::deleteDir($dir."/".$object);
                            else
                              unlink($dir."/".$object); 
                          } 
                        }
                        rmdir($dir); 
                      }
                    }
                    
                    public static function send_big_param($param)
                    {

                      $temp_folder = MPATH_TEMP.session::get('ssid');
                      $hash = md5(uniqid(rand(), true));
                      $file = $temp_folder.SLASH.$hash.'.data';
                      if(!file_put_contents($file, $param, FILE_APPEND | LOCK_EX))
                      {
                        exit("2#Erreur Opération !");
                      }else{
                        exit("1#$hash");
                      }
                    }
                    
                 

                  public static function formatBytes($size, $precision = 0)
                  {
                    if(empty($size)){
                      return '0 Mb';
                    }
                    $base = log($size * 8, 1024);
                    $suffixes = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z');   
                    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
                  }

 }

                  ?>