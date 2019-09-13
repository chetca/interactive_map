<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Карта</title>
	<script type="text/javascript" src="/bitrix/templates/maps/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?apikey=7f26106a-6774-4c8e-a8c7-8062079b4a69&lang=ru_RU"></script>
	<script type="text/javascript">
		$(document).ready(function() {	
			var myMap;
			ymaps.ready(init);
			function init(){     
				myMap = new ymaps.Map("myMapId", {
					center: [51.83500727, 107.60193161],
					type: 'yandex#map',
					zoom: 14,
					controls: ['fullscreenControl', 'geolocationControl', 'zoomControl']
				}, {
					minZoom: 11
				});
				var searchControl = new ymaps.control.SearchControl({
					options: {
						provider: 'yandex#search'
					}
				});
				myMap.controls.add(searchControl);
				//alert("1");
				console.log(parent.tabControl_form);
				var typeGL = parent.tabControl_form.PROPtype.value;
				//alert("2");
				if (typeGL){
					if (typeGL == 514 || typeGL == 531){
						var lon = parent.tabControl_form.PROPlon.value;
						var lat = parent.tabControl_form.PROPlat.value;
						var myGeoObject = new ymaps.GeoObject({
							geometry: {
								type: "Point",
								coordinates: [lon, lat]
							},
						}, {
							strokeColor: "#FFFF00",
							strokeWidth: 5,
						});
						myMap.geoObjects.add(myGeoObject);
						myMap.setCenter([lon, lat]);
					}
					else if (typeGL == 515 || typeGL == 532){
						var arLat = parent.tabControl_form.PROPlat.value.split(';');
						var arLon = parent.tabControl_form.PROPlon.value.split(';');
						var pointSet = [];
						for (i=0; i<arLat.length; i++){
							pointSet[pointSet.length] = [arLon[i], arLat[i]];
						}
						var myGeoObject = new ymaps.GeoObject({
							geometry: {
								type: "LineString",
								coordinates: pointSet
							},
						}, {
							strokeWidth: 5
						});
						myMap.geoObjects.add(myGeoObject);
						myMap.setCenter(pointSet[0]);
					} else {
						var arLat = parent.tabControl_form.PROPlat.value.split(';');
						var arLon = parent.tabControl_form.PROPlon.value.split(';');
						var pointSet = [];
						for (i=0; i<arLat.length; i++){
							pointSet[pointSet.length] = [arLon[i], arLat[i]];
						}
						var myGeoObject = new ymaps.GeoObject({
							geometry: {
								type: "Polygon",
								coordinates: [pointSet]
							},
						}, {
							strokeWidth: 5
						});
						myMap.geoObjects.add(myGeoObject);
						myMap.setCenter(pointSet[0]);
					}
				}
				myMap.events.add('contextmenu', function (e) {
					var typeGL = parent.tabControl_form.PROPtype.value;
					var coords = e.get('coords');
					var lon = coords[0];
					var lat = coords[1];		
					if (typeGL){
						if (typeGL == 514 || typeGL == 531){
							window.typeGL = typeGL;
							myMap.geoObjects.removeAll();
							parent.tabControl_form.PROPlon.value = lon;
							parent.tabControl_form.PROPlat.value = lat;
							var myGeoObject = new ymaps.GeoObject({
								geometry: {
									type: "Point",
									coordinates: coords
								},
							}, {
								strokeWidth: 5
							});
							myMap.geoObjects.add(myGeoObject);
						}
						else if (typeGL == 515 || typeGL == 532){
							if (window.typeGL != typeGL){
								parent.tabControl_form.PROPlat.value = '';
								parent.tabControl_form.PROPlon.value = '';
								window.typeGL = typeGL;
							}
							if ((parent.tabControl_form.PROPlat.value)&&(parent.tabControl_form.PROPlon.value)){
								parent.tabControl_form.PROPlat.value = parent.tabControl_form.PROPlat.value + ";" + lat;
								parent.tabControl_form.PROPlon.value = parent.tabControl_form.PROPlon.value + ";" + lon;
							} else{
								parent.tabControl_form.PROPlat.value = lat;
								parent.tabControl_form.PROPlon.value = lon;
							}
							myMap.geoObjects.removeAll();
							var arLat = parent.tabControl_form.PROPlat.value.split(';');
							var arLon = parent.tabControl_form.PROPlon.value.split(';');
							var pointSet = [];
							for (i=0; i<arLat.length; i++){
								pointSet[pointSet.length] = [arLon[i], arLat[i]];
							}
							var myGeoObject = new ymaps.GeoObject({
								geometry: {
									type: "LineString",
									coordinates: pointSet
								},
							}, {
								strokeWidth: 5
							});
							myMap.geoObjects.add(myGeoObject);
						} else{
							if (window.typeGL != typeGL){
								parent.tabControl_form.PROPlat.value = '';
								parent.tabControl_form.PROPlon.value = '';
								window.typeGL = typeGL;
							}
							if ((parent.tabControl_form.PROPlat.value)&&(parent.tabControl_form.PROPlon.value)){
								parent.tabControl_form.PROPlat.value = parent.tabControl_form.PROPlat.value + ";" + lat;
								parent.tabControl_form.PROPlon.value = parent.tabControl_form.PROPlon.value + ";" + lon;
							} else{
								parent.tabControl_form.PROPlat.value = lat;
								parent.tabControl_form.PROPlon.value = lon;
							};
							myMap.geoObjects.removeAll();
							var arLat = parent.tabControl_form.PROPlat.value.split(';');
							var arLon = parent.tabControl_form.PROPlon.value.split(';');
							var pointSet = [];
							for (i=0; i<arLat.length; i++){
								pointSet[pointSet.length] = [arLon[i], arLat[i]];
							}
							var myGeoObject = new ymaps.GeoObject({
								geometry: {
									type: "Polygon",
									coordinates: [pointSet]
								},
							}, {
								strokeWidth: 5
							});
							myMap.geoObjects.add(myGeoObject);
						}
					} else {
						alert('Не выбран тип метки!');
					}
				});				
			}
		});
	</script>
	<style>
		body, div{margin:0px; padding:0px;}
	</style>
</head> 
<body>
	<div id="myMapId" style="width:648px; height:520px;"></div>
</body>
</html>