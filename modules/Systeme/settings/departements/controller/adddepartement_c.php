
<?php
defined('_MEXEC') or die;
if(MInit::form_verif('adddepartement',false))
{
	
  $posted_data = array(
   'departement'          => Mreq::tp('departement') ,
   'id_region'            => Mreq::tp('id_region') ,

   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
     if(!MInit::is_regex($posted_data['departement'])){

      $empty_list .= "<li>Département non valide (a-z 1-9)</li>";
      $checker = 1;
    }

    if($posted_data['departement'] == NULL){

      $empty_list .= "<li>Département</li>";
      $checker = 1;
    }

    if($posted_data['id_region'] == NULL){

      $empty_list .= "<li>Région</li>";
      $checker = 1;
    }
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    
  
  //End check empty element

 $new_departement = new  Mdept($posted_data);
  
  

  //execute Insert returne false if error
  if($new_departement->save_new_departement()){

    echo("1#".$new_departement->log);
  }else{

    echo("0#".$new_departement->log);
  }


} else {
  view::load('Systeme/settings/departements','adddepartement');
}





?>