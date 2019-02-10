$(function() {

	/**********************************************
	 * carte Google Maps
	 lien util
	http://www.coordonnees-gps.fr/carte/pays/TD
	**********************************************/

	
	
	function setMarkers(map,locations){
		var tab = [];
		for(var i=0; i<locations.length; i++){
			var station = locations[i];
			var myLatLng = new google.maps.LatLng(station['marker_latitude'], station['marker_longitude']);
			var infoWindow = new google.maps.InfoWindow();
			var image = 'common/images/marker-map/'+station['icone_icon']+'.png';
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
						+"<p class='texte'> Position : 1.336654  <p>"
						+"<p class='texte'> Fréquence: 5.2Ghz <p>"
						+"</div>"
						);
					infoWindow.open(map,this);
				});
			})(i);


   // cyrcle pr chaque marke
   var cyrcle  = new google.maps.Circle({
   	center:myLatLng,
   	radius:35000,
   	strokeColor:"#00FFFF",
   	strokeOpacity:0.5,
   	strokeWeight:2,
   	fillColor:"#045FB4",
   	fillOpacity:0.4
   });

   cyrcle.setMap(map);

}


		// ligne entre marke
		var flightPath = new google.maps.Polyline({
			path:tab,
			strokeColor: "#DF01A5",
			strokeOpacity:0.4 ,
			strokeWeight:5,
			fillColor:"#610B4B",
			fillOpacity:0.4      
		});

		flightPath.setMap(map);


	}
	google.maps.event.addDomListener(window, 'load', initialize);
});