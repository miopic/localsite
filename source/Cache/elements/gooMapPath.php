<?php

  $mapFloat = isset($mapFloat) ? $mapFloat : 'left';
  $startLat = $points[0]['lat'];
  $startLng = $points[0]['lng'];
  $endLat = $points[count($points)-1]['lat'];
  $endLng = $points[count($points)-1]['lng'];
  //max lat lng
  $maxLat = -361;
  $maxLng = -361;

  foreach ($points as $i=>$point)
  {
    if ($point['lat'] >= $maxLat)
    {
      $maxLat = $point['lat'];
    }
    if ($point['lng'] >= $maxLng)
    {
      $maxLng = $point['lng'];
    }
  }
  //max lat lng
  $minLat = 361;
  $minLng = 361;
  foreach ($points as $i=>$point)
  {
    if ($point['lat'] <= $minLat)
    {
      $minLat = $point['lat'];
    }
    if ($point['lng'] <= $minLng)
    {
      $minLng = $point['lng'];
    }
  }
  $bounds = array(array("lat"=>$minLat, "lng"=>$minLng),array("lat"=>$minLat, "lng"=>$minLng));
  $bounds = json_encode($bounds);
  $scrollWheelZoom = isset($scrollWheelZoom) ? $scrollWheelZoom : 'true';
  $scrollWheelZoom = $scrollWheelZoom ? 'true' : 'false';

  $zoomControl = isset($zoomControl) ? $zoomControl : 'true';
  $zoomControl = $zoomControl ? 'true' : 'false';
  $dragging = isset($dragging) ? $dragging : 'true';
  $dragging = $dragging ? 'true' : 'false';

  $jsData = json_encode($points);


  $mapDiv = isset($mapDiv) ? $mapDiv : 'tempmap';
  $height = isset($height) ? $height: '65%';
  $width = isset($width) ? $width: '100%';
  $zoom = isset($zoom) ? $zoom : 10;
  $centerLat = isset($centerLat) ? $centerLat : DEFAULT_LATITUDE;
  $centerLng = isset($centerLng) ? $centerLng : DEFAULT_LONGITUDE;
  $startLat = isset($startLat) ? $startLat : DEFAULT_LATITUDE;
  $startLng = isset($startLng) ? $startLng : DEFAULT_LONGITUDE;
  $finishLat = isset($finishLat) ? $finishLat : DEFAULT_LATITUDE;
  $finishLng = isset($finishLng) ? $finishLng : DEFAULT_LONGITUDE;
?>
	<div class="" id="<?=$mapDiv?>" style="height:100%; width:100%;background:#EBEDEF;padding:10px 10px 10px 10px; border-radius:10px"></div>
		<script>
		let map;
		async function initMap() {
      const myCenter = { "lat":  <?=$centerLat?>, "lng": <?=$centerLng?>}
      const myStart = { "lat":  <?=$startLat?>, "lng": <?=$startLng?>}
      const myMax = { "lat":  <?=$maxLat?>, "lng": <?=$maxLng?>}
      const myMin = { "lat":  <?=$minLat?>, "lng": <?=$minLng?>}
      const { Map } = await google.maps.importLibrary("maps");
			map = new google.maps.Map(document.getElementById("<?=$mapDiv?>"), {
				center: myCenter,
				zoom: <?=$zoom?>,
        mapId:'248130336f999827'
			});
      var startMarker = new google.maps.Marker(
        {position:myStart}
      )

      bounds = new google.maps.LatLngBounds(myMin, myMax);
      map.fitBounds(bounds);
      startMarker.setMap(map);
      //maxMarker.setMap(map);
      //minMarker.setMap(map);
      const activityCoordinates = <?=$jsData?>;
       const activityPath = new google.maps.Polyline({
         path: activityCoordinates,
         geodesic: true,
         strokeColor: "#FF0000",
         strokeOpacity: 1.0,
         strokeWeight: 3,
       });
      activityPath.setMap(map);
		}
		initMap();
		</script>
<br><br>
