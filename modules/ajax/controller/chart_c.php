<?php
if(!MInit::crypt_tp('chart', null, 'D'))
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour ce graph sont erronées, contactez l\'administrateur');
}
if(MInit::crypt_tp('filtr', null, 'D'))
{
    exit('affiche filter ici');
}
//Format file link
$file_tplt = MPATH_THEMES.'chart_template/'.Mreq::tp('chart').'_chart.php';
if(!file_exists($file_tplt)){
    exit('0#<br>Le Graph n\'existe pas, contactez l\'administrateur');
}

//Evry thing ok load template
include_once $file_tplt;
