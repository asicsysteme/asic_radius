<?php
//============================================================+
// File name   : facture_vd_pdf.php
// Last Update : 29/01/2019
//
// Description : All info Facture Vente Directe
//
// Author: ASIC
//
// (c) Copyright:
//               ASIC
//============================================================+
//Get all info Facture from model
$facture = new Mvnt_factures();
$facture->id_vnt_factures = Mreq::tp('id');
$id_facture = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_facture = $facture->get_facture_vd_for_show($id_facture))
{  
   // returne message error red to facture 
   exit('0#<br>Les informations pour cette template sont erronées, contactez l\'administrateur '.$facture->log);
}

//Execute Pdf render

global $db;

if(!$facture->Get_detail_vnt_facture_pdf())
{
	exit("0#".$facture->log);

}

$headers = array(
	'#'           => '5[#]C',
	'Réf'         => '17[#]C',
	'Description' => '43[#]', 
	'Qte'         => '5[#]C', 
	'P.Unitaire'  => '10[#]R',
	'P.Total'     => '15[#]R',	
);

$tableau_head_product = MySQL::make_table_head($headers);
$tableau_body_product = $db->GetMTable_pdf($headers);


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    var $Table_head = null;
    var $Table_body = null;
    var $info_facture = array();
    var $facture_details_info = array();
    var $qr = false;
	//Page header
	public function Header() {
		//writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {
		
		// Logo
		$image_file = MPATH_IMG.MCfg::get('logo');
		$this->writeHTMLCell(50, 25, '', '', '' , 0, 0, 0, true, 'C', true);
		$this->Image($image_file, 22, 6, 30, 23, 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
		
		//Get info ste from DB
		$ste_c = new MSte_info();
        
		$ste = $ste_c->get_ste_info_report_head(1);
		$this->writeHTMLCell(0, 0, '', 30, $ste , '', 0, 0, true, 'L', true);
		$this->SetTextColor(0, 50, 127);
		// Set font
		$this->SetFont('helvetica', 'B', 22);
		//Ste
		
		// Title
		$titre_doc = '<h1 style="letter-spacing: 2px;color;#495375;font-size: 20pt;">FACTURE</h1>';
		$this->writeHTMLCell(0, 0, 140, 10, $titre_doc , 'B', 0, 0, true, 'R', true);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('helvetica', '', 9);
        $per = NULL;
        
        
		$detail_facture = '<table cellspacing="3" cellpadding="2" border="0">
		<tr>
		<td style="width:25%; color:#A1A0A0;"><strong>Réf Facture</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:75%; background-color: #eeecec;">'.$this->info_facture['ref'].'</td>
		</tr> 
		<tr>
		<td style="width:25%; color:#A1A0A0;"><strong>Date</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:75%; background-color: #eeecec; ">'.$this->info_facture['date_facture'].'</td>
		</tr>
		<tr>
		<td style="width:25%; color:#A1A0A0;"><strong>Client</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:75%; background-color: #eeecec; ">'.$this->info_facture['nom_client'].'</td>
		</tr>
		<tr>
		<td style="width:25%; color:#A1A0A0;"><strong>Téléphone</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:75%; background-color: #eeecec; ">'.$this->info_facture['tel_client'].'</td>
		</tr>
        <tr>
        <td style="width:25%; color:#A1A0A0;"><strong>Commande
                </strong></td>
        <td style="width:5%;">:</td>
        <td style="width:75%; background-color: #eeecec; ">' . $this->info_facture['cmd'] . '</td>
        </tr>        
        </table>';
		$this->writeHTMLCell(0, 0, 105, 23, $detail_facture, '', 0, 0, true, 'L', true);
	    				
		//$this->Ln();		
		$this->setCellPadding(0);
		$height = $this->getLastH() + $this->GetY() + 5;
		$this->SetTopMargin($this->GetY());
		//Info général
		$tableau_head = $this->Table_head;
		$this->writeHTMLCell('', '', 15, $height, $tableau_head, 0, 0, 0, true, 'L', true);
		$height = $this->getLastH();
        $this->SetTopMargin($height + $this->GetY());
        //end comment fati
		
	}

	// Page footer
	public function Footer() {
		//if($this->qr == true){
// QRCODE,H : QR-CODE Best error correction
			$qr_content = $this->info_facture['ref']."\n".$this->info_facture['cmd']."\n".$this->info_facture['date_facture'];
			$style = array(
				'border' => 1,
				'vpadding' => 'auto',
				'hpadding' => 'auto',
				'fgcolor' => array(0,0,0),
	            'bgcolor' => false, //array(260,255,255)
	            'module_width' => 1, // width of a single module in points
	            'module_height' => 1 // height of a single module in points
            );
	//write2DBarcode($code, $type, $x='', $y='', $w='', $h='', $style='', $align='', $distort=false)
	        $this->SetY(-30);
			$this->write2DBarcode($qr_content, 'QRCODE,H', 15, '', 25, 25, $style, 'N');
		//}
		$ste_c = new MSte_info();
        $this->SetY(-30);
		$ste = $ste_c->get_ste_info_report_footer(1);
		$this->writeHTMLCell(0, 0, '', '', $ste , '', 0, 0, true, 'C', true);
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
	public function writeHTMLTogether($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='') {
    $cp =  $this->getPage();
    $this->startTransaction();

    $this->writeHTML($html, $ln, $fill, $reseth, $cell, $align);

    if ($this->getPage() > $cp) {
         $this->rollbackTransaction(true);//true is very important
         $this->AddPage();
         $this->writeHTML($html, $ln, $fill, $reseth, $cell, $align);           
    } else {            
         $this->commitTransaction();            
    }
    }

	
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->Table_head = $tableau_head_product;
$pdf->info_facture = $info_facture;
$pdf->qr = isset($qr_code) ? $qr_code : false;


// set document information
$pdf->SetCreator(MCfg::get('sys_titre'));
$pdf->SetAuthor(session::get('username'));
$pdf->SetTitle('devis');
$pdf->SetSubject(MCfg::get('client_titre'));
$pdf->SetKeywords(MCfg::get('client_titre'));


// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 90, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

$pdf->SetFooterMargin(30);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 30);


// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
// set font
$pdf->SetFont('helvetica', '', 9);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
//If is generated to stored the QR is need

// Print text using writeHTMLCell()
$pdf->Table_body = $tableau_body_product;
$html = $pdf->Table_body;


$pdf->writeHTML($html, true, false, true, false, '');
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
//$pdf->writeHTMLTogether($block_sum, $ln=true, $fill=false, $reseth=false, $cell=false, $align='');
/*$block_sum1 = 'Y: '.$y;
$pdf->writeHTML($block_sum1, true, false, true, false, '');
$pdf->writeHTML($block_sum, true, false, true, false, '');
$y = $pdf->GetY();


$block_sum1 = 'Y: '.$y;*/

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($file_export,'F');


//============================================================+
// END OF FILE
//============================================================+

