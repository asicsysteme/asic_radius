<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="common/js/bootstrap.min.js"></script>
	


<script type="text/javascript" src="http://maps.google.com/maps/api/js?key= AIzaSyB0EANeXUKC5TlGrXH0xs_KImjXslP6THs &exp&sensor=false&libraries=places"></script>
 
<script type="text/javascript">
function initialize(){
var myLatLng = new google.maps.LatLng(<?php echo (!empty($lat)) ? $lat : '12.087083'; ?>,<?php echo (!empty($lng)) ? $lng : '15.0148322'; ?>	);

var mapOptions={
	zoom: <?php echo (!empty($zoom)) ? $zoom : '12'; ?>,
	center: myLatLng,
	mapTypeId: google.maps.MapTypeId.HYBRID,
	streetViewControl: true,
	styles: [{"elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"road","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","stylers":[{"visibility":"off"}]}]
	/*styles: [{"elementType":"labels","stylers":[{"visibility":"off"},{"color":"#f49f53"}]},{"featureType":"landscape","stylers":[{"color":"#fff"},{"lightness":-7}]},{"featureType":"road","stylers":[{"color":"#813033"},{"lightness":43}]},{"featureType":"poi.business","stylers":[{"color":"#645c20"},{"lightness":38}]},{"featureType":"water","stylers":[{"color":"#1994bf"},{"saturation":-69},{"gamma":0.99},{"lightness":43}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#f19f53"},{"weight":1.3},{"visibility":"on"},{"lightness":16}]},{"featureType":"poi.business"},{"featureType":"poi.park","stylers":[{"color":"#645c20"},{"lightness":39}]},{"featureType":"poi.school","stylers":[{"color":"#a95521"},{"lightness":35}]},{},{"featureType":"poi.medical","elementType":"geometry.fill","stylers":[{"color":"#813033"},{"lightness":38},{"visibility":"off"}]},{},{},{},{},{},{},{},{},{},{},{},{"elementType":"labels"},{"featureType":"poi.sports_complex","stylers":[{"color":"#9e5916"},{"lightness":32}]},{},{"featureType":"poi.government","stylers":[{"color":"#9e5916"},{"lightness":46}]},{"featureType":"transit.station","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","stylers":[{"color":"#813033"},{"lightness":22}]},{"featureType":"transit","stylers":[{"lightness":38}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"color":"#f19f53"},{"lightness":-10}]},{},{},{}]*/


},





map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
setMarkers(map,marker);
}
//Markers setting
function setMarkers(map,locations){
	var tab = [];
	for(var i=0; i<locations.length; i++){
		var station = locations[i];
		var myLatLng = new google.maps.LatLng(station['marker_latitude'], station['marker_longitude']);
		var infoWindow = new google.maps.InfoWindow();
		var image = 'common/images/marker-map/map-icon/'+station['icone_icon']+'.png';
		var radius = <?php if(!empty ( $raduis)) { echo  $raduis ; }   else { ;?> 
                  station['marker_radius'];  
		<?php	
		} ;?> 
		var latitude = station['marker_latitude'];
		var longitude = station['marker_longitude'];
		var line_lat = station['marker_line_lat']; 
		var line_long = station['marker_line_long']; 
		
		tab.push(myLatLng);
		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map,
			icon: image,
			title: station['marker_ville'],
				//animation:google.maps.Animation.BOUNCE
			});

		(function(i){
			google.maps.event.addListener(marker, "click",function(){
				var station = locations[i];
				infoWindow.close();
				infoWindow.setContent(
					"<div id='infoWindow'>"
					+'<img width="148" height="100" alt="no image" src="common/images/marker-clusterer/m5.png" style ="float:right;">'
					+"<p class='texte'> Site  : "+ station['marker_text'] +"<p>"
					+"<p class='texte'> Radius  : "+ station['marker_radius'] +"<p>"
					+"<p class='texte'> Position : 1.336654  <p>"
					+"<p class='texte'> Fréquence: 5.2Ghz <p>"
					+"</div>"
					);
				infoWindow.open(map,this);
			});
		})(i);
		//Creat station coverage
		if($.isNumeric(radius) == true && radius != 0){
			
			var cyrcle  = new google.maps.Circle({
                center:myLatLng,
                radius:radius * 1,
                strokeColor:"#0101DF",
                strokeOpacity:0.5,
                strokeWeight:2,
                fillColor:"#045FB4",
                fillOpacity:0.4
            });
            
            cyrcle.setMap(map);
		}
		//Creat Lines between stations
		if(line_lat != null && line_long != null){
			var line = new google.maps.Polyline({
                path: [
                    new google.maps.LatLng(latitude, longitude), 
                    new google.maps.LatLng(line_lat, line_long)
                ],
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 1,
                
            });
            line.setMap(map);
		}
		
	} 

}     
//Run Script
google.maps.event.addDomListener(window, 'load', initialize);         
</script>
	<!-- <script src="common/js/maps.js"></script> -->