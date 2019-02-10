<?php include_once("template/vueHeader.php"); ?>

  <body>

    <?php //include_once("template/vueNavbar.php"); ?>
    
    <div class="container" id="map-canvas">
       
    </div>
  
    
   
    
    <script type="text/javascript">
    	var marker = <?php echo $allMarkersJson ?>;    
    </script>

	<?php include_once("template/vueFooter.php"); ?>

	</body>
</html>