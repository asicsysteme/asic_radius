	<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo THEME_PATH ;?>/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo THEME_PATH ;?>/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo THEME_PATH ;?>/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo THEME_PATH ;?>/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="<?php echo THEME_PATH ;?>/js/excanvas.js"></script>
		<![endif]-->
		<script src="<?php echo THEME_PATH ;?>/js/fuelux/fuelux.wizard.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/jquery-ui.custom.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/jquery.ui.touch-punch.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/jquery.easypiechart.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/jquery.sparkline.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/flot/jquery.flot.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/flot/jquery.flot.pie.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/flot/jquery.flot.resize.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/jquery.gritter.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/bootbox.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/dataTables/jquery.dataTables.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/dataTables/jquery.dataTables.bootstrap.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/jquery.autocomplete.js"></script>

		<!-- ace scripts -->
		<script src="<?php echo THEME_PATH ;?>/js/ace/elements.scroller.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/elements.colorpicker.js"></script>
		
		<script src="<?php echo THEME_PATH ;?>/js/date-time/bootstrap-datepicker.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/date-time/bootstrap-timepicker.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/bootstrap-tag.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/chosen.jquery.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/date-time/moment.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/date-time/daterangepicker.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/date-time/bootstrap-datetimepicker.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/bootstrap-colorpicker.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/elements.fileinput.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/elements.typeahead.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/summernote/dist/summernote.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/elements.wysiwyg.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/elements.spinner.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/elements.treeview.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/elements.wizard.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/x-editable/bootstrap-editable.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/x-editable/ace-editable.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/jquery.validate.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/elements.aside.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.ajax-content.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.touch-drag.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.sidebar.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.submenu-hover.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/jquery.fileDownload.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.widget-box.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.settings.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.settings-rtl.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.settings-skin.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/jquery.colorbox.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/jquery.maskedinput.js"></script>
		<?php if(session::get('userid') AND Mcfg::get('deploy') == true){?>
		<script src="<?php echo THEME_PATH ;?>/js/notify.js"></script>	
		<?php } ?>
		
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?php echo THEME_PATH ;?>/js/ace/ace.searchbox-autocomplete.js"></script>

		<script src="<?php echo THEME_PATH ;?>/js/jquery.fullscreen-min.js"></script>
        <?php  $mrn_js = Mcfg::get('deploy') == true ?'myfunction.js' : 'myfunction.js';?>
        <script src="<?php echo THEME_PATH .'/js/'. $mrn_js ?>"></script>
        <script src="<?php echo THEME_PATH ?>/js/highchart/code/highcharts.js"></script>
        <script src="<?php echo THEME_PATH ?>/js/highchart/code/modules/exporting.js"></script>
       <!--  <script src="<?php echo THEME_PATH ?>/js/highchart/code/modules/offline-exporting.js"></script> -->


<script type="text/javascript">
$(function () {

	//alert('test');
});
</script>




