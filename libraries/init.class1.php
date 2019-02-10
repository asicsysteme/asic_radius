<?php
/**
* Class Initiales functions
*/
class MInit_new{

	/**
	 * [check_browser Check Browser supported]
	 * @return [Bool] [description]
	 */
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
	static public function cryptage($chaine, $sens)
	{
//$sens cryptage=1  décryptage=0  
		$key = MCfg::get('secret');
        $string = $chaine; // note the spaces
        if($string!=''){
   	        if ($sens==1){
   		        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
   		        return $encrypted;
   	        }else{
   		        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
   		        return $decrypted;
   	        }
        }
        return false;
    }

}