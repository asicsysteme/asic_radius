<?php
//============================================================+
// File name   : devis_pdf.php
// Last Update : 08/10/2017
//
// Description : All info Devis
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+
//Get all info Devis from model

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
     
     
	
	
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set document information
$pdf->SetCreator(MCfg::get('sys_titre'));
$pdf->SetAuthor('ASIC');
$pdf->SetTitle('invitation');
$pdf->SetSubject('FGC');
$pdf->SetKeywords('FGC');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set default header data

// set header and footer fonts


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(1, 1, 1);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 5);


// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//$pdf->SetProtection(array('print', 'copy','modify'), "ourcodeworld", "ourcodeworld-master", 0, null);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
// set font
$pdf->SetFont('helvetica', '', 9);
$style = array(
				'border' => 1,
				'vpadding' => 1,
				'hpadding' => 1,
				'fgcolor' => array(1,1,1),
	            'bgcolor' => false,//array(255,255,255),
	            'module_width' => 1, // width of a single module in points
	            'module_height' => 1 // height of a single module in points
           );
// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
//If is generated to stored the QR is need

//$pdf->Image('images/billet.jpg', 15, 140, 75, 113, 'JPG', null, '', true, 150, '', false, false, 1, false, false, false);
/*$pdf->Image('images/invitation_v1.jpg', $x='', $y='', $w=208, $h=90, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array());
$y = $pdf->GetY() + 95;
$pdf->Image('images/invitation_v1.jpg', $x='', $y, $w=208, $h=90, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array());
$y = $pdf->GetY() + 190;
$pdf->Image('images/invitation_v1.jpg', $x='', $y, $w=208, $h=90, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array());
$pdf->write2DBarcode($qr_code, 'QRCODE,H', 15, '', 25, 25, $style, 'N');*/
$y = 0;
foreach ($all_invitations as $key => $value) {
	$qr_code = $all_invitations[$key]['cod']."\n".$all_invitations[$key]['id'];
	$pdf->Image('images/invitation_v1.jpg', $x='', $y, $w=208, $h=90, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array());
	$y = ($pdf->GetY() + 95);
	$pdf->SetY($y);
	$pdf->write2DBarcode($qr_code, 'QRCODE,H', $x = 3, $y - 43, 28, 28, $style, 'N');
    $pdf->SetY($y);
}


/*$y = $pdf->GetY();
//No space for Sum Blok then AddPage
if($y > 190)
{
	$pdf->no_tabl_head = false;
	$pdf->AddPage();
	$pdf->writeHTML($block_sum, true, false, true, false, '');

}else{
	$pdf->writeHTML($block_sum, true, false, true, false, '');
}*/

/*$block_sum1 = 'Y: '.$y;
$pdf->writeHTML($block_sum1, true, false, true, false, '');
$pdf->writeHTML($block_sum, true, false, true, false, '');
$y = $pdf->GetY();


$block_sum1 = 'Y: '.$y;*/

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($file_export,'F');
exit('0#'.$file_export);

//============================================================+
// END OF FILE
//============================================================+

