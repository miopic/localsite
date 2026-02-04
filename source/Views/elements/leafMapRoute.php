<?php
//$centerLat = $record['start_lat'];
//$centerLng = $record['start_lng'];

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
  $centerLat = ($maxLat - $minLat) / 2;
  $centerLng = ($maxLng - $minLng) / 2;

  $bounds = array(array("lat"=>$minLat, "lng"=>$minLng),array("lat"=>$minLat, "lng"=>$minLng));
  $bounds = json_encode($bounds);
  $scrollWheelZoom = isset($scrollWheelZoom) ? $scrollWheelZoom : 'true';
  $scrollWheelZoom = $scrollWheelZoom ? 'true' : 'false';

  $zoomControl = isset($zoomControl) ? $zoomControl : 'true';
  $zoomControl = $zoomControl ? 'true' : 'false';
  $dragging = isset($dragging) ? $dragging : 'true';
  $dragging = $dragging ? 'true' : 'false';
  $pathColor  = isset($pathColor) ? $pathColor : 'DarkBlue';
  $jsData = json_encode($points);

  $mapDiv = isset($mapDiv) ? $mapDiv : 'tempmap';
  $height = isset($height) ? $height: '65%';
  $width = isset($width) ? $width: '100%';
  $zoom = isset($zoom) ? $zoom : 8;
  $centerLat = isset($centerLat) ? $centerLat : DEFAULT_LATITUDE;
  $centerLng = isset($centerLng) ? $centerLng : DEFAULT_LONGITUDE;
  $startLat = isset($startLat) ? $startLat : DEFAULT_LATITUDE;
  $startLng = isset($startLng) ? $startLng : DEFAULT_LONGITUDE;
  $endLat = isset($endLat) ? $endLat : DEFAULT_LATITUDE;
  $endLng = isset($endLng) ? $endLng : DEFAULT_LONGITUDE;
?>

  <style>
#<?=$mapDiv?>
  {
    height:400px;
    width:100%;
  }
  </style>

	<div class="text-justify-center" id="<?=$mapDiv?>"></div>


    <script>
    var map = L.map('<?=$mapDiv?>').setView([<?=$centerLat?>, <?=$centerLng?>], 13);
    var latlng = <?=$jsData?>;
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([<?=$startLat?>, <?=$startLng?>]).addTo(map);
    var marker = L.marker([<?=$endLat?>, <?=$endLng?>]).addTo(map);
    var polyline = L.polyline(latlng, {color: '<?=$pathColor?>'}).addTo(map);

    // zoom the map to the polyline
    map.fitBounds(polyline.getBounds());

    </script>
