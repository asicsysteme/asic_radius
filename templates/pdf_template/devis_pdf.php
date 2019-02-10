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
$devis = new Mdevis();
$devis->id_devis = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$devis->get_devis())
{  
   // returne message error red to devis 
   exit('0#<br>Les informations pour cette template sont erronées, contactez l\'administrateur');
}



//Execute Pdf render

if(!$devis->Get_detail_devis_pdf())
{
	exit("0#".$devis->log);

}
global $db;
$headers = array(
            '#'           => '5[#]C',
            'Réf'         => '17[#]C',
            'Description' => '43[#]', 
            'Qte'         => '5[#]C', 
            'P.Unitaire'  => '10[#]R',
            'P.Total'       => '15[#]R',

        );
$devis_info   = $devis->devis_info;
$tableau_head = MySQL::make_table_head($headers);
$tableau_body = $db->GetMTable_pdf($headers);




// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
     var $Table_head   = null;
     var $no_tabl_head = true;
     var $Table_body   = null;
     var $info_devis   = array();
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
		$titre_doc = '<h1 style="letter-spacing: 2px;color;#495375;font-size: 20pt;">DEVIS</h1>';
		$this->writeHTMLCell(0, 0, 140, 10, $titre_doc , 'B', 0, 0, true, 'R', true);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('helvetica', '', 9);
		$detail_devis = '<table cellspacing="3" cellpadding="2" border="0">
		<tr>
		<td style="width:35%; color:#A1A0A0;"><strong>Réf Devis</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec;">'.$this->info_devis['reference'].'</td>
		</tr> 
		<tr>
		<td style="width:35%; color:#A1A0A0;"><strong>Date</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec; ">'.$this->info_devis['date_devis'].'</td>
		</tr>
		</table>';
		$this->writeHTMLCell(0, 0, 140, 23, $detail_devis, '', 0, 0, true, 'L', true);
	    //Info Client
	    $nif = null;
	    if($this->info_devis['nif'] != null)
	    {
	    	$nif = '<tr>
		<td align="right" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">NIF</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;">'.$this->info_devis['nif'].'</td>
		</tr>';
	    }
	    $ref_client = $this->info_devis['reference_client'] != null ? $this->info_devis['reference_client'] : null;
	    $tel = $this->info_devis['tel'] != null ? 'Tél.'.$this->info_devis['tel'] : null;
	    $email = $this->info_devis['email'] != null ? 'Email.'.$this->info_devis['email'] : null;
	    $adresse = $this->info_devis['adresse'] != null ? $this->info_devis['adresse'] : null;
	    $bp = $this->info_devis['bp'] != null ? 'BP. '.$this->info_devis['bp'] : null;
	    $ville = $this->info_devis['ville'] != null ? $this->info_devis['ville'] : null;
	    $pays = $this->info_devis['pays'] != null ? $this->info_devis['pays'] : null;
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
		<td style="width: 65%; background-color: #eeecec;"><strong>'.$this->info_devis['denomination'].'</strong></td>
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
		if($this->info_devis['projet'] != null){
			$projet = '<span style="width: 65%;font-family: sans-serif;ont-weight: bold;font-size: 10pt;"><strong>'.$this->info_devis['projet'].'</span>';
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
			$qr_content = $this->info_devis['reference']."\n".$this->info_devis['denomination']."\n".$this->info_devis['date_devis'];
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
$pdf->info_devis = $devis->devis_info;
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

//$pdf->SetProtection(array('print', 'copy','modify'), "ourcodeworld", "ourcodeworld-master", 0, null);

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



$obj = new nuts($pdf->info_devis['totalttc'], $pdf->info_devis['devise']);
$ttc_lettre = $obj->convert("fr-FR");
$total_no_remise = $pdf->info_devis['total_no_remise'];
$block_tt_no_remise = '<tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;"><strong>Total</strong></td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>'.$total_no_remise .'  '.$pdf->info_devis['devise'].'</strong></td>
                </tr>';
$block_remise = '<tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;"><strong>Remise '.$pdf->info_devis['valeur_remise'].' %</strong></td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>'.$pdf->info_devis['total_remise'].'  '.$pdf->info_devis['devise'].'</strong></td>
                </tr>';
$block_ttc = '<tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;"><strong>TVA 18%</strong></td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>'.$pdf->info_devis['totaltva'].'  '.$pdf->info_devis['devise'].'</strong></td>
                </tr>
                <tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;"><strong>Total TTC</strong></td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>'.$pdf->info_devis['totalttc'].' '.$pdf->info_devis['devise'].'</strong></td>
                </tr>';                
$block_remise = $pdf->info_devis['valeur_remise'] == 0 ? null : $block_remise; 
$block_tt_no_remise = $pdf->info_devis['valeur_remise'] == 0 ? null : $block_tt_no_remise;  
$block_ttc    = $pdf->info_devis['totaltva'] == 0 ? null : $block_ttc;
$titl_ht = $pdf->info_devis['totaltva'] == 0 ? 'Total à payer' : 'Total HT';
//$signature = $pdf->info_proforma['comercial']; 

$signature = 'La Direction'; 


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
        <td width="50%" align="left">
            
        </td>
        <td width="50%">
           <table class="table" cellspacing="2" cellpadding="2"  style="width: 300px; border:1pt solid black;" >
            <tbody>
                '.$block_tt_no_remise.$block_remise.'
                <tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;"><strong>'.$titl_ht.'</strong></td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>'.$pdf->info_devis['totalht'].' '.$pdf->info_devis['devise'].'</strong></td>
                </tr>

                '.$block_ttc.'
                
            </tbody>
        </table> 
    </td>
</tr>
<tr>
    <td colspan="2" style="color: #E99222;font-family: sans-serif;font-weight: bold;">
        Arrêté le présent Devis à la somme de :
    </td>
</tr>
<tr>
    <td colspan="2" style="color:#6B6868; width: 650px; border:1pt solid black; background-color: #eeecec; padding: 5px;">
        <strong>'.$ttc_lettre.'</strong>    
    </td>
</tr>
<tr>
    <td colspan="2" style="color: #E99222;font-family: sans-serif;font-weight: bold;">       
        <strong>Conditions générales:</strong>        
    </td>
</tr>

<tr>
    <td colspan="2" style="color:#6B6868; width: 650px; border:1pt solid black; background-color: #eeecec; padding: 5px;">
        '.$pdf->info_devis['claus_comercial'].'
    </td>
</tr>

<tr>
    <td colspan="2" align="right" style="font: underline; width: 550px; padding-right: 200px;">
        <br><br><br><br><br>
        <strong>'.$signature.'</strong>
    </td>
</tr>';
//$pdf->lastPage(); 
//$block_sum .= '</table>';

$d = new Mdevis();
$d->id_devis = Mreq::tp('id');
$d->get_devis();

if($d->devis_info['etat'] == 0){
	//var_dump('ohhh 0');
$block_sum .= '</table>';

}else{
	//var_dump(' 0');	
$block_sum .= '
<tr>
<td colspan="2" align="right" style="font: underline; width: 620px;  padding-right: 200px;">
        <br>
        <span class="profile-picture">
			<img width="170" height="170" class="editable img-responsive" alt="logo_global.png" id="avatar2" src="./upload/signature/signature_ali.jpg" />
		</span>	

    </td>
</tr>
</table>';
}


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

