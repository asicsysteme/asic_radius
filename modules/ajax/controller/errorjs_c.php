<?php

if(Mreq::tg('_tsk') == 'errorjs' && Mreq::tg('noscript') == 0){
	//exit(Mreq::tg('noscript').' 1');

	header('location:./');
}else{

	//exit(Mreq::tg('noscript').' 2');
	$session = new session();
		if(!$session->stop())
		{
			return false;
		}else{
			view::load('ajax','errorjs');
		}
}
        

        


?>