<!-- /.main-content -->
<div class="main-content">
	<div class="main-content-inner">
		<!-- #section:basics/content.breadcrumbs -->
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
			try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>

			<ul class="breadcrumb" id="treeapp">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Accueil</a>
				</li>

				
			</ul><!-- /.breadcrumb -->

			<!-- #section:basics/content.searchbox -->
			
				
			
			<div class="nav-search" id="nav-search">
				
				<form class="form-search">
					<span class="input-icon">
						<input type="text" placeholder="Recherche ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
						<i class="ace-icon fa fa-search nav-search-icon"></i>
					</span>
				</form>
				
					
			</div><!-- /.nav-search -->

			<!-- /section:basics/content.searchbox -->
		</div>

		<!-- /section:basics/content.breadcrumbs -->
		<div class="page-content" >
		    <div class="row">
				<div id="content" class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<?php


					//$application = new applic;
					//$application->load(2,'tdb');
					$execute_app = new MAjax();
                    $execute_app->is_appli = true;
                    $execute_app->default_app = Mreq::tg('_tsk') != "0"?Mreq::tg('_tsk'):'tdb';
                    $execute_app->load();

					?>
					<!-- PAGE CONTENT ENDS -->
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->




