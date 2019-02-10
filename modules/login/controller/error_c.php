<?php

if(Mreq::tg('noscript') != "0" && Mreq::tg('_tsk') == 'errorjs' ){
	//header('location:./');
}
        $session = new session();
		if(!$session->stop())
		{
			return false;
		}else{
			view::load('ajax','errorjs');
		}


?>