<?php
//============================================================+
// File name   : bl_pdf.php
// Last Update : 02/04/2018
//
// Description : All info BL
//
// Author: Rachid Kada
//
// (c) Copyright:
//               Rachid Kada
//               
//============================================================+
//Get all info BL from model
$bl = new Mbl();
$bl->id_bl = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$bl->get_bl())
{  
   // returne message error red to bl 
   exit('0#<br>Les informations pour cette template sont erronées, contactez l\'administrateur');
}



//Execute Pdf render

if(!$bl->Get_detail_bl_pdf())
{
	exit("0#".$bl->log);

}
global $db;
$headers = array(
            '#'           => '5[#]C',
            'Réf'         => '15[#]C',
            'Description' => '55[#]', 
            'Quantité'    => '20[#]C', 
            
            

        );
$bl_info   = $bl->bl_info;
$tableau_head = MySQL::make_table_head($headers);
$tableau_body = $db->GetMTable_pdf($headers);




// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
     var $Table_head   = null;
     var $no_tabl_head = true;
     var $Table_body   = null;
     var $info_bl      = array();
     var $info_ste     = array();
     var $qr           = false;
     
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
		$titre_doc = '<h1 style="letter-spacing: 2px;color;#495375;font-size: 20pt;">Bon de Livraison</h1>';
		$this->writeHTMLCell(0, 0, 128, 10, $titre_doc , 'B', 0, 0, true, 'R', true);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('helvetica', '', 9);
		$detail_bl = '<table cellspacing="3" cellpadding="2" border="0">
		<tr>
		<td style="width:35%; color:#A1A0A0;"><strong>Réf BL</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec;">'.$this->info_bl['reference'].'</td>
		</tr> 
		<tr>
		<td style="width:35%; color:#A1A0A0;"><strong>Date</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec; ">'.$this->info_bl['date_bl'].'</td>
		</tr>
		</table>';
		$this->writeHTMLCell(0, 0, 140, 23, $detail_bl, '', 0, 0, true, 'L', true);
	    //Info Client
	    $nif = null;
	    if($this->info_bl['nif'] != null)
	    {
	    	$nif = '<tr>
		<td align="right" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">NIF</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;">'.$this->info_bl['nif'].'</td>
		</tr>';
	    }
	    $ref_client = $this->info_bl['reference_client'] != null ? $this->info_bl['reference_client'] : null;
	    $tel = $this->info_bl['tel'] != null ? 'Tél.'.$this->info_bl['tel'] : null;
	    $email = $this->info_bl['email'] != null ? 'Email.'.$this->info_bl['email'] : null;
	    $adresse = $this->info_bl['adresse'] != null ? $this->info_bl['adresse'] : null;
	    $bp = $this->info_bl['bp'] != null ? 'BP. '.$this->info_bl['bp'] : null;
	    $ville = $this->info_bl['ville'] != null ? $this->info_bl['ville'] : null;
	    $pays = $this->info_bl['pays'] != null ? $this->info_bl['pays'] : null;
		$detail_client = '<table cellspacing="3" cellpadding="2" border="0">
		<tbody>
		<tr style="background-color:#495375; font-size:14; font-weight:bold; color:#fff;">
		<td colspan="3"><strong>Informations client</strong></td>
		</tr>
		<tr>
		<td align="right" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Réf Client</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;"><strong>'.$ref_client.'</strong></td>
		</tr>
		<tr>
		<td align="right" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Dénomination</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;"><strong>'.$this->info_bl['denomination'].'</strong></td>
		</tr>';

		if($adresse.$bp.$ville.$pays != null){
			$detail_client .= '<tr>
	    <td align="right" style="width: 30%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Adresse</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;">'.$adresse.' '.$bp.' '.$ville.' '.$pays.'</td>
		</tr>';

		}
			
		
		
		if($tel != null && $email != null){
			$detail_client .= '<tr>
		<td align="right" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Contact</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;">'.$tel.' '.$email.'</td>
		</tr>
		';
		}
		$detail_client .= $nif.'
		</tbody>
		</table>';
		//$marge_after_detail_client = 
		$this->writeHTMLCell(100, 0, 99, 40, $detail_client, 0, 0, 0, true, 'L', true);
		if($this->info_bl['projet'] != null){
			$projet = '<span style="width: 65%;font-family: sans-serif;ont-weight: bold;font-size: 10pt;"><strong>'.$this->info_bl['projet'].'</span>';
		    $height = $this->getLastH();
		    $this->SetTopMargin($height + $this->GetY() + 5);
		    //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {
		    $this->setCellPadding(1);
		    $this->writeHTMLCell(183, '', 15.6, '', $projet, 1, 0, 0, true, 'L', true);
		}
		$this->Ln();
		$this->setCellPadding(0);
		$height = $this->getLastH() + $this->GetY();
		//$this->SetTopMargin(10 + $this->GetY());
		//Info général
		$tableau_head = $this->Table_head;
		if($this->no_tabl_head){
			$this->writeHTMLCell('', '', 15, $height, $tableau_head, 0, 0, 0, true, 'L', true);
		    $height = $this->getLastH();
            $this->SetTopMargin($height + $this->GetY());
		}
		
	}

	// Page footer
	public function Footer() {
		//if($this->qr == true){
// QRCODE,H : QR-CODE Best error correction
			$qr_content = $this->info_bl['reference']."\n".$this->info_bl['denomination']."\n".$this->info_bl['date_bl'];
			$style = array(
				'border' => 1,
				'vpadding' => 'auto',
				'hpadding' => 'auto',
				'fgcolor' => array(0,0,0),
	            'bgcolor' => false, //array(255,255,255)
	            'module_width' => 1, // width of a single module in points
	            'module_height' => 1 // height of a single module in points
           );
	//write2DBarcode($code, $type, $x='', $y='', $w='', $h='', $style='', $align='', $distort=false)
	        $this->SetY(-30);
			//$this->write2DBarcode($qr_content, 'QRCODE,H', 15, '', 25, 25, $style, 'N');
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

$pdf->Table_head = $tableau_head;
$pdf->info_bl = $bl->bl_info;
$pdf->qr = isset($qr_code) ? $qr_code : false;


// set document information
$pdf->SetCreator(MCfg::get('sys_titre'));
$pdf->SetAuthor(session::get('username'));
$pdf->SetTitle('bl');
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
$pdf->Table_body = $tableau_body;
$html = $pdf->Table_body;
// ---------------------------------------------------------

//$pdf->writeHTMLCell('', '','' , '', $html , 0, 0, 0, true, 'L', true);


//$signature = $pdf->info_proforma['comercial']; 

$signature = 'Le Fournisseur'; 


$block_sum = '<div></div>
<style>
p {
    line-height: 0.6;
    .row0
				{
					background-color: #eaebed;
					border:1pt solid black;
				}
				.row1{
					border:1px solid black;
				}
				.alignRight { text-align: right; }
				.center{ text-align: center; }
}

</style>
<table style="width: 685px;" cellpadding="2">
 <tr>
    <td colspan="2" style="color:#6B6868; width: 650px; border:1pt solid black; padding: 5px;">
        Remarques:
        <br><br><br><br><br>
    </td>
</tr>

<tr>
    <td  style="font: underline; width: 550px; padding-right: 200px;">
        <br><br><br><br><br>
        <strong>Le Client</strong>
    </td>
    <td  style="font: underline; width: 550px; padding-right: 200px;">
        <br><br><br><br><br>
        <strong>'.$signature.'</strong>
    </td>
</tr>
</table>';
//$pdf->lastPage(); 
//$block_sum .= '</table>';



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
$pdf->writeHTMLTogether($block_sum, $ln=true, $fill=false, $reseth=false, $cell=false, $align='');
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

