<?php
/*
 * Modele de classe PHP : Debug.php
 */

class Debug{
//__variable lié à la classe
	
    function print_tab( $tableau ) {
		echo "<pre>";
		print_r($tableau);
        echo "</pre>";
    }
	
    function dump_tab( $tableau ) {
		echo "<pre>";
		var_dump($tableau);
        echo "</pre>";
    }
}

$debug =new Debug();