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

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
     var $Table_head  = null;
     var $Table_body  = null;
     var $info_contrat  = array();
     var $info_devis   = array();
     
	//Page header
	public function Header() {
			
		// Logo
		$image_file = MPATH_IMG.MCfg::get('logo');
		$this->writeHTMLCell(50, 25, '', '', '' , 1, 0, 0, true, 'C', true);
		$this->Image($image_file, 22, 6, 30, 23, 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
		
		// Set font
		$this->SetFont('helvetica', 'B', 22);
		
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}

	
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//$pdf->Table_head = $tableau_head;
//$pdf->Table_body = $tableau_body;
$pdf->info_contrat=$contrat_info;
//$pdf->info_devis=$devis_info;
//var_dump($contrat_info);
// set document information
$pdf->SetCreator(MCfg::get('sys_titre'));
$pdf->SetAuthor(session::get('username'));
$pdf->SetTitle('Contrat');
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
$pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
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

// Print text using writeHTMLCell()
//$pdf->Table_body = $tableau_body;
//$html = $pdf->Table_body;
// ---------------------------------------------------------

//$pdf->writeHTMLCell('', '','' , '', $html , 0, 0, 0, true, 'L', true);
$html=NULL;
//$content = file_get_contents(MPATH_THEMES.'pdf_template/contrat_html.php');
$pdf->lastPage();
//$html = $content;
$html=' <!DOCTYPE html>
<html>
<head>
</head>
<body>
<p style="text-align: left;"><strong><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></strong></p>
<p style="text-align: left;">&nbsp;</p>
<p style="text-align: left;"><strong><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Ref:&nbsp; </span></strong><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">'.$pdf->info_contrat['reference'].'</span></p>
<p style="text-align: left;"><strong><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; </span></strong><span style="text-decoration: underline;"><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000; text-decoration: underline;">CONTRAT DE FOURNITURE DES BANDES PASSANTES</span></span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">ENTRE D&rsquo;UNE PART : 
</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Le Responsable de&nbsp;</span><strong><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">  HELP Tchad</span></strong><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;"> &laquo; Client. &raquo;</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">ET D&rsquo;AUTRE PART :</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">La Soci&eacute;t&eacute;<strong> GLOBAL TECH</strong>, repr&eacute;sent&eacute; par son Directeur G&eacute;n&eacute;ral. M. <strong>MAHAMAT ALI MAHAMOUD</strong></span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">N&rsquo;Djamena &ndash; Tchad,</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Agissant au nom et pour le compte de son entreprise comme &laquo; Fournisseur &raquo; ;</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Il a &eacute;t&eacute; convenu comme suit :</span><br /><strong><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Article 1 : Objet du contrat</span></strong><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Le pr&eacute;sent contrat a pour objet la fourniture des produit list&eacute;s le devis<strong>.</strong></span></p>
<p style="text-align: left;"><strong><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Article 2 : Conditions d&rsquo;ex&eacute;cution</span></strong><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Le fournisseur s&rsquo;engage &agrave; fournir ce service &agrave; 99% et assure une maintenance pr&eacute;ventive et r&eacute;guli&egrave;re du mat&eacute;riel durant le</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">contrat.</span><br /><strong><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Article 3 : Le montant du contrat</span></strong><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Le montant de ce contrat est de deux millions cinq cent vingt milles FCFA (2 520 000F) soit deux cent dix milles FCFA (210</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">000F) par mois.</span><br /><strong><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Article 4 : modalit&eacute; de paiement</span></strong><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">La modalit&eacute; de paiement se fera en avance trimestrielle apr&egrave;s l&rsquo;approbation par les deux partis comme suit :</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">630 000F CFA comme avance le 01.07.2016</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">630 000F CFA comme avance le 01.10.2016</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">630 000F CFA comme avance le 01.01.2017</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">630 000F CFA comme avance le 01.04.2017</span><br /><strong><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Article 5 : P&eacute;nalit&eacute; de retard</span></strong><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">En cas de retard non justifi&eacute; et ce pour des raisons imputables au seul fournisseur, une p&eacute;nalit&eacute; de retard de 1/100 sera</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">appliqu&eacute;e quotidiennement &agrave; compter du premier jour suivant la date d&rsquo;expiration du d&eacute;lai contractuel.</span><br /><strong><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Article 6 : Attribution de juridiction</span></strong><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Les parties conviennent que tout litige relatif &agrave; l&rsquo;application et/ou l&rsquo;interpr&eacute;tation du pr&eacute;sent contrat sera r&eacute;gl&eacute; &agrave; l&rsquo;amiable. A</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">d&eacute;faut d&rsquo;accord, les tribunaux de N&rsquo;Djamena seront comp&eacute;tents.</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Lu et approuv&eacute;, le</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Dont acte sur deux (02) pages en deux (02) exemplaires originaux.</span></p>
<p style="text-align: left;"><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Pour le Fournisseur &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Pour le Client</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">MAHAMAT ALI MAHAMOUD</span><br /><span style="font-family: times new roman,times,serif; font-size: 12pt; color: #000000;">Directeur General</span></p>
</body>
</html> ';

$pdf->writeHTML($html, true, false, true, false, '');
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($file_export,'F');

//============================================================+
// END OF FILE
//============================================================+

