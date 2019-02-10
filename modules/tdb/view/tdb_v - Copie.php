<div class="page-header">
	<h1>
		Widgets1
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			Draggabble Widget Boxes &amp; Containers
		</small>
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
			<div class="col-xs-12 col-sm-6 widget-container-col">
				<!-- #section:custom/widget-box -->
				<div class="widget-box">
					<div class="widget-header">
						<h5 class="widget-title">Default Widget Box</h5>

						<!-- #section:custom/widget-box.toolbar -->
						<div class="widget-toolbar">
							<div class="widget-menu">
								<a href="#" data-action="settings" data-toggle="dropdown">
									<i class="ace-icon fa fa-bars"></i>
								</a>

								<ul class="dropdown-menu dropdown-menu-right dropdown-light-blue dropdown-caret dropdown-closer">
									<li>
										<a data-toggle="tab" href="#dropdown1">Option#1</a>
									</li>

									<li>
										<a data-toggle="tab" href="#dropdown2">Option#2</a>
									</li>
								</ul>
							</div>

							<a href="#" data-action="fullscreen" class="orange2">
								<i class="ace-icon fa fa-expand"></i>
							</a>

							<a href="#" data-action="reload">
								<i class="ace-icon fa fa-refresh"></i>
							</a>

							<a href="#" data-action="collapse">
								<i class="ace-icon fa fa-chevron-up"></i>
							</a>

							<a href="#" data-action="close">
								<i class="ace-icon fa fa-times"></i>
							</a>
						</div>

						<!-- /section:custom/widget-box.toolbar -->
					</div>

					<div class="widget-body">
						<div class="widget-main">
							<p class="alert alert-info">
								Nunc aliquam enim ut arcu aliquet adipiscing. Fusce dignissim volutpat justo non consectetur. Nulla fringilla eleifend consectetur.
							</p>
							<p class="alert alert-success">
								Raw denim you probably haven't heard of them jean shorts Austin.
							</p>
						</div>
					</div>
				</div>

				<!-- /section:custom/widget-box -->
			</div>

			<div class="col-xs-12 col-sm-6 widget-container-col">
				<div class="widget-box widget-color-blue">
					<!-- #section:custom/widget-box.options -->
					<div class="widget-header">
						<h5 class="widget-title bigger lighter">
							<i class="ace-icon fa fa-table"></i>
							Tables & Colors
						</h5>

						<div class="widget-toolbar widget-toolbar-light no-border">
							<select id="simple-colorpicker-1" class="hide">
								<option selected="" data-class="blue" value="#307ECC">#307ECC</option>
								<option data-class="blue2" value="#5090C1">#5090C1</option>
								<option data-class="blue3" value="#6379AA">#6379AA</option>
								<option data-class="green" value="#82AF6F">#82AF6F</option>
								<option data-class="green2" value="#2E8965">#2E8965</option>
								<option data-class="green3" value="#5FBC47">#5FBC47</option>
								<option data-class="red" value="#E2755F">#E2755F</option>
								<option data-class="red2" value="#E04141">#E04141</option>
								<option data-class="red3" value="#D15B47">#D15B47</option>
								<option data-class="orange" value="#FFC657">#FFC657</option>
								<option data-class="purple" value="#7E6EB0">#7E6EB0</option>
								<option data-class="pink" value="#CE6F9E">#CE6F9E</option>
								<option data-class="dark" value="#404040">#404040</option>
								<option data-class="grey" value="#848484">#848484</option>
								<option data-class="default" value="#EEE">#EEE</option>
							</select>
						</div>
					</div>

					<!-- /section:custom/widget-box.options -->
					<div class="widget-body">
						<div class="widget-main no-padding">
							<table class="table table-striped table-bordered table-hover">
								<thead class="thin-border-bottom">
									<tr>
										<th>
											<i class="ace-icon fa fa-user"></i>
											User
										</th>

										<th>
											<i>@</i>
											Email
										</th>
										<th class="hidden-480">Status</th>
									</tr>
								</thead>

								<tbody>
									<tr>
										<td class="">Alex</td>

										<td>
											<a href="#">alex@email.com</a>
										</td>

										<td class="hidden-480">
											<span class="label label-warning">Pending</span>
										</td>
									</tr>

									<tr>
										<td class="">Fred</td>

										<td>
											<a href="#">fred@email.com</a>
										</td>

										<td class="hidden-480">
											<span class="label label-success arrowed-in arrowed-in-right">Approved</span>
										</td>
									</tr>

									<tr>
										<td class="">Jack</td>

										<td>
											<a href="#">jack@email.com</a>
										</td>

										<td class="hidden-480">
											<span class="label label-warning">Pending</span>
										</td>
									</tr>

									<tr>
										<td class="">John</td>

										<td>
											<a href="#">john@email.com</a>
										</td>

										<td class="hidden-480">
											<span class="label label-inverse arrowed">Blocked</span>
										</td>
									</tr>

									<tr>
										<td class="">James</td>

										<td>
											<a href="#">james@email.com</a>
										</td>

										<td class="hidden-480">
											<span class="label label-info arrowed-in arrowed-in-right">Online</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div><!-- /.span -->
		</div><!-- /.row -->
		<?php $sarot = 'mGqjJ1rRBNcOv33juhBnBDA5/Vd4A5lkZNxMSDw7tDg=';

echo MInit::cryptage($sarot,0);?>
		<!-- PAGE CONTENT END -->
	</div>
</div>
<script type="text/javascript">

			jQuery(function($) {

				
			
				$('#simple-colorpicker-1').ace_colorpicker({pull_right:true}).on('change', function(){
					var color_class = $(this).find('option:selected').data('class');
					var new_class = 'widget-box';
					if(color_class != 'default')  new_class += ' widget-color-'+color_class;
					$(this).closest('.widget-box').attr('class', new_class);
				});
			
			
				// scrollables
				$('.scrollable').each(function () {
					var $this = $(this);
					$(this).ace_scroll({
						size: $this.attr('data-size') || 100,
						//styleClass: 'scroll-left scroll-margin scroll-thin scroll-dark scroll-light no-track scroll-visible'
					});
				});
				$('.scrollable-horizontal').each(function () {
					var $this = $(this);
					$(this).ace_scroll(
					  {
						horizontal: true,
						styleClass: 'scroll-top',//show the scrollbars on top(default is bottom)
						size: $this.attr('data-size') || 500,
						mouseWheelLock: true
					  }
					).css({'padding-top': 12});
				});
				
				$(window).on('resize.scroll_reset', function() {
					$('.scrollable-horizontal').ace_scroll('reset');
				});
			
				
				$('#id-checkbox-vertical').prop('checked', false).on('click', function() {
					$('#widget-toolbox-1').toggleClass('toolbox-vertical')
					.find('.btn-group').toggleClass('btn-group-vertical')
					.filter(':first').toggleClass('hidden')
					.parent().toggleClass('btn-toolbar')
				});
			
				/**
				//or use slimScroll plugin
				$('.slim-scrollable').each(function () {
					var $this = $(this);
					$this.slimScroll({
						height: $this.data('height') || 100,
						railVisible:true
					});
				});
				*/
				
			
				/**$('.widget-box').on('setting.ace.widget' , function(e) {
					e.preventDefault();
				});*/
			
				/**
				$('.widget-box').on('show.ace.widget', function(e) {
					//e.preventDefault();
					//this = the widget-box
				});
				$('.widget-box').on('reload.ace.widget', function(e) {
					//this = the widget-box
				});
				*/
			
				//$('#my-widget-box').widget_box('hide');
			
				
			
				// widget boxes
				// widget box drag & drop example
			    $('.widget-container-col').sortable({
			        connectWith: '.widget-container-col',
					items:'> .widget-box',
					handle: ace.vars['touch'] ? '.widget-header' : false,
					cancel: '.fullscreen',
					opacity:0.8,
					revert:true,
					forceHelperSize:true,
					placeholder: 'widget-placeholder',
					forcePlaceholderSize:true,
					tolerance:'pointer',
					start: function(event, ui) {
						//when an element is moved, it's parent becomes empty with almost zero height.
						//we set a min-height for it to be large enough so that later we can easily drop elements back onto it
						ui.item.parent().css({'min-height':ui.item.height()})
						//ui.sender.css({'min-height':ui.item.height() , 'background-color' : '#F5F5F5'})
					},
					update: function(event, ui) {
						ui.item.parent({'min-height':''})
						alert('moved');
						//p.style.removeProperty('background-color');
					}
			    });
				
			
			
			});
		</script>