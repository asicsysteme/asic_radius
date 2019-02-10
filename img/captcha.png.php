<?php 
session_start();
function ChaineAleatoire($nbcar)
{
	$chaine = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	srand((double)microtime()*1000000);

	$variable='';
        
	for($i=0; $i<$nbcar; $i++) $variable .= $chaine{rand()%strlen($chaine)};
	return $variable;
}
if(isset($_SESSION['Captcha'])){unset ($_SESSION['Captcha']);}
$_SESSION['Captcha'] = ChaineAleatoire(5);



header("Content-type: image/png");
$img = imagecreatefrompng('captcha.png' ) or die ("Problème de création GD");



$background_color = imagecolorallocate ($img, 255, 255, 255);
$ecriture_color = imagecolorallocate($img, 0, 0, 0);
imagestring ($img, 20,5, 4, $_SESSION['Captcha'] , $ecriture_color);
imagepng($img);

?>