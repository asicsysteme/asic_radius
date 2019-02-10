
<?php
defined('_MEXEC') or die;
if(MInit::form_verif('edit_info_ste',false))
	{
		/*if (!MInit::crypt_tp('id', null, 'D')) {
        //returne message error red to client 
			exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
		}*/

		$posted_data = array(

			'ste_id'      => Mreq::tp('id') ,
			'ste_name'    => Mreq::tp('ste_name') ,
			'ste_bp'      => Mreq::tp('ste_bp') ,
			'ste_adresse' => Mreq::tp('ste_adresse') ,
			'ste_tel'     => Mreq::tp('ste_tel') ,
			'ste_fax'     => Mreq::tp('ste_fax') ,
			'ste_email'   => Mreq::tp('ste_email') ,
			'ste_if'      => Mreq::tp('ste_if') ,
			'ste_rc'      => Mreq::tp('ste_rc') ,
			'ste_website' => Mreq::tp('ste_website') ,

		);


  //Check if array have empty element return list
  //for acceptable empty field do not put here



		$checker = null;
		$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
		
		if($posted_data['ste_name'] == NULL){

			$empty_list .= "<li>Nom de la Société</li>";
			$checker = 1;
		}
		if($posted_data['ste_bp'] == NULL){

			$empty_list .= "<li>BP</li>";
			$checker = 1;
		}
		if($posted_data['ste_adresse'] == NULL){

			$empty_list .= "<li>Adresse</li>";
			$checker = 1;
		}
		if($posted_data['ste_tel'] == NULL){

			$empty_list .= "<li>Téléphone</li>";
			$checker = 1;
		}
		if($posted_data['ste_email'] == NULL){

			$empty_list .= "<li>Email</li>";
			$checker = 1;
		}
		if($posted_data['ste_if'] == NULL){

			$empty_list .= "<li>Identifiant fiscal</li>";
			$checker = 1;
		}
		if($posted_data['ste_rc'] == NULL){

			$empty_list .= "<li>Numéro Registre de commerce</li>";
			$checker = 1;
		}

		$empty_list.= "</ul>";
		if($checker == 1)
		{
			exit("0#$empty_list");
		}



  //End check empty element

		$ste_info = new  MSte_info($posted_data);
        $ste_info->id_ste = $posted_data['ste_id']; 


  //execute Insert returne false if error
		if($ste_info->edit_info_ste()){

			exit("1#".$ste_info->log);
		}else{

			exit("0#".$ste_info->log);
		}


	} else {
		view::load_view('info_ste');
	}





	?>