(function ($) {
    $.extend({
        playSound: function () {
            return $(
                   '<audio class="sound-player" autoplay="autoplay" style="display:none;">'
                     + '<source src="' + arguments[0] + '" />'
                     + '<embed src="' + arguments[0] + '" hidden="true" autostart="true" loop="false"/>'
                   + '</audio>'
                 ).appendTo('body');
        },
        stopSound: function () {
            $(".sound-player").remove();
        }
    });
})(jQuery);
$(document).ready(function(){
	function notify($titl, $msg, $style) {
		$.notify({
			title: $titl,
			text:  $msg,
			image: "<img src='img/Mail.png'/>"
		}, {
			style:       'metro',
			className:   $style,
			autoHide:    true,
			clickToHide: true
		});
	}

	function get_notif_list(){
		$.ajax({
			type     :'POST',
			url      :'./?_tsk=notif&ajax=1',
			data     :'ul=1',
			dataType :'JSON',
			timeout: 3000,
			success: function(result) {

				if(result != null){
					read_notif_arr(result['arr']);
					//alert(result['sum']);
					//var result_arry = result.split("[#]");
					if(result['sum'] > 0){
						$('#zone_notif').removeClass('hide');
					}else{
						$('#zone_notif').addClass('hide');
					}
					$('#sum_notif').text(result['sum']);
					$('#notif_ul').html(result['list']);
					
				}
			},
			/*error: function(reponse) {
                reponse['responseText'] = typeof reponse['responseText'] == 'undefined'  ? '0' : reponse['responseText'];
				var data_mes = reponse['responseText'].split('[#]')
				bootbox.process({
	    		    message:'Working',
	            });
	            $('#main-container').empty();
	            $('#main-container').html('');

				//ajax_loadmessage('Déconnexion automatique','nok',5000)
				window.setTimeout( function(){
					    window.location = "./?alg="+data_mes[1];
				        }, 1000 );
			}*/
		});
	}

	function read_notif_arr($arr){
        var obj = jQuery.parseJSON($arr);
        $.each(obj, function(key,value) {
            var $old_value = parseInt($('#notify_'+value['app']).text()) ? parseInt($('#notify_'+value['app']).text()) : 0;
            var $new_value = value['count_notif'];
            if($new_value > $old_value){
            	   	$.gritter.add({
						title:      'Nouvelle Notification',
						text:       'Nouvelle ligne dans le module: <b>'+value['dscrip']+'<b>',
						//text:     '<a href="#" class="this_url" rel="'+value['app']+'">Nouvelle ligne dans le module: <b>'+value['dscrip']+'<b></a>  ',
						class_name: 'gritter-error'
					});
					$.playSound('./img/notify.mp3')
                    //$.stopSound();                    
            }
            
        });
	}

	var start = new Date;

	setInterval(function() {
		get_notif_list();
	}, 15000);
    

})


