<?php

//============================================================+
// File name   : ticket_pdf.php
// Last Update : 26/07/2018
//
// Description : All info Ticket & Action
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
$ticket = new Mtickets;
$ticket->id_tickets = Mreq::tp('id');

if (!MInit::crypt_tp('id', null, 'D') or ! $ticket->get_tickets()) {

    // returne message error red to devis 
    exit('0#<br>Les informations pour cette template sont erronées, contactez l\'administrateur');
}

//Execute Pdf render

if (!$ticket->Get_detail_ticket_pdf()) {
    exit("0#" . $ticket->log);
}
global $db;
$headers = array(
    'Date' => '17[#]C',
    'Description' => '43[#]',
);


$ticket->get_action_by_ticket();
$action_info = $ticket->list_action;
$tableau_head = MySQL::make_table_head($headers);
$tableau_body = $db->GetMTable_pdf($headers);

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    var $Table_head = null;
    var $no_tabl_head = true;
    var $Table_body = null;
    var $info_ticket = array();
    

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
        $titre_doc = '<h1 style="letter-spacing: 2px;color;#495375;font-size: 20pt;">TICKET</h1>';
        $this->writeHTMLCell(0, 0, 140, 10, $titre_doc, 'B', 0, 0, true, 'R', true);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 9);
        $detail_t = '<table cellspacing="3" cellpadding="2" border="0">
		<tr>
		<td style="width:35%; color:#A1A0A0;"><strong>ID Ticket</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec;"> <strong>' . $this->info_ticket['id'] . '</strong></td>
		</tr> 
		<tr>
		<td style="width:35%; color:#A1A0A0;"><strong>Date</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec; "><strong>' . $this->info_ticket['credat'] . '</strong></td>
		</tr>
		</table>';
        $this->writeHTMLCell(0, 0, 140, 23, $detail_t, '', 0, 0, true, 'L', true);
        //Info Client
        $nif = null;
        if ($this->info_ticket['client'] != null) {
            $nif = '<tr>
		<td align="right" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Client</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;">' . $this->info_ticket['client'] . '</td>
		</tr>';
        }
        $serial_number = $this->info_ticket['serial_number'] != null ? $this->info_ticket['serial_number'] : null;
        $date_realis = $this->info_ticket['date_realis'] != null ? $this->info_ticket['date_realis'] : null;
        $date_previs = $this->info_ticket['date_previs'] != null ? $this->info_ticket['date_previs'] : null;
        $typep = $this->info_ticket['typep'] != null ? $this->info_ticket['typep'] : null;
        $categorie_produit = $this->info_ticket['categorie_produit'] != null ? $this->info_ticket['categorie_produit'] : null;
        $prd = $this->info_ticket['prd'] != null ? $this->info_ticket['prd'] : null;
        $technicien = $this->info_ticket['technicien'] != null ? $this->info_ticket['technicien'] : null;
        $detail_ticket = '<table cellspacing="3" cellpadding="2" border="0">
		<tbody>
		<tr style="background-color:#495375; font-size:14; font-weight:bold; color:#fff;">
		<td colspan="3"><strong>Informations ticket</strong></td>
		</tr>
		<tr>
		<td align="left" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Serial Number</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;"><strong>' . $serial_number . '</strong></td>
		</tr>
                <tr>
		<td align="left" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Date réalisation</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;"><strong>' . $date_realis . '</strong></td>
		</tr>
                <tr>
		<td align="left" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">D. prévisionnelle</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;"><strong>' . $date_previs . '</strong></td>
		</tr>
                <tr>
		<td align="left" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Type produit</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;"><strong>' . $typep . '</strong></td>
		</tr>
                <tr>
		<td align="left" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Catégorie produit</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;"><strong>' . $categorie_produit . '</strong></td>
		</tr>
                
                <tr>
		<td align="left" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Produit</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;"><strong>' . $prd . '</strong></td>
		</tr>
                <tr>
		<td align="left" style="width: 30%; color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">Technicien</td>
		<td style="width: 5%; color: #E99222;font-family: sans-serif;font-weight: bold;">:</td>
		<td style="width: 65%; background-color: #eeecec;"><strong>' . $technicien . '</strong></td>
		</tr>
		</tbody>
		</table>';
        //$marge_after_detail_client = 
        $this->writeHTMLCell(100, 0, 99, 40, $detail_ticket, 0, 0, 0, true, 'L', true);
        if ($this->info_ticket['message'] != null) {
            $projet = '<span style="width: 65%;font-family: sans-serif;ont-weight: bold;font-size: 8pt;"><strong>' . $this->info_ticket['message'] . '</span>';
            $height = $this->getLastH();
            $this->SetTopMargin($height + $this->GetY() + 5);
            //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {
            $this->setCellPadding(1);
            //$this->writeHTMLCell(183, '', 15.6, '', $projet, 1, 0, 0, true, 'L', true);
        }
        $this->Ln();
        $this->setCellPadding(0);
        $height = $this->getLastH() + $this->GetY();
        //$this->SetTopMargin(10 + $this->GetY());
        //Info général
        
        /*
        $tableau_head = $this->Table_head;
        if ($this->no_tabl_head) {
            $this->writeHTMLCell('', '', 15, $height, $tableau_head, 0, 0, 0, true, 'L', true);
            $height = $this->getLastH();
            $this->SetTopMargin($height + $this->GetY());
        }*/
    }

    // Page footer
    public function Footer() {

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

$pdf->Table_head = $tableau_head;
$pdf->info_ticket = $ticket->tickets_info;
$pdf->info_action = $ticket->action_info;


// set document information
$pdf->SetCreator(MCfg::get('sys_titre'));
$pdf->SetAuthor(session::get('username'));
$pdf->SetTitle('ticket');
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
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($file_export, 'F');


//============================================================+
// END OF FILE
//============================================================+

