<?php

$notifier = new MNotifier();
if(MReq::tp('ul') == 1){
	echo ($notifier->notif_list());
}

?>






