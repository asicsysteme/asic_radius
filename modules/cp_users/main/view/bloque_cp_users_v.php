<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_users
//Created : 03-02-2019
//View
//Get all cp_users info 
$info_cp_users = new Mcp_users();
//Set ID of Module with POST id
$info_cp_users->id_cp_users = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$info_cp_users->get_cp_users())
{ 	
    // returne message error red to client 
    exit('3#'.$info_cp_users->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



$form = new Mform('bloque_cp_users', 'bloque_cp_users', '', 'cp_users', '0', 'is_modal');
$form->input_hidden('id', $info_cp_users->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));



//Input Example
$form->input('Motif blocage', 'motif', 'text' ,'9', null, null, null, $readonly = null);
//For more Example see form class
//Form render
$form->render();
?>


<script type="text/javascript">
$(document).ready(function() {
    
    $('.send_modal').on('click', function () {
        if(!$('#bloque_cp_users').valid())
        {
            //alert('Form validate');
            e.preventDefault();
        }else{
            //alert('Run AJAX');
            $.ajax({
                cache: false,
                url  : '?_tsk=bloque_cp_users&ajax=1',
                type : 'POST',
                data : $('#bloque_cp_users').serialize(),
                dataType:"html",
                success: function(data_f)
                {

                    var data_arry = data_f.split("#");
                    if(data_arry[0]==0){
                        ajax_loadmessage(data_arry[1],'nok',3000);
                    }else{ 
 
                        ajax_loadmessage(data_arry[1],'ok',3000);
                        var t1 = $('.dataTable').DataTable().draw();
                        $('.close_modal').trigger('click');
                        
                    }
                },
                timeout: 30000,
                error: function(){
                    ajax_loadmessage('Délai non attendue','nok',5000)

                }
            });

        }

    });

});  
</script>
		