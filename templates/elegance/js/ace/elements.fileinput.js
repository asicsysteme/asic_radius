/**
 <b>Ace file input element</b>. Custom, simple file input element to style browser's default file input.
 */
 (function($ , undefined) {
 	var multiplible = 'multiple' in document.createElement('INPUT');
	var hasFileList = 'FileList' in window;//file list enabled in modern browsers
	var hasFileReader = 'FileReader' in window;
	var hasFile = 'File' in window;

	var Ace_File_Input = function(element , settings) {

		var self = this;
		
		var attrib_values = ace.helper.getAttrSettings(element, $.fn.ace_file_input.defaults);
		this.settings = $.extend({}, $.fn.ace_file_input.defaults, settings, attrib_values);
		

		this.$element = $(element);
		this.element = element;
		this.disabled = false;
		this.can_reset = true;
		

		this.$element
		.off('change.ace_inner_call')
		.on('change.ace_inner_call', function(e , ace_inner_call){
			if(self.disabled) return;

			if(ace_inner_call === true) return;//this change event is called from above drop event and extra checkings are taken care of there
			return handle_on_change.call(self);
			//if(ret === false) e.preventDefault();
		});

		var parent_label = this.$element.closest('label').css({'display':'block'})
		var tagName = parent_label.length == 0 ? 'label' : 'span';//if not inside a "LABEL" tag, use "LABEL" tag, otherwise use "SPAN"
		var modal_parent = this.$element.closest('.bootbox-body');
		if(modal_parent.length == 0){
			this.$element.wrap('<'+tagName+' class="ace-file-input '+this.settings.container_width+'" />');
		}else{
			this.$element.wrap('<'+tagName+' class="ace-file-input col-xs-12 col-sm-10" />');
		}
		

		this.apply_settings();
		this.reset_input_field();//for firefox as it keeps selected file after refresh
	}
	Ace_File_Input.error = {
		'FILE_LOAD_FAILED' : 1,
		'IMAGE_LOAD_FAILED' : 2,
		'THUMBNAIL_FAILED' : 3
	};


	Ace_File_Input.prototype.apply_settings = function() {
		var self = this;


		this.multi = this.$element.attr('multiple') && multiplible;
		this.well_style = this.settings.style == 'well';

		if(this.well_style) this.$element.parent().addClass('ace-file-multiple');
		else this.$element.parent().removeClass('ace-file-multiple');


		this.$element.parent().find(':not(input[type=file])').remove();//remove all except our input, good for when changing settings
		//this.$element.after('<span class="ace-file-container-tool col-sm-2"><a class="btn_show" href="#"><i class=" ace-icon fa fa-search show_file"></i></a><a class="btn_remove" href="#"><i class=" ace-icon fa fa-times remove_file"></i></a></span>');
		this.$element.after('<span class="editable-buttons col-sm-3"><a class="btn btn-info  btn_show "><i class="ace-icon fa fa-search show_file"></i></a><a class="btn btn-danger  btn_remove "><i class="ace-icon fa fa-times"></i></a></div>');
		
		
		
		this.$element.after('<span class="ace-file-container col-sm-12" data-title="'+this.settings.btn_choose+'"><span class="ace-file-name" data-title="'+this.settings.no_file+'">'+(this.settings.no_icon ? '<i class="'+ ace.vars['icon'] + this.settings.no_icon+'"></i>' : '')+'</span></span>');
		this.$element.parent().find('.btn_show').removeClass('iframe_pdf').removeAttr('rel');
		if(this.settings.value_field != ''){
			filename = this.settings.value_field;

			var index = filename.lastIndexOf("\\") + 1;
			if(index == 0)index = filename.lastIndexOf("/") + 1;
			filename = filename.substr(index);
			format = false;
			if(format == false) {
				if((/\.(jpe?g|png|gif|svg|bmp|tiff?)$/i).test(this.settings.value_input)) {				
					format = 'image';
				}
				else if((/\.(mpe?g|flv|mov|avi|swf|mp4|mkv|webm|wmv|3gp)$/i).test(this.settings.value_input)) {
					format = 'video';
				}
				else if((/\.(mp3|ogg|wav|wma|amr|aac)$/i).test(this.settings.value_input)) {
					format = 'audio';
				}
				else format = 'file';
			}
			
			
			var fileIcons = {
				'file' : 'fa fa-file',
				'image' : 'fa fa-picture-o file-image',
				'video' : 'fa fa-film file-video',
				'audio' : 'fa fa-music file-audio'
			};
			var fileIcon = fileIcons[format];
			//alert(1+' '+ filename + '  ' + fileIcon + ' ' +this.settings.value_input);

			$data_title = this.settings.value_input === "" ? filename : this.settings.value_input;
			$field = this.settings.field;

			if(this.settings.value_input != ""){self.disable();}

			this.$element.nextAll().eq(0).find('.ace-file-name').attr({'data-title':$data_title}).find(ace.vars['.icon']).attr('class', ace.vars['icon'] + fileIcon);
			
			this.$element.nextAll().eq(0).addClass('selected col-sm-9');
			
			this.$element.nextAll().eq(1).css('display', 'block');
			this.$element.nextAll().eq(1).attr('data-format', format);
			//if(format == 'file'){
				this.$element.parent().find('.btn_show').addClass('iframe_pdf').attr('field', $field ).attr('rel',filename);
				this.$element.parent().find('.btn_remove').attr('rel', 'p');
			//}
			
		
		}else{
			this.$element.parent().find('.btn_show').removeClass('iframe_pdf').removeAttr('rel');
		}
		this.$label = this.$element.next();
		this.$container = this.$element.closest('.ace-file-input');
		rmv_btn = this.$element.parent().find('.btn_remove');
		shw_btn = this.$element.parent().find('.btn_show');
		rmv_btn.on(ace.click_event, function(e){
			    if(self.settings.value_input == ''){
					ajax_loadmessage('Suppression Impossible, il faut choisir un autre fichier','nok',3000);
					return false;
				}
				e.preventDefault();
				if( !self.can_reset ) return false;
				
				var ret = true;
				if(self.settings.before_remove) ret = self.settings.before_remove.call(self.element);
				if(!ret) return false;
				
				if(self.settings.is_edit == 1){
					bootbox.confirm('<p class="text-warning">Etes-vous sure de supprimer ce fichier ?</br> Vous serez oubligé de le remplacer par un autre ! </p>', function(result) {
						if (result){
							if(self.remove_file()){
								var r = self.reset_input();
							}
						}
					});

				}else{
					if(self.remove_file()){
						var r = self.reset_input();
					}
				}
		
				return false;
			});
		    shw_btn.on(ace.click_event, function(e){
			    self.show_file();
			});

		

		//Bloc add hidden input stock 
		
		 var input_file_name = 
		 $('<input type="hidden" id="'+this.$element.attr('id')+'-id" name="'+this.$element.attr('id')+'-id" value="'+this.settings.value_field+'" >').appendTo(this.$element.parent());
         

		if(this.settings.droppable && hasFileList) {
			enable_drop_functionality.call(this);
		}
	}



	Ace_File_Input.prototype.show_file_list = function($files , inner_call) {
		var files = typeof $files === "undefined" ? this.$element.data('ace_input_files') : $files;
		if(!files || files.length == 0) return;
		
		//////////////////////////////////////////////////////////////////
		
		if(this.well_style) {
			this.$label.find('.ace-file-name').remove();
			if(!this.settings.btn_change) this.$label.addClass('hide-placeholder');
		}
		this.$label.attr('data-title', this.settings.btn_change).addClass('selected');
		
		for (var i = 0; i < files.length; i++) {
			var filename = '', format = false;
			if(typeof files[i] === "string") filename = files[i];
			else if(hasFile && files[i] instanceof File) filename = $.trim( files[i].name );
			else if(files[i] instanceof Object && files[i].hasOwnProperty('name')) {
				//format & name specified by user (pre-displaying name, etc)
				filename = files[i].name;
				if(files[i].hasOwnProperty('type')) format = files[i].type;
				if(!files[i].hasOwnProperty('path')) files[i].path = files[i].name;
			}
			else continue;
			
			var index = filename.lastIndexOf("\\") + 1;
			if(index == 0)index = filename.lastIndexOf("/") + 1;
			filename = filename.substr(index);
			
			if(format == false) {
				if((/\.(jpe?g|png|gif|svg|bmp|tiff?)$/i).test(filename)) {				
					format = 'image';
				}
				else if((/\.(mpe?g|flv|mov|avi|swf|mp4|mkv|webm|wmv|3gp)$/i).test(filename)) {
					format = 'video';
				}
				else if((/\.(mp3|ogg|wav|wma|amr|aac)$/i).test(filename)) {
					format = 'audio';
				}
				else format = 'file';
			}
			
			var fileIcons = {
				'file'  : 'fa fa-file',
				'image' : 'fa fa-picture-o file-image',
				'video' : 'fa fa-film file-video',
				'audio' : 'fa fa-music file-audio'
			};
			var fileIcon = fileIcons[format];
			
			
			if(!this.well_style) {
				//alert(2+' '+ filename + '  '+ this.settings.value_input + '  '+fileIcon);

				$data_title = this.settings.value_input === null ? filename : this.settings.value_input;
				this.$label.find('.ace-file-name').attr({'data-title':$data_title}).find(ace.vars['.icon']).attr('class', ace.vars['icon'] + fileIcon);
                
				this.$element.nextAll().eq(0).addClass('col-sm-9');
				this.$element.nextAll().eq(1).css('display', 'block');
				this.$element.nextAll().eq(1).attr('data-format', format);
				this.settings.value_field = '';
			}else {
				this.$label.append('<span class="ace-file-name" data-title="'+filename+'"><i class="'+ ace.vars['icon'] + fileIcon+'"></i></span>');
				var type = (inner_call === true && hasFile && files[i] instanceof File) ? $.trim(files[i].type) : '';
				var can_preview = hasFileReader && this.settings.thumbnail 
				&&
						( (type.length > 0 && type.match('image')) || (type.length == 0 && format == 'image') )//the second one is for older Android's default browser which gives an empty text for file.type
						if(can_preview) {
							var self = this;
							$.when(preview_image.call(this, files[i])).fail(function(result){
						//called on failure to load preview
						if(self.settings.preview_error) self.settings.preview_error.call(self, filename, result.code);
					})
						}
					}
				}

				return true;
			}

			Ace_File_Input.prototype.reset_input = function() {
				this.reset_input_ui();
				this.reset_input_field();
				this.$element.parent().find('.btn_show').removeClass('iframe_pdf').removeAttr('rel');
				this.settings.value_field = '';
				this.settings.is_edit = null;
				$('#'+this.$element.attr('id')+'-id').attr('value', '');
				this.$element.nextAll().eq(1).removeAttr('data-format');
				
				this.enable();
				
			}
			Ace_File_Input.prototype.show_file = function() {

             
				
				/*var $link_file = $('#'+this.$element.attr('id')+'-id').attr('value');
				if($link_file != this.settings.value_field){
					this.$element.parent().find('.btn_show').removeClass('iframe_pdf').removeAttr('rel');
				}*/
				
                	if(this.settings.value_field != ''){
                		$link_file = this.settings.value_field;
                	}else{
                		$link_file = $('#'+this.$element.attr('id')+'-id').attr('value');
                		var $ext_array = $link_file.split(".");
                		if($ext_array[$ext_array.length-1] == "pdf"){
					        		$.colorbox({iframe:true, width:"80%", height:"90%",href:$link_file});
					        	}else{
					        		$.colorbox({image:true,href:$link_file});
					        	}
                		
                	}                           
			}

			Ace_File_Input.prototype.remove_file = function() {
				//Call confirmation message before
				var $temp = this.$element.parent().find('.btn_remove').attr('rel');
				var $tester = true;
						$.ajax({
							type: 'POST',
							url: './?_tsk=upload&ajax=1',
							data: '&del=1&f='+$('#'+this.$element.attr('id')+'-id').attr('value')+'&t='+$temp,
							timeout: 3000,
							dataType: 'JSON',
							success: function(data) {
								if(data['error']){
									//this.reset_input;
									//ajax_loadmessage(data['message'],'ok');
									$tester = true;
									
								}else{
						            //ajax_loadmessage(data_arry[1],'ok');	
						            ajax_loadmessage(data['message'], 'nok');
						            $tester = false;						         
					            }
				            },
				            error: function() {
					            ajax_loadmessage('Suppression Impossible #AJAX','nok',3000);
					            $tester = false;
				            }
			            });

			            if($tester == true){
			            	return true;
			            }else{
			            	return false;
			            }      
			            				
				}

			Ace_File_Input.prototype.reset_input_ui = function() {
				this.$label.attr({'data-title':this.settings.btn_choose, 'class':'ace-file-container col-xs-12'})
				.find('.ace-file-name:first').attr({'data-title':this.settings.no_file , 'class':'ace-file-name'})
				.find(ace.vars['.icon']).attr('class', ace.vars['icon'] + this.settings.no_icon)
				.prev('img').remove();
				if(!this.settings.no_icon) this.$label.find(ace.vars['.icon']).remove();

				this.$label.find('.ace-file-name').not(':first').remove();
				this.$element.nextAll().eq(1).css('display', 'none');

				this.reset_input_data();

		//if(ace.vars['old_ie']) ace.helper.redraw(this.$container[0]);
	}
	Ace_File_Input.prototype.reset_input_field = function() {
		//http://stackoverflow.com/questions/1043957/clearing-input-type-file-using-jquery/13351234#13351234
		this.$element.wrap('<form>').parent().get(0).reset();
		this.$element.unwrap();
		
		//strangely when reset is called on this temporary inner form
		//only **IE9/10** trigger 'reset' on the outer form as well
		//and as we have mentioned to reset input on outer form reset
		//it causes infinite recusrsion by coming back to reset_input_field
		//thus calling reset again and again and again
		//so because when "reset" button of outer form is hit, file input is automatically reset
		//we just reset_input_ui to avoid recursion
	}
	Ace_File_Input.prototype.reset_input_data = function() {
		if(this.$element.data('ace_input_files')) {
			this.$element.removeData('ace_input_files');
			this.$element.removeData('ace_input_method');
		}
	}

	Ace_File_Input.prototype.enable_reset = function(can_reset) {
		this.can_reset = can_reset;
	}

	Ace_File_Input.prototype.disable = function() {
		this.disabled = true;
		this.$element.attr('disabled', 'disabled').addClass('disabled');
	}
	Ace_File_Input.prototype.enable = function() {
		this.disabled = false;
		this.$element.removeAttr('disabled').removeClass('disabled');
	}

	Ace_File_Input.prototype.files = function() {
		return $(this).data('ace_input_files') || null;
	}
	Ace_File_Input.prototype.method = function() {
		return $(this).data('ace_input_method') || '';
	}
	
	Ace_File_Input.prototype.update_settings = function(new_settings) {
		this.settings = $.extend({}, this.settings, new_settings);
		this.apply_settings();
	}
	
	Ace_File_Input.prototype.loading = function(is_loading) {
		if(is_loading === false) {
			this.$container.find('.ace-file-overlay').remove();
			this.element.removeAttribute('readonly');
		}
		else {
			var inside = typeof is_loading === 'string' ? is_loading : '<i class="overlay-content fa fa-spin fa-spinner orange2 fa-2x"></i>';
			var loader = this.$container.find('.ace-file-overlay');
			if(loader.length == 0) {
				loader = $('<div class="ace-file-overlay"></div>').appendTo(this.$container);
				loader.on('click tap', function(e) {
					e.stopImmediatePropagation();
					e.preventDefault();
					return false;
				});
				
				this.element.setAttribute('readonly' , 'true');//for IE
			}
			loader.empty().append(inside);
		}
	}



	var enable_drop_functionality = function() {
		var self = this;
		
		var dropbox = this.$element.parent();
		dropbox
		.off('dragenter')
		.on('dragenter', function(e){
			e.preventDefault();
			e.stopPropagation();
		})
		.off('dragover')
		.on('dragover', function(e){
			e.preventDefault();
			e.stopPropagation();
		})
		.off('drop')
		.on('drop', function(e){
			e.preventDefault();
			e.stopPropagation();

			if(self.disabled) return;

			var dt = e.originalEvent.dataTransfer;
			var file_list = dt.files;
			if(!self.multi && file_list.length > 1) {//single file upload, but dragged multiple files
				var tmpfiles = [];
				tmpfiles.push(file_list[0]);
				file_list = tmpfiles;//keep only first file
			}
			
			
			file_list = processFiles.call(self, file_list, true);//true means files have been selected, not dropped
			if(file_list === false) return false;

			self.$element.data('ace_input_method', 'drop');
			self.$element.data('ace_input_files', file_list);//save files data to be used later by user

			self.show_file_list(file_list , true);
			
			self.$element.triggerHandler('change' , [true]);//true means ace_inner_call
			return true;
		});
	}
	
	
	var handle_on_change = function() {
		
		var file_list = this.element.files || [this.element.value];/** make it an array */
		
		file_list = processFiles.call(this, file_list, false);//false means files have been selected, not dropped
		if(file_list === false) return false;
		
		this.$element.data('ace_input_method', 'select');
		this.$element.data('ace_input_files', file_list);
		
		this.show_file_list(file_list , true);
		
		return true;
	}



	var preview_image = function(file) {
		var self = this;
		var $span = self.$label.find('.ace-file-name:last');//it should be out of onload, otherwise all onloads may target the same span because of delays
		
		var deferred = new $.Deferred;
		
		var getImage = function(src) {
			$span.prepend("<img class='middle' style='display:none;' />");
			var img = $span.find('img:last').get(0);

			$(img).one('load', function() {
				imgLoaded.call(null, img);
			}).one('error', function() {
				imgFailed.call(null, img);
			});

			img.src = src;
		}
		var imgLoaded = function(img) {
			//if image loaded successfully
			var size = 50;
			if(self.settings.thumbnail == 'large') size = 150;
			else if(self.settings.thumbnail == 'fit') size = $span.width();
			$span.addClass(size > 50 ? 'large' : '');

			var thumb = get_thumbnail(img, size/**, file.type*/);
			if(thumb == null) {
				//if making thumbnail fails
				$(this).remove();
				deferred.reject({code:Ace_File_Input.error['THUMBNAIL_FAILED']});
				return;
			}

			var w = thumb.w, h = thumb.h;
			if(self.settings.thumbnail == 'small') {w=h=size;};
			$(img).css({'background-image':'url('+thumb.src+')' , width:w, height:h})
			.data('thumb', thumb.src)
			.attr({src:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWNgYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg=='})
			.show()

			///////////////////
			deferred.resolve();
		}
		var imgFailed = function(img) {
			//for example when a file has image extenstion, but format is something else
			$span.find('img').remove();
			deferred.reject({code:Ace_File_Input.error['IMAGE_LOAD_FAILED']});
		}
		
		if(hasFile && file instanceof File) {
			var reader = new FileReader();
			reader.onload = function (e) {
				getImage(e.target.result);
			}
			reader.onerror = function (e) {
				deferred.reject({code:Ace_File_Input.error['FILE_LOAD_FAILED']});
			}
			reader.readAsDataURL(file);
		}
		else {
			if(file instanceof Object && file.hasOwnProperty('path')) {
				getImage(file.path);//file is a file name (path) --- this is used to pre-show user-selected image
			}
		}
		
		return deferred.promise();
	}

	var get_thumbnail = function(img, size, type) {
		var w = img.width, h = img.height;
		
		//**IE10** is not giving correct width using img.width so we use $(img).width()
		w = w > 0 ? w : $(img).width()
		h = h > 0 ? h : $(img).height()

		if(w > size || h > size) {
			if(w > h) {
				h = parseInt(size/w * h);
				w = size;
			} else {
				w = parseInt(size/h * w);
				h = size;
			}
		}


		var dataURL
		try {
			var canvas = document.createElement('canvas');
			canvas.width = w; canvas.height = h;
			var context = canvas.getContext('2d');
			context.drawImage(img, 0, 0, img.width, img.height, 0, 0, w, h);
			dataURL = canvas.toDataURL(/*type == 'image/jpeg' ? type : 'image/png', 10*/)
		} catch(e) {
			dataURL = null;
		}
		if(! dataURL) return null;
		

		//there was only one image that failed in firefox completely randomly! so let's double check things
		if( !( /^data\:image\/(png|jpe?g|gif);base64,[0-9A-Za-z\+\/\=]+$/.test(dataURL)) ) dataURL = null;
		if(! dataURL) return null;
		

		return {src: dataURL, w:w, h:h};
	}
	

	
	var processFiles = function(file_list, dropped) {
		var ret = checkFileList.call(this, file_list, dropped);
		if(ret === -1) {
			this.reset_input();
			return false;
		}
		if( !ret || ret.length == 0 ) {
			//if( !this.$element.data('ace_input_files') ) this.reset_input();
			//if nothing selected before, reset because of the newly unacceptable (ret=false||length=0) selection
			//otherwise leave the previous selection intact?!!!
			return false;
		}
		if (ret instanceof Array || (hasFileList && ret instanceof FileList)) file_list = ret;
		
		
		ret = true;
		if(this.settings.before_change) ret = this.settings.before_change.call(this.element, file_list, dropped);
		if(ret === -1) {
			this.reset_input();
			return false;
		}
		if(!ret || ret.length == 0) {
			if( !this.$element.data('ace_input_files') ) this.reset_input();
			return false;
		}
		
		//inside before_change you can return a modified File Array as result
		if (ret instanceof Array || (hasFileList && ret instanceof FileList)) file_list = ret;
		
		return file_list;
	}
	
	
	var getExtRegex = function(ext) {
		if(!ext) return null;
		if(typeof ext === 'string') ext = [ext];
		if(ext.length == 0) return null;
		return new RegExp("\.(?:"+ext.join('|')+")$", "i");
	}
	var getMimeRegex = function(mime) {
		if(!mime) return null;
		if(typeof mime === 'string') mime = [mime];
		if(mime.length == 0) return null;
		return new RegExp("^(?:"+mime.join('|').replace(/\//g, "\\/")+")$", "i");
	}
	var checkFileList = function(files, dropped) {
		var allowExt   = getExtRegex(this.settings.allowExt);

		var denyExt    = getExtRegex(this.settings.denyExt);
		
		var allowMime  = getMimeRegex(this.settings.allowMime);

		var denyMime   = getMimeRegex(this.settings.denyMime);

		var maxSize    = this.settings.maxSize || false;
		
		if( !(allowExt || denyExt || allowMime || denyMime || maxSize) ) return true;//no checking required


		var safe_files = [];
		var error_list = {}
		for(var f = 0; f < files.length; f++) {
			var file = files[f];
			
			//file is either a string(file name) or a File object
			var filename = !hasFile ? file : file.name;
			if( allowExt && !allowExt.test(filename) ) {
				//extension not matching whitelist, so drop it
				if(!('ext' in error_list)) error_list['ext'] = [];
				error_list['ext'].push(filename);
				
				continue;
			} else if( denyExt && denyExt.test(filename) ) {
				//extension is matching blacklist, so drop it
				if(!('ext' in error_list)) error_list['ext'] = [];
				error_list['ext'].push(filename);
				
				continue;
			}

			var type;
			if( !hasFile ) {
				//in browsers that don't support FileReader API
				safe_files.push(file);
				continue;
			}
			else if((type = $.trim(file.type)).length > 0) {
				//there is a mimetype for file so let's check against are rules
				if( allowMime && !allowMime.test(type) ) {
					//mimeType is not matching whitelist, so drop it
					if(!('mime' in error_list)) error_list['mime'] = [];
					error_list['mime'].push(filename);
					continue;
				}
				else if( denyMime && denyMime.test(type) ) {
					//mimeType is matching blacklist, so drop it
					if(!('mime' in error_list)) error_list['mime'] = [];
					error_list['mime'].push(filename);
					continue;
				}
			}

			if( maxSize && file.size > maxSize ) {
				//file size is not acceptable
				if(!('size' in error_list)) error_list['size'] = [];
				error_list['size'].push(filename);
				continue;
			}

			safe_files.push(file)
		}
		

		
		if(safe_files.length == files.length) return files;//return original file list if all are valid

		/////////
		var error_count = {'ext': 0, 'mime': 0, 'size': 0}
		if( 'ext' in error_list ) error_count['ext'] = error_list['ext'].length;
		if( 'mime' in error_list ) error_count['mime'] = error_list['mime'].length;
		if( 'size' in error_list ) error_count['size'] = error_list['size'].length;
		
		var event
		this.$element.trigger(
			event = new $.Event('file.error.ace'), 
			{
				'file_count': files.length,
				'invalid_count' : files.length - safe_files.length,
				'error_list' : error_list,
				'error_count' : error_count,
				'dropped': dropped
			}
			);
		if ( event.isDefaultPrevented() ) return -1;//it will reset input
		//////////

		return safe_files;//return safe_files
	}



	///////////////////////////////////////////
	$.fn.aceFileInput = $.fn.ace_file_input = function (option,value) {
		var retval;

		var $set = this.each(function () {
			var $this = $(this);
			var data = $this.data('ace_file_input');
			var options = typeof option === 'object' && option;

			if (!data) $this.data('ace_file_input', (data = new Ace_File_Input(this, options)));
			if (typeof option === 'string') retval = data[option](value);
		});

		return (retval === undefined) ? $set : retval;
	};


	$.fn.ace_file_input.defaults = {
		style: false,
		no_file: 'Pas de fichier ...',
		no_icon: 'fa fa-upload',
		btn_choose: 'Choisir',
		btn_change: 'Changer',
		icon_remove: 'fa fa-times',
		container_width : 'col-xs-12 col-sm-6' ,
		droppable: false,
		thumbnail: false,//large, fit, small
		
		allowExt: null,
		denyExt: null,
		allowMime: null,
		denyMime: null,
		maxSize: false,
		value_field: null,
		
		//callbacks
		before_change: null,
		before_remove: function() {
			            if(upload_in_progress)
			            return false;//if we are in the middle of uploading a file, don't allow resetting file input
						return true;
			                         
							
					},

		preview_error: function(filename , code) {
						//code = 1 means file load error
						//code = 2 image load error (possibly file is not an image)
						//code = 3 preview failed
					}
	}


})(window.jQuery);
//Cal Uploder
function fliupld(lechamps, asize,  type, value, edit) {
    var file_input = $('#' + lechamps);
	var upload_in_progress = false;
	var $allowExt = $allowMime = $value = $value_input = null;
	if(type === undefined){
						$allowExt =  null;
					    $allowMime = null; 

	}else if(type == 'image'){
						$allowExt =  ["jpeg", "jpg", "png", "gif"];
					    $allowMime =  ["image/jpg", "image/jpeg", "image/png", "image/gif"];

	}else if(type == 'pdf'){
						$allowExt =  ["pdf"];
					    $allowMime =  ["application/pdf"];

	}else if(type == 'doc'){
						$allowExt =  ["pdf", "doc", "docx", "xls", "xlsx", "txt"];
					    $allowMime =  [
					                   "application/pdf", "application/msword",
					                   "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
					                   "application/vnd.ms-excel",
					                   "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
					                   "text/plain",
					                  ];
	}

	
	$value =  value === undefined ? "" : value;
	
	if($value != "" && edit == 1){
         $value_input = file_input.attr('value');
	}
	
	file_input.ace_file_input({
		//Style:           'well',
		//btn_choose:      'Choisir un fichier',
		//btn_change:      null,
		droppable:         true,
		value_field:       $value,
		value_input:       $value_input,
		thumbnail:         'large',
		//container_width: 'container_width class',
		maxSize:           asize,//bytes
		allowExt:          $allowExt, 
		allowMime:         $allowMime, 
		type_file:         type,
		is_edit:           edit,
		field:             lechamps,

		before_remove: function() {
			if(upload_in_progress)
				return false;//if we are in the middle of uploading a file, don't allow resetting file input
				return true;
		},

		preview_error: function(filename , code) {
						//code = 1 means file load error
						//code = 2 image load error (possibly file is not an image)
						//code = 3 preview failed
					}
				})
	file_input.on('file.error.ace', function(ev, info) {

		if(info.error_count['ext'] || info.error_count['mime']){
			ajax_loadmessage('Le type de fichier est non autorisé! <br><b>'+ $allowExt,'nok');
			return;
		} 
		if(info.error_count['size']){
			ajax_loadmessage('La taille de fichier ne doit pas dépasser ' + parseInt(asize / 781) +' Kb !','nok');
			return;
		} 
		file_input.ace_file_input('reset_input');
	});
	var ie_timeout = null;//a time for old browsers uploading via iframe
	file_input.on('change', function(e) {
		e.preventDefault();
		var files = file_input.data('ace_input_files');
		if( !files || files.length == 0 ) return false;//no files selected
		var deferred ;
		if( "FormData" in window ) {
			formData_object = new FormData();//create empty FormData object
			var field_name = file_input.attr('name');
							//for fields with "multiple" file support, field name should be something like `myfile[]`
			var files = $(this).data('ace_input_files');
			if(files && files.length > 0) {
				for(var f = 0; f < files.length; f++) {
					formData_object.append(field_name,  files[f]);
					formData_object.append("fileID", lechamps);
					formData_object.append("upld", 1);
				}
			}
			upload_in_progress = true;
			file_input.ace_file_input('loading', true);
			deferred = $.ajax({
							url: './?_tsk=upload&ajax=1',
							type: 'POST',
							processData: false,//important
							contentType: false,//important
							dataType: 'JSON',
							data: formData_object ,
						})
		            }
					//deferred callbacks, triggered by both ajax and iframe solution
					deferred
					.done(function(result) {//success
					if(result['path']){
						file_input.ace_file_input('disable', true);	
						$('#'+lechamps+'-id').attr('value', result['path']);
						file_input.parent().find('.btn_remove').attr('field', lechamps).attr('rel', 't');
					}else{
						ajax_loadmessage(result['message'],'nok');
						file_input.ace_file_input('reset_input', true);							
					}
						
					})
					.fail(function(result) {//failure
						ajax_loadmessage('Problème Upload','nok');
						//alert("There was an error ");
					})
					.always(function() {//called on both success and failure
						if(ie_timeout) clearTimeout(ie_timeout)
						ie_timeout = null;
						upload_in_progress = false;
						file_input.ace_file_input('loading', false);
					});

					deferred.promise();
				});

}