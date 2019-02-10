<?php 
if(MInit::form_verif('addrules', false))
{
	//Check if id is been the correct id compared with idc
    if(!MInit::crypt_tp('id', null, 'D') )
    {  
    // returne message error red to client 
        exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
	$add_rule = new Musers;
	$add_rule->id_user = MReq::tp('userid');
	$add_rule->get_user();
	$service = $add_rule->user_info['service'];
	$add_rule->clear_user_rules(); //Clear all rule for this user

	 
	for ($i = 0 , $c  = count(MReq::tp('action_id'))  ; $i < $c ; $i++  ) 
	{

		$the_i = MReq::tp('action_id')[$i];
		$posted_data = array(
			'action_id' => MReq::tp('action_id')[$i],
			'app_name'  => MReq::tp('app_name'.$the_i),
			'app_id'    => MReq::tp('app_id'.$the_i),
			'idf'       => MReq::tp('idf'.$the_i),
			'type'      => MReq::tp('type'.$the_i),
			'userid'    => MReq::tp('userid'),
			'service'   => $service,

	    );
	    $add_rule->app_action = $posted_data;
	    $add_rule->add_user_rules();
		//var_dump($add_rule->_data);
			
	}

	//execute Insert returne false if error
  if($add_rule->error = true){

    echo("1#Enregistrement réussie");//if we leave Muser->log show line for each rule
  }else{

    echo("0#".$add_rule->log);
  }
	
}else{
	//Check if id is been the correct id compared with idc
    if(!MInit::crypt_tp('id', null, 'D') )
    {  
    // returne message error red to client 
        exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
	view::load('users','rules');
}
    




?>