<?php

if (Mreq::tp('upld')==1) {
	   $fileid = Mreq::tp('fileID');

      $handle = new Mupload();
      
      if($handle->upload($_FILES[$fileid])){
            $handle->file_max_size = '1000000000';
            $handle->Process(MPATH_TEMP.session::get('ssid'));
            $handle->stop_all = true;      

      $handle-> Clean();

      }

}
if(Mreq::tp('del')==1) {
  $temp_file = Mreq::tp('t') == 't' ? true: false;
	$handle = new Mupload();
  $handle->stop_all = true;
  $handle->delete_file(Mreq::tp('f'), $temp_file);
}










