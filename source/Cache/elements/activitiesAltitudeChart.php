<?php
if (($record['streamAltitude'] != '')  && ($record['streamDistance'] != ''))
{
  $streamDistanceArray = json_decode($record['streamDistance']);
  $streamAltitudeArray = json_decode($record['streamAltitude']);
  $graphData = array();
  $itemCountAltitude = min(count($streamDistanceArray), count($streamAltitudeArray));
  $graphData[0][0] = 'distance';
  $graphData[0][1] = 'altitude';

  for($k = 0; $k < $itemCountAltitude ; $k++)
  {
      $graphData[$k+1][0] = round(($streamDistanceArray[$k] / METERS_PER_KILOMETER),4);
      $graphData[$k+1][1] = $streamAltitudeArray[$k];
  }



  $this->set('chartType', 'Area');
  $this->set('chartDiv', 'altitudeChart2');
  $this->set('height','200');
  $this->set('width','550');
  $this->set('chartAreaHeight', '70');
  $this->set('chartAreaWidth', '400');
  $this->set('vAxisTitle', '');
  $this->set('chartAreaLeft', '60');
  $this->set('chartAreaTop', '20');
  $this->set('hAxisFontSize', 6);
  $this->set('vAxisFontSize', 6);
  $this->set('verticalHLabels',false);
  $this->set('graphData', $graphData);
?>
  <div class="col-5 rounded" style="background:#EBEDEF; width:590px; height:285px;;padding:20px 20px 20px 20px"><h5 style="">Altitude</h5><?=$this->element('charts2')?></div>
<?php
}
?>
