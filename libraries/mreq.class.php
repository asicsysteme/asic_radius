<?php
/**
* Gestion des request $_GET & $_POST
*/

class MReq 
{
	protected $request;
	

	
//Traitement des GET

	static public function tg($request){

		if(isset($_REQUEST[$request]) && !empty($_REQUEST[$request])){
		
			return self::hadi($_REQUEST[$request]);

			
		
		}else{
		return '0';
		}
	}
//Traitement des POST
   	static public function tp($request, $cryptage = null){

		if(isset($_REQUEST[$request]) && !empty($_REQUEST[$request]))
		{
			if($cryptage != null)
			{
				return MInit::cryptage($_REQUEST[$request], 0);
			}



		    

		    return $_REQUEST[$request];


		}else{
			if(isset($_REQUEST[$request]) && $_REQUEST[$request] == 0)
			{
				return $_REQUEST[$request];
			}
		    return NULL;
		}
	}
	

	static private function hadi($get){
		$get = preg_replace('/([^a-z0-9_]+)/i','', $get);
        return $get;
	}
	private function tv($var){
		if(isset($var) && !empty($var))
		   {

			$goodvar=$var;

			}else {
				$goodvar=0;
			}

	if($goodvar = addslashes($goodvar))
		{
			return hadi($goodvar);
	    }
	}
	private function tvs($var)
    {
	if(isset($var) && !empty($var))
		{
			$goodvar=$var;}else {$goodvar=0;
		}
	if($goodvar = stripslashes($goodvar))
	    {
		return $goodvar;
	    }
	
		}

}



?>