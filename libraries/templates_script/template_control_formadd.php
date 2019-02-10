if(MInit::form_verif('%task%', false))
{

	$posted_data = array(

		%lines_action%

	);


    //Check if array have empty element return list
    //for acceptable empty field do not put here
	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";

		%lines_action_check%


	$empty_list.= "</ul>";
	if($checker == 1)
	{
		exit("0#$empty_list");
	}



      //End check empty element
	$new_%modul% = new  M%modul%($posted_data);



       //execute Insert returne false if error
	if($new_%modul%->save_new_%modul%())
	{
		exit("1#".$new_%modul%->log);
	}else{
		exit("0#".$new_%modul%->log);
	}


}

//No form posted show view
view::load_view('%task%');







	?>