<?php
/*if(MInit::form_verif('addtest', false))
{
	
  $posted_data = array(
   'nom'        => Mreq::tp('nom') ,
   'prenom'     => Mreq::tp('prenom') ,
   'photo_id'   => Mreq::tp('photo_id') ,
   'photo_titl' => Mreq::tp('photo_titl') ,
   
   
  );
global $db;
    
    $values["nom"]    = MySQL::SQLValue($posted_data['nom']);
    $values["prenom"] = MySQL::SQLValue($posted_data['prenom']);
    

    if (!$result = $db->InsertRow("test", $values)) {
        
        $log = $db->Error();
        
        

      }else{

        $last_id = $result;
        $folder        = MPATH_UPLOAD.'test'.SLASH.$last_id;
        Minit::save_multipl_file_upload(Mreq::tp('photo_id'), $folder, $last_id, Mreq::tp('photo_titl'), 'test', 'gal', 'image', $edit = null);
        
      }

    

}*/


function after_update($table, $id, $arr_new, $arr_old) {
  $aReturn = array();

  foreach ($arr_new as $mKey => $mValue) {
    if (array_key_exists($mKey, $arr_old)) {
      if (is_array($mValue)) {
        $aRecursiveDiff = arrayRecursiveDiff($mValue, $arr_old[$mKey]);
        if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
      } else {
        if ($mValue != $arr_old[$mKey]) {

            $aReturn[$mKey] = array('table' => $table, 'id_item'=>$id, 'column'=>$mKey, 'val_old'=>$arr_old[$mKey], 'val_new'=>$mValue, 'user'=>session::get('username'));
          }
      }
    }
  }
  //Traitement result
  global $db;
  
  foreach ($aReturn as $key_g => $values) {
    foreach ($values as $key => $value) {
      $arr_insert[$key] = MySQL::SQLValue($value);
    }
    if (!$result = $db->InsertRow("espionnage_update", $arr_insert)) {
        
        exit($db->Error().' '.$db->BuildSQLInsert("espionnage_update", $arr_insert));
      }
  }
  //return $aReturn;
  
}

/*$arr_new  = array('k1' => '1' ,'K2'=> '2');
$arr_old  = array('k1' => '1' ,'K2'=> '5');*/

$arr_new['k1'] = 1;
$arr_new['k2'] = 'test de 2';
$arr_new['k3'] = 'test de 3';
$arr_new['k4'] = 'Kle 4';
$arr_old['k1'] = 1;
$arr_old['k2'] = 3;
$arr_old['k3'] = 'K3not';
$arr_old['k4'] = 'Old k4';

//var_dump($arr_old);
//var_dump($arr_new);
after_update('table', 12, $arr_new, $arr_old);



?>