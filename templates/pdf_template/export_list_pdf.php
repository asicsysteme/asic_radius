<?php
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// extend TCPF with custom functions
class MYPDF extends TCPDF {
    var $Table_head  = null;
    var $Table_body  = null;
    var $high_header = null;
    var $tag_filter  = null;
    // Colored table
    //Separated Header Drawing into it's own function for reuse.
    //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {
    public function Header() {
        
       // $this->SetHeaderData(MCfg::get('logo'), 30, $this->title_report, 'Généré à Temara le :'.date('d-m-Y H:i:s').' Par: '.session::get('username'), array(0,64,255), array(0,64,128));
        $image_file = MPATH_IMG.MCfg::get('logo');
        $this->Image($image_file, 10, 5, 25, 20, 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('dejavusans', '', 14, 'BIU', true);
        $this->writeHTMLCell('', '', 40, 10,$this->title_report, 0, 0, 0, true, 'L', true);
        $this->SetFont('dejavusans', '', 10, '', true);
        $this->writeHTMLCell('', '', 40, 18,'Généré le :'.date('d-m-Y H:i:s').' Par: '.session::get('username'), 0, 0, 0, true, 'L', true);
        if($this->tag_filter != null)
        {
            $this->SetFont('dejavusans', '', 8, '', true);
            $this->writeHTMLCell('', '', 40, 24,'Filtre appliqué: '.$this->tag_filter, 0, 0, 0, true, 'L', true);
            $height = $this->getLastH();
            $this->SetTopMargin($height + $this->GetY());
            $height = $this->getLastH() + $this->GetY();

        }else{
            $height = 30;

        }
        
        
        $this->SetFont('dejavusans', '', 7, '', true);
        $tableau_head = $this->Table_head;
        $this->writeHTMLCell('', '', '', $height , $tableau_head, 0, 0, 0, true, 'L', true);
        $height = $this->getLastH();
        $this->high_header = $height;
        $this->SetTopMargin($height + $this->GetY());
        //$this->SetTopMargin($this->GetY());
        
        
        
    }

}
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->Table_head = $tableau_head;
$pdf->title_report = $title_report;
$pdf->tag_filter = $tag_filter;
//$pdf->high_header = 30;
// set document information
$pdf->SetCreator(MCfg::get('sys_titre'));
$pdf->SetAuthor(session::get('username'));
$pdf->SetTitle($title);
$pdf->SetSubject(MCfg::get('client_titre'));
$pdf->SetKeywords(MCfg::get('client_titre'));

// set default header data
//$pdf->SetHeaderData(MCfg::get('logo'), 30, $title, 'Généré le :'.date('d-m-Y H:i:s').' Par: '.session::get('username'), array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));



// set margins
$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 7, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
//write table body just after table head set Y like this.




//Get table body content from exporter scrip
$pdf->Table_body = $tableau_body;

$html = $pdf->Table_body;
//exit($pdf->GetY() .'  '.PDF_MARGIN_TOP);
// Print text using writeHTMLCell()
$pdf->writeHTML($html, true, false, true, false, '');
//$pdf->writeHTMLCell(0, 0, '', 40, $html, 0, 0, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($file_export,'F');

//============================================================+
// END OF FILE
//============================================================+

