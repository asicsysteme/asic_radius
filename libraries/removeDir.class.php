<?php 
##############################################################
# Class removeDir
#
#  Author: Ritesh Patel
#  E-Mail: patel.ritesh.mscit@gmail.com
#  Rajkot - Gujarat - India
#
#  Objective:
#    This class allows programmer to easily remove directory whether it is empty or not empty
#
#
#
#  Methods:
#  * removeDir()         		- Constructor 
#  * isEmpty($path)				- Check whether Dirctory is empty or not
#  * deleteDir([$dirnm])       	- Delete the Directory with its sub-dirctories and files
# 
#  If you modify this class, or have any ideas to improve it, please contact me!
#  You are allowed to redistribute this class, if you keep my name and contact e-mail on it.
#
#  PLEASE! IF YOU USE THIS CLASS IN ANY OF YOUR PROJECTS, PLEASE LET ME KNOW!
#  If you have problems using it, don't think twice before contacting me!
#
##############################################################

class removeDir{
	private $dirmn;
	function removeDir(){} // Constructor
	function isEmpty($path) // Check whether Dirctory is empty or not
	{
		$handle=opendir($path);
		$i=0;
		while (false !== ($file = readdir($handle))) 
			$i++;
		closedir($handle); 
		if($i>=2)	
			return false;
		else
			return true;
	}
static public	function deleteDir($dirnm) // Delete the Directory with its sub-dirctories and files
{
	if(file_exists($dirnm)){
		$d=dir($dirnm);
		
		while(false !== ($entry = $d->read()))
		{
			if($entry=="." || $entry=="..")
				continue;
			$currele=$d->path."/".$entry;
			if(is_dir($currele))
			{
				if($this->isEmpty($currele))
				{
					@rmdir($currele);
				}
				else
				{
					$this->deleteDir($currele);
				}
			}
			else
			{
				@unlink($currele);
			}
		}
		$d->close();
		rmdir($dirnm);
	}
	return true;
}

static public	function deletefile($file){
	
	if(file_exists($file)){
		@unlink($file);
		return true;
	}else{
		return false;	
	}
	
}

}
$delet_dir = new removeDir();
$delet_dir->deleteDir('cache');

?>