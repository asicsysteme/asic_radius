<?php
if(!MInit::crypt_tp('tplt', null, 'D'))
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette Template sont erronées, contactez l\'administrateur');
}
//Format file link
$file_tplt = MPATH_THEMES.'pdf_template/'.Mreq::tp('tplt').'_pdf.php';
if(!file_exists($file_tplt)){
    exit('0#<br>La template n\'existe pas, contactez l\'administrateur');
}

//Evry thing ok load template
$file_export  = MPATH_PDF_REPORT.Mreq::tp('tplt').'_' .date('d_m_Y_H_i_s').'.pdf';
include_once $file_tplt;

$return_arr = array('error' => 'false', 'file' => $file_export );

echo json_encode($return_arr);
