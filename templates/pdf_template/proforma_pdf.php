<?php
//============================================================+
// File name   : proforma_pdf.php
// Last Update : 08/10/2017
//
// Description : All info proforma
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+
//Get all info proforma from model
$proforma = new Mproforma();
$proforma->id_proforma = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$proforma->get_proforma())
{  
   // returne message error red to proforma 
   exit('0#<br>Les informations pour cette template sont erronées, contactez l\'administrateur');
}



//Execute Pdf render

if(!$proforma->Get_detail_proforma_pdf())
{
	exit("0#".$proforma->log);

}
global $db;
$tableau_body = null;
$headers = array(
            '#'           => '5[#]C',
            'Réf'         => '17[#]C',
            'Description' => '43[#]', 
            'Qte'         => '5[#]C', 
            'P.Unitaire'  => '10[#]R',
            'P.Total'       => '15[#]R',

        );

$proforma_info   = $proforma->proforma_info;
$liste_sub_group = $proforma->get_detail_prforma_by_group();
$tableau_head = MySQL::make_table_head($headers);
if($liste_sub_group){
    $tableau_body = null;
    foreach ($liste_sub_group as $key => $value) 
    {   
    	$id_sub_group = $value['sub_group'];
    	$tableau_body .= '<h3>Proposition N°: '.$id_sub_group.' </h3>';
    	
    	$proforma->Get_detail_proforma_pdf($id_sub_group);
    	
    	$tableau_body .= $tableau_head;
    	$tableau_body .= $db->GetMTable_pdf($headers);
    	$liste_sum = $proforma->get_sum_by_sub_group($id_sub_group);
    	$table_sum_sub_group = '
    	<table style="width: 685px;" cellpadding="2">
    <tr>
        <td width="50%" align="left">
            
        </td>
        <td width="50%">
    	<table class="table" cellspacing="2" cellpadding="2"  style="width: 300px; border:1pt solid black;" >
            <tbody>                
                <tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Total HT</td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;">'.$liste_sum[0]['sum_tt_ht'].' '.$proforma->g('devise').'</td>
                </tr> 
                <tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Total TVA</td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;">'.$liste_sum[0]['sum_tt_tva'].' '.$proforma->g('devise').'</td>
                </tr>   
                <tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Total TTC</td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;">'.$liste_sum[0]['sum_tt_ttc'].' '.$proforma->g('devise').'</td>
                </tr>              
            </tbody>
        </table>
        </td></tr></table> ';
    	$tableau_body .= $table_sum_sub_group;

    }
}

//$tableau_body = $db->GetMTable_pdf($headers);




// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
     var $Table_head = null;
     var $Table_body = null;
     var $info_proforma = array();
     var $info_ste   = array();
     var $qr         = false;
     
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
		$titre_doc = '<h1 style="letter-spacing: 2px;color;#495375;font-size: 20pt;">PROFORMA</h1>';
		$this->writeHTMLCell(0, 0, 140, 10, $titre_doc , 'B', 0, 0, true, 'R', true);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('helvetica', '', 9);
		$detail_proforma = '<table cellspacing="3" cellpadding="2" border="0">
		<tr>
		<td style="width:45%; color:#A1A0A0;"><strong>Réf proforma</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:50%; background-color: #eeecec;">'.$this->info_proforma['reference'].'</td>
		</tr> 
		<tr>
		<td style="width:45%; color:#A1A0A0;"><strong>Date</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:50%; background-color: #eeecec; ">'.$this->info_proforma['date_proforma'].'</td>
		</tr>
		</table>';
		$this->writeHTMLCell(70, 0, 129, 23, $detail_proforma, '', 0, 0, true, 'L', true);
	    //Info Client
	    $nif = null;
	    if($this->info_proforma['nif'] != null)
	    {
	    	$nif = '<tr>
		<td align="right" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">NIF</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;">'.$this->info_proforma['nif'].'</td>
		</tr>';
	    }
	    $ref_client = $this->info_proforma['reference_client'] != null ? $this->info_proforma['reference_client'] : null;
		$tel = $this->info_proforma['tel'] != null ? 'Tél.'.$this->info_proforma['tel'] : null;
	    $email = $this->info_proforma['email'] != null ? 'Email.'.$this->info_proforma['email'] : null;
	    $adresse = $this->info_proforma['adresse'] != null ? $this->info_proforma['adresse'] : null;
	    $bp = $this->info_proforma['bp'] != null ? $this->info_proforma['bp'] : null;
	    $ville = $this->info_proforma['ville'] != null ? $this->info_proforma['ville'] : null;
	    $pays = $this->info_proforma['pays'] != null ? $this->info_proforma['pays'] : null;
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
		<td style="width: 65%; background-color: #eeecec;"><strong>'.$this->info_proforma['denomination'].'</strong></td>
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
		$this->writeHTMLCell(100, 0, 99, 40, $detail_client, 0, 0, 0, true, 'L', true);
		
		$this->Ln();
		$this->setCellPadding(0);
		$height = $this->getLastH() + $this->GetY();
		//Info général
		$tableau_head = $this->Table_head;
		$this->writeHTMLCell('', '', 15, 83, $tableau_head, 0, 0, 0, true, 'L', true);
		$height = $this->getLastH();
       
        $this->SetTopMargin($height + $this->GetY());
		//$pdf->writeHTMLCell('', '','' , '', $html , 0, 0, 0, true, 'L', true);

	}

	// Page footer
	public function Footer() {
		$ste_c = new MSte_info();
		//if($this->qr == true){
// QRCODE,H : QR-CODE Best error correction
			$qr_content = $this->info_proforma['reference']."\n".$this->info_proforma['denomination']."\n".$this->info_proforma['date_proforma'];
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
			$this->write2DBarcode($qr_content, 'QRCODE,H', 15, '', 25, 25, $style, 'N');
		//}
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

//$pdf->Table_head = $tableau_head;
$pdf->info_proforma = $proforma->proforma_info;
$pdf->qr = isset($qr_code) ? $qr_code : false;


// set document information
$pdf->SetCreator(MCfg::get('sys_titre'));
$pdf->SetAuthor(session::get('username'));
$pdf->SetTitle('proforma');
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
$pdf->SetAutoPageBreak(TRUE, 60);


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

//$signature = $pdf->info_proforma['comercial']; 

$signature = 'La Direction';
$block_sum = '<div></div>
<table style="width: 685px;" cellpadding="2">

<tr>
    <td colspan="2" style="color: #E99222;font-family: sans-serif;font-weight: bold;">
        
        <strong>Conditions générales:</strong>
        
    </td>
</tr>
<tr>
    <td colspan="2" style="color:#6B6868; width: 650px; border:1pt solid black; background-color: #eeecec;">
        '.$pdf->info_proforma['claus_comercial'].'
     <br>
     Merci de nous avoir consulter.
 </td>
</tr>

<tr>
    <td colspan="2" align="right" style="font: underline; width: 550px; padding-right: 200px;">
        <br><br><br><br><br>
        <strong>'.$signature.'</strong>
    </td>
</tr>';

//$block_sum .= '</table>';
$p = new Mproforma();
$p->id_proforma = Mreq::tp('id');
$p->get_proforma();

if($p->proforma_info['etat'] == 0){
	//var_dump('ohhh 0');
$block_sum .= '</table>';

}else{
	//var_dump(' 0');	
$block_sum .= '
<tr>
<td colspan="2" align="right" style="font: underline; width: 620px;  padding-right: 200px;">
        <br>
        <span class="profile-picture">
			<img width="150" height="150" class="editable img-responsive" alt="logo_global.png" id="avatar2" src="./upload/signature/signature_ali.jpg" />
		</span>	

    </td>
</tr>
</table>';
}


$pdf->writeHTML($html, true, false, true, false, '');
$pdf->writeHTMLTogether($block_sum, $ln=true, $fill=false, $reseth=false, $cell=false, $align='');
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($file_export,'F');


//============================================================+
// END OF FILE
//============================================================+

