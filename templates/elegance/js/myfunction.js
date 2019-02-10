// Ajax load content
function ajax_loader($url,$data,$redirect){
	//alert($url)
	bootbox.process({
	    		    message:'Working',
	            });
	$('#content').empty();
	$('#content').html('');	
	
	$.ajax({
		cache: false,
		url  : '?_tsk='+$url+'&ajax=1',
		type : 'POST',
		data : $data+'&cor=1',
		dataType:"html",
		success: function(data){
			bootbox.hideAll();
			var data_arry = data.split("#");
			if(data_arry[0]==3){
				ajax_loadmessage(data_arry[1],'nok',5000)
				$('#content').empty();

				if(typeof $redirect !== 'undefined'){
					ajax_loader($redirect,'');

				}else{
					window.setTimeout( function(){
					window.location = "./";
				    }, 5000 );
				}
			}else if(data_arry[0]==4){
				bootbox.process({
	    		    message:'Working',
	            });
	            $('#main-container').empty();
	            $('#main-container').html('');
				ajax_loadmessage(data_arry[1],'nok',5000)
				window.setTimeout( function(){
					    window.location = "./";
			        }, 5000 );
			}else{
				var data_result =  data.split("#||#");
                $("#treeapp").html(data_result[0]);
                //check if is data for tigger then load message
                var data_mes = data_result[1].split('#')
                if(data_mes[0]==3)
                {
                	ajax_loadmessage(data_mes[1],'nok',5000)
				    $('#content').empty();
				    if(typeof $redirect !== 'undefined'){
					    ajax_loader($redirect,'');
				    }else{
					    window.setTimeout( function(){
					    window.location = "./";
				        }, 5000 );
					}				
                }else{
                	$('#content').html(data_result[1]);
                }      
    		}
		},
		timeout: 30000,
		error: function(){
			ajax_loadmessage('Délai non attendue','nok',5000)
		}
        // will fire when timeout is reached
	});
}

//AJAX load bootbox content
function ajax_bbox_loader($url, $data, $titre, $width, $btn_keep = false){
	//alert($url)
	
	$.ajax({
		cache: false,
		url  : '?_tsk='+$url+'&ajax=1',
		type : 'POST',
		data : $data,
		dataType:"html",
		success: function(data){
			
			var data_arry = data.split("#");
			if(data_arry[0]==3){
				ajax_loadmessage(data_arry[1],'nok',5000)
			}else if(data_arry[0]==4){
				bootbox.process({
					message:'Working',
				});
				$('#main-container').empty();
				$('#main-container').html('');
				ajax_loadmessage(data_arry[1],'nok',5000)
				window.setTimeout( function(){
					window.location = "./";
				}, 5000 );
				

			
		}else{
			var dialog = bootbox.dialog({

				message: data,
				title: $titre,
				size: $width !== undefined? $width : '',
				buttons: 			
				{						
					"click" :
					{
						"label" : "Enregistrer",
						"className" : "btn-sm btn-primary send_modal",
						"callback": function(e) {
						
							return false;
						}
					},
					"cancel" :
					{
						"label" : "Annuler",
						"className" : "btn-sm btn-inverse close_modal",
						"callback": function (e) {
							return true;
						}
					} 

				}
			});

			$('.bootbox-body').ace_scroll({
				size: 400
			});


		}
	},
	timeout: 30000,
	error: function(){
		ajax_loadmessage('Délai non attendue','nok',5000)
	}

        // will fire when timeout is reached

    });
    return true;

}

function bb_add_pic($url,$titre,$width){

	$.ajax({
		cache: false,
		url  : '?_tsk='+$url+'&ajax=1',
		type : 'POST',
		data : '',
		dataType:"html",
		success: function(data){
			
			var data_arry = data.split("#");
			if(data_arry[0]==3){

				ajax_loadmessage(data_arry[1],'nok',5000)
			}else{
				
                	bootbox.dialog({
                		
                        message: data,


                        title: $titre,
                        size: $width !== undefined? $width : '',
                        buttons: 			
						{
							
							 
							"cancel" :
							{
							 label: "Annuler",
							 className: "btn-sm",
						    },
							"click" :
							{
								"label" : "Enregistrer",
								"className" : "btn-sm btn-primary",
								"callback": function(e) {
									$pic_link = $("#photo-id").val();
									$pic_titl = $("#pic_titl").val();
									

									if(!$pic_link || !$pic_titl){
										ajax_loadmessage('Il faut remplire les champs','nok',5000);
										return false;
									}else{
									
									$bloc_pic = '<li><a href="#" class="show_pic" rel="'+$pic_link+'"><img width="150" height="150" alt="150x150" src="'+$pic_link+'" /><div class="text"><div class="inner"><input name="photo_id[]" value="'+$pic_link+'" type="hidden"><input  name="photo_titl[]" value="'+$pic_titl+'" type="hidden">'+$pic_titl+'</div></div></a><div class="tools tools-bottom"><a class="del_pic" href="#"><i class="ace-icon fa fa-times red"></i></a></div></li>';
									$('.ace-thumbnails').append($bloc_pic);

									return true;
									}
									
								}
							}, 
							
							
							
						},

                    });


                    
                
			 
			}
		},
		timeout: 30000,
		error: function(){
			ajax_loadmessage('Délai non attendue','nok',5000)
		}

        // will fire when timeout is reached
     
	});
}

$('html').click(function() {
	if ($('#gritter-notice-wrapper').length) {
		 $('#gritter-notice-wrapper').remove();
		 if(!$('.modal-body'.length)){
		 	bootbox.hideAll();
		  }
		 
	};

   
});


$('body').on('click', '.this_url', function() {
	 $('#gritter-notice-wrapper').remove();//remove message box

	 var $url = $(this).attr('rel');
	 var $data = $(this).attr('data') != ""?$(this).attr('data'):"";
	 var $redirect = $(this).attr('redi') != ""?$(this).attr('redi'):"";
	 ajax_loader($url,$data,$redirect);
	 if($(this).parent('li').attr('left_menu') == 1){
	 	
        //
	 	$(".active").removeClass("active");
	 	$(this).parent("li").addClass("active");
	 	
        $(this).parent().parent().parent().addClass("active");

	 };
	 

});



$('body').on('click', '.this_exec', function(e) {
	e.preventDefault();
	 $('#gritter-notice-wrapper').remove();//remove message box
	 

	 var $url = $(this).attr('rel');
	 var $data = $(this).attr('data') != "" ? $(this).attr('data') : "";
	 var $the_table = $(this).closest('table').attr('id');
	 var $go_to = $(this).closest('div').attr('go_to');
	 var $cosutm_noeud = $(this).attr('cn_rmv') !== undefined ? 'cn_rmv'+$(this).attr('cn_rmv') : null;

	 if($go_to == null){
	 	exec_ajax($url, $data, $confirm = 1, '', $the_table, $cosutm_noeud);
	 }else{
	 	exec_ajax_go($url, $data, $confirm = 1, '', $go_to);
	 }
	 
});



function do_ajax($url, $data , $the_table, $cosutm_noeud){
	bootbox.process({
	    		    message:'Working',
	            });
	$.ajax({
                url: '?_tsk='+$url+'&ajax=1',
                type: 'POST',
                data: $data,
                dataType: 'html',
                success: function(data,e) {

                	var data_arry = data.split("#");
                	if(data_arry[0] == 1) {

        				ajax_loadmessage(data_arry[1],'ok',5000);
        				if($cosutm_noeud !== null)
        				{
        					
        					
        					$('.'+$cosutm_noeud).remove();
        				}else{
        					
        					var table = $('#'+$the_table).DataTable();
                            table.row('.selected').remove().draw( false );
        				}
        				
        				bootbox.hideAll();
        				
        			}else{
        				
        				ajax_loadmessage(data_arry[1],'nok',50000);
        				bootbox.hideAll();
        			}                  
                  	          
                },
                timeout: 30000,
		error: function(){
			ajax_loadmessage('Délai non attendue','nok',5000)
		}
    });
}
//Exec function  on backdoor calling do_ajax
function exec_ajax($url, $data, $confirm, $message_confirm , $the_table, $cosutm_noeud){

	var $message = typeof $message_confirm !== 'undefined' ? 'Veuillez confirmer !' : $message_confirm;
   
	if($confirm == 1){
		  bootbox.confirm($message, function(result) {
            if (result) {
            	do_ajax($url, $data, $the_table, $cosutm_noeud); 
            }
          });
	}else{
		do_ajax($url, $data, $the_table, $cosutm_noeud);
	}
   	return true;
}




function do_ajax_go($url, $data , $go_to){
	bootbox.process({
	    		    message:'Working',
	            });
	$.ajax({
                url: '?_tsk='+$url+'&ajax=1',
                type: 'POST',
                data: $data,
                dataType: 'html',
                success: function(data,e) {

                	var data_arry = data.split("#");
                	if(data_arry[0] == 1) {

        				ajax_loadmessage(data_arry[1],'ok',5000);
        				ajax_loader($go_to, $data);
        				bootbox.hideAll();
        				
        			}else{
        				
        				ajax_loadmessage(data_arry[1],'nok',50000);
        				bootbox.hideAll();
        			}      			
                },
                timeout: 30000,
		error: function(){
			ajax_loadmessage('Délai non attendue','nok',5000)
		}
    });
}


//Exec function  on backdoor calling do_ajax
function exec_ajax_go($url, $data, $confirm, $message_confirm , $go_to){

	var $message = typeof $message_confirm !== 'undefined' ? 'Veuillez confirmer go!' : $message_confirm;
   
	if($confirm == 1){
		  bootbox.confirm($message, function(result) {
            if (result) {
            	do_ajax_go($url, $data, $go_to); 
            }
          });
	}else{
		do_ajax_go($url, $data, $go_to);
	}
   	return true;
}


// Load Message

function ajax_loadmessage($core, $class, $time) {
	$.gritter.removeAll();
	
	
	$time = typeof $time !== 'undefined' ? $time : 5000;	

	$laclass = $class == 'ok'?'gritter-success':'gritter-error';
	$titre = $class == 'ok'?'Opération  réussie':'Erreur Opération';
	
	window.setTimeout( function(){
		$.gritter.add({
			title: $titre,
			text:  $core,
			class_name: $laclass + '  gritter-center gritter-light',
			time:  $time,
		});
	}, 10 );
	
	
	return false;


}






// Melsiouns Function
$(function () {
  function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
// Les Masque
  //called when key is pressed in textbox
  $('body').on('keypress keyup change', '.is-number', function(e) {

  	if (e.which != 8 && e.which != 0  && (e.which < 48 || e.which > 57)) {
  		return false;
  	}
  	
  });
 

  	
  $("body").bind("DOMNodeInserted", function() {
   //$(this).find('.is-date').mask('99-99-9999');

   
});
    // $('.is-date').mask('99-99-9999');


    



});



// Remplir Une zone on select Input Select
function load_onselect(field){
	//alert($(field).val());
	 
	if($(field).val()!=""){
		var $zone = $(field).closest('.form-group'); 
		//$("<p>Test</p>").appendTo($zone);
		$.ajax({

			url: "./?_tsk=loadenselect&ajax=1&tb=1",
			type: "POST",
			data: "tab=1&id=" + $(field).val(),
			dataType: 'html',
			success: function(data){
				
				$(data).appendTo($zone);   
			} 
		});
	}else{
		$("#"+zone).empty();
	}    

}
//End Remplir Une zone on select Input Select

//Scroll Left bar
// scrollables
/*$('.slim-scroll').each(function () {
	var $this = $(this);
	$this.slimScroll({
		height: $this.data('height') || 550,
						//railVisible:true
					});
});	*/	
//End Scroll left bat
//Colorbox caller
$(document).ready(function(){
				//Examples of how to assign the Colorbox event to elements
				$('body').on('click', '.iframe_pdf', function() {
					$.ajax({
		                type: 'POST',
		                url: './?_tsk=shopdf&ajax=1',
		                data: 'f='+$(this).attr('rel'),
		                timeout: 3000,
		                success: function(result) {
			                var data_arry = result.split("#");
			
					        if(data_arry[0]==1){
					        	//detrmine is for image or pdf
					        	var $ext_array = data_arry[1].split(".");
					        	if($ext_array[2] == "pdf"){
					        		$.colorbox({iframe:true, width:"80%", height:"90%",href:data_arry[1]});

					        	}else{
					        		$.colorbox({image:true,href:data_arry[1]});

					        	}
					        	
						        return true;
                            }else{
						        ajax_loadmessage(data_arry[1],'nok');
						        return false;
					        }
			            },
		                error: function() {
			                ajax_loadmessage('Affichage Impossible #AJAX','nok',3000);
			                return false;
		                }
	                });
											
					
				});
				// Call report script exec template PDF
				$('body').on('click', '.report_tplt', function() {

					$.ajax({
		                type: 'POST',
		                url: './?_tsk=report&ajax=1',
		                data: $(this).attr('rel')+'&'+$(this).attr('data'),
		                timeout: 30000,
		                dataType:'JSON',
		                success: function(data) {
			                	
					        if(data['error'] == 'error'){
					        	ajax_loadmessage('Erreur chargement Template JS','nok',3000);
					        	return false;
					        }else{
					        	$.colorbox({iframe:true, width:"80%", height:"90%",href:data['file']});
					        	return true;
					        }
					        
			            },
			            
		                error: function() {
			                ajax_loadmessage('Affichage Impossible #AJAX','nok',3000);
			                return false;
		                }
	                });
											
					
				});

				//$(".iframe_pdf").colorbox({iframe:true, width:"80%", height:"90%",href:data});
				$('body').on('click', '.show_pic', function() {
					var $link_pic = $(this).attr('rel');
					$(".show_pic").colorbox({image:true,href:$link_pic});

				});
				$('body').on('click', '.this_map', function() {
					
					var $data = $(this).attr('data');
                    $('body').fullScreen(true);
                     setTimeout(function() { $.colorbox({iframe:true, map:true, width:"100%", height:"100%",href:"./map/?"+$data }) },500)
   		        });

   		        $('body').on('click', '.this_modal', function() {
   		        	var $link  = $(this).attr('rel');
   		        	var $titre = $(this).attr('data_titre'); 
   		        	var $data  = $(this).attr('data'); 

					ajax_bbox_loader($link, $data, $titre, 'large')
   		        });

   		        $('body').on('click', '#btn_action', function() {
   		        	var $url  = $(this).attr('rel');
   		        	var $id = $(this).attr('data_id'); 
   		        	 
                    append_drop_menu($url, $id, '#btn_action');
					
   		        });


   		        $('body').on('click', '.del_pic', function() {
   		        	var $tester = true;
   		        	if($(this).attr('rel') == null){
   		        		$(this).closest('li').remove();
   		        	}else{
   		        		$.ajax({
							type: 'POST',
							url: './?_tsk=upload&ajax=1',
							data: '&del=1&f='+$(this).attr('rel'),


							timeout: 3000,
							success: function(data) {

								var data_arry = data.split("#");

								if(data_arry[0]==1){
									//this.reset_input;
									//ajax_loadmessage(data_arry[1],'ok');
									$tester = true;
								}else{
						            //ajax_loadmessage(data_arry[1],'ok');	
						            ajax_loadmessage(data_arry[1],'nok');
						            $tester = false;						         
					            }
				            },
				            error: function() {
					            ajax_loadmessage('Suppression Impossible #AJAX','nok',3000);
					            $tester = false;
				            }
			            });
			            if($tester == true){
			            	$(this).closest('li').remove();
			            }
   		        	}
   		        });
//Call dashbord first time
});
function call_colorBox(params) {
	$.colorbox({iframe:true, width:"80%", height:"90%",href:"?_tsk=shopdf&ajax=1&doc="+params});
}//End colorboxcaller				


$(document).ready(function(){
	
//alert(firsttime);
if(typeof  firsttime !== 'undefined' &&  firsttime==1)
{
	bootbox.process({
	    		    message:'Working',
	            });
	setTimeout(function() { ajax_loader('dbd') },1000)		
    firsttime == 2;	
}

});

//Apend button action
function  append_drop_menu($url, $id, $btn){
	//Fisrt empty button
	$($btn+' ul').remove();
	$.ajax({
		type: 'POST',
		url: '?_tsk='+$url+'&ajax=1',
		data: 'id='+ $id + '&act=1',		
		timeout: 3000,
		success: function(data) {
			var data_arry = data.split("#");
			if(data_arry[0]==3){

				ajax_loadmessage(data_arry[1],'nok',5000)
				$('#content').empty();

				if(typeof $redirect !== 'undefined'){
					ajax_loader($redirect,'');

				}else{
					window.setTimeout( function(){
					window.location = "./";
				}, 5000 );

				}
			}else if(data_arry[0]==4){
				bootbox.process({
	    		    message:'Working',
	            });
	            $('#main-container').empty();
	            $('#main-container').html('');
				ajax_loadmessage(data_arry[1],'nok',5000)
				window.setTimeout( function(){
					    window.location = "./";
				        }, 5000 );
			
			}else{
				$($btn).append(data);
			}
		},
		error: function() {
			ajax_loadmessage('Action indisponible','nok',3000);
		}
	});    
}

/*$(document).keydown(function(e){
	if (e.keyCode == 8) { 
		$data_go_back =$.parseJSON(ace.cookie.get("gobak"));
		$('#gritter-notice-wrapper').remove();//remove message box

        var $url = $data_go_back['app'];
        var $data = $data_go_back['data'] != null ? $data_go_back['data']:"";
        var $redirect = "";
        var $item = $data_go_back['item'];
        ajax_loader($url,$data,$redirect);
                //
	 	$(".active").removeClass("active");
	 	$("#"+$item).addClass("active");
	 	
	 	$("#"+$item).parent().parent().addClass("active");

                
    }
});*/
$('body').on('click', '#call_go_back', function(e) {

		$data_go_back =$.parseJSON(ace.cookie.get("gobak"));
		$('#gritter-notice-wrapper').remove();//remove message box

        var $url = $data_go_back['app'] === "undefined" ? "tdb" : $data_go_back['app'];
        var $data = $data_go_back['data'] !== null ? $data_go_back['data']:"";
        var $redirect = "";
        var $item = $data_go_back['item'];
               
        ajax_loader($url, $data, $redirect);
                //
	 	$(".active").removeClass("active");
	 	$("#"+$item).addClass("active");
	 	
	 	$("#"+$item).parent().parent().addClass("active");
	 
});

$('body').on('click', '.this_url_jump', function(e) {
	    $data_go_back =$.parseJSON(ace.cookie.get("gobak"));
		$('#gritter-notice-wrapper').remove();//remove message box
		var $data = $(this).attr('data');

		$.ajax({
                url: '?_tsk=seturl&ajax=1',
                type: 'POST',
                data: $data,
                dataType: 'JSON',
                success: function(data,e) {
                	
                	if(data['error'] == 'false'){
                		ajax_loadmessage('Data lost','nok',50000);
                	}else{
                		var $go_to = data['task'];
                		var $data = data['data'];
                		ajax_loader($go_to, $data);
                	}     			
                },
                timeout: 30000,
		    error: function(){
		    	ajax_loadmessage('Délai non attendue','nok',5000)
		    }
        });
});