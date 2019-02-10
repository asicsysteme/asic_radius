<?php
// ---------------------------------------------------
//  Informations de Projet proud version
// ---------------------------------------------------
/**
 * @package    MRN_ERP
 * @version    1.0.1
 *
 * @copyright  Copyright (C) 2011 - 2016 Africa Telecom Solution, SARL. All rights reserved.
 * @license    
 */



/**
 *  Définir minimale prise en charge PHP version de l'application comme une constante de sorte qu'il peut être référencée dans l'application..
 */
define('MRN_MINIMUM_PHP', '7.2.4');

/*if (version_compare(PHP_VERSION, MRN_MINIMUM_PHP, '<'))
{
	die('Votre Serveur doit utiliser PHP version ' . MRN_MINIMUM_PHP . ' ou plus !');
}*/





/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
define('_MEXEC', 1);



if (!defined('MDEFINES'))
{
	define('MPATH_BASE', '.');
	require_once MPATH_BASE . '/mincludes/defines.php';
}

require_once MPATH_INCLUDES . 'framework.php';



// ---------------------------------------------------
//  Charger Template selon session
// ---------------------------------------------------

Template::load();


