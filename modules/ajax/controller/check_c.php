<?php 
//Function check existing fields on table
function check_exist($table, $colomn, $value, $isedit = "")
{
	global $db;

    $sql_edit = $isedit == null? "": " AND id <> $isedit ";

	$result = $db->QuerySingleValue0("SELECT $table.$colomn FROM $table 
		where $colomn = '$value'  $sql_edit ");
		
	if($result == "0" )
    {
        exit("true");// SELECT * FROM $table where $colomn = '$value'");  //good to register
    }else{
        exit("false");//  SELECT * FROM $table where $colomn = '$value'"); //already registered
    }
}

//Function check good password
function check_Password_complex($pass) 
{

   

    if(strlen($pass) < 8 || !preg_match("#[0-9]+#", $pass) || !preg_match("#[a-zA-Z]+#", $pass))
    {
        exit("false");//
    }else{
        exit("true");//.
    }




}


//Check username avalaible
if(Mreq::tp('f') != null && Mreq::tp('f') != 'pass')
{
    
    
    $table  =  Mreq::tp('t');
    $colomn =  Mreq::tp('c');
    $champ  =  Mreq::tp('f');
    $value  =  trim(Mreq::tp($champ));
   // exit($colomn.' '.$table.' '.$champ.'  '.$value);

    check_exist( $table, $colomn, $value, Mreq::tp('isedit'));
}

if(Mreq::tp('f') == "pass")
{
    check_Password_complex(Mreq::tp('pass'));
}






?>
