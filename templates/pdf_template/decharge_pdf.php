<?php

//============================================================+
// File name   : décharges_pdf.php
// Last Update : 08/10/2017
//
// Description : All info Décharge
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
$paiement = new Mcommission();
$paiement->id_paiement = Mreq::tp('id');

if (!MInit::crypt_tp('id', null, 'D') or ! $paiement->Get_detail_paiement_show()) {
    // returne message error red to devis 
    exit('0#<br>Les informations pour cette template sont erronées, contactez l\'administrateur');
}

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    var $Table_head = null;
    var $no_tabl_head = true;
    var $Table_body = null;
    var $info_paiement = array();
    var $info_ste = array();
    var $qr = false;
    
    //Page header
    public function Header() {
       
        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {
        // Logo
        $image_file = MPATH_IMG . MCfg::get('logo');
        $this->writeHTMLCell(50, 25, '', '', '', 0, 0, 0, true, 'C', true);
        $this->Image($image_file, 22, 6, 30, 23, 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);

        //Get info ste from DB
        $ste_c = new MSte_info();

        $ste = $ste_c->get_ste_info_report_head(1);
        $this->writeHTMLCell(0, 0, '', 30, $ste, '', 0, 0, true, 'L', true);
        $this->SetTextColor(0, 50, 127);
        // Set font
        $this->SetFont('helvetica', 'B', 22);
        //Ste
        // Title
        $titre_doc = '<h1 style="letter-spacing: 2px;color;#495375;font-size: 20pt;">DÉCHARGE</h1>';
        $this->writeHTMLCell(0, 0, 140, 10, $titre_doc, 'B', 0, 0, true, 'R', true);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 9);
        $detail_encaissement = '<table cellspacing="3" cellpadding="2" border="0">
		
		<td style="width:35%; color:#A1A0A0;"><strong>Date</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec; ">' . $this->info_paiement['date_debit'] . '</td>
		</tr>
		</table>';
        $this->writeHTMLCell(0, 0, 140, 23, $detail_encaissement, '', 0, 0, true, 'L', true);
        '
		</tbody>
		</table>';
        
        $signature = 'La Direction';
        $prn;
        if ($this->info_paiement['methode_payement'] == "Espèce")
            $prn = "en";
        else {
            $prn = "par";
        }
        /*
        $ref;
        if ($this->info_paiement['ref_payement'] != NULL AND $this->info_paiement['mode_payement'] != "Espèce" )
            $ref = "référence <b>" . $this->info_paiement['ref_payement'] . "</b>";
        else {
            $ref = "";
        }
         * 
         */

        
        $projet = '<table>
                           <tr><th><h2 style="text-align: center">Décharge</h2><br></th></tr>
                           <tr><td> Je soussigné  <b>' . $this->info_paiement['commerciale'] . '</b>  reconnais avoir reçu de M/Mme  <b>'.$this->info_paiement['user'].'</b> la somme de <b>'
                . $this->info_paiement['debit'] . ' Fcfa </b> ' . $prn . ' <b>' . $this->info_paiement['methode_payement'] . '</b><br> '
                . 'Ce paiement décharge la société <b>Globaltech</b> d\'une somme de <b>'.$this->info_paiement['debit'].' FCFA .</b><br></td></tr>
                                      <tr>
    <td colspan="2" align="right" style="font: underline; width: 550px;  padding-right: 200px;">
        <br><br><br><br><br>
        <strong>' . $signature . '</strong>
    </td>
</tr>';




if($this->info_paiement['etat'] == 0){
	
$projet .= '
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
        $height = $this->getLastH();
        $this->SetTopMargin($height + $this->GetY() + 35);
        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {
        $this->setCellPadding(1);
        $this->writeHTMLCell(183, '', 15.6, '', $projet, 1, 0, 0, true, 'L', true);

        $this->Ln();
        $this->setCellPadding(0);
        $height = $this->getLastH() + $this->GetY();
        //$this->SetTopMargin(10 + $this->GetY());
        //Info général
        $tableau_head = $this->Table_head;
        if ($this->no_tabl_head) {
            $this->writeHTMLCell('', '', 15, $height, $tableau_head, 0, 0, 0, true, 'L', true);
            $height = $this->getLastH();
            $this->SetTopMargin($height + $this->GetY());
        }
    }

    // Page footer
    public function Footer() {
        //if($this->qr == true){
// QRCODE,H : QR-CODE Best error correction
        $qr_content = $this->info_paiement['id'] . "\n" . $this->info_paiement['debit'] . "\n" . $this->info_paiement['date_debit'];
        $style = array(
            'border' => 1,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
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
        $this->writeHTMLCell(0, 0, '', '', $ste, '', 0, 0, true, 'C', true);
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }

    public function writeHTMLTogether($html, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '') {
        $cp = $this->getPage();
        $this->startTransaction();

        $this->writeHTML($html, $ln, $fill, $reseth, $cell, $align);

        if ($this->getPage() > $cp) {
            $this->rollbackTransaction(true); //true is very important
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
$pdf->info_paiement = $paiement->paiements_info;
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

$html = $pdf->Table_body;
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output($file_export, 'F');


//============================================================+
// END OF FILE
//============================================================+

