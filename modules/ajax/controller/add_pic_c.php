<?php

$form = new Mform('add_pic', 'add_pic', '', 'add_pic', NULL);
$photo_array[]  = array('required', 'true', 'Insérer Titre de la photo 1');
$form->input('Titre de l\'image', 'pic_titl', 'text', 12, null, $photo_array);
$form->input('Photo', 'photo', 'file', 12, null, null);
$form->file_js('photo', 500000, 'Image');
$form->render();
?>
