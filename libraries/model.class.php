<?php
class model {

   public $model;
//recuperer repertoir et nom de fichier à charger 
   static public function load($model_rep,$model_file) {
	   
         require_once(MPATH_MODULES.$model_rep.SLASH.'model/'.$model_file.'_m.php');
          
		          
   }


}

?>
