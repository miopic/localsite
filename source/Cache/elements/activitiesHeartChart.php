<?php
if (($record['streamHeart'] != '')  && ($record['streamDistance'] != ''))
{
      $streamDistanceArray = json_decode($record['streamDistance']);
      $streamHeartArray = json_decode($record['streamHeart']);
      $graphData = array();
      $itemCountAltitude = min(count($streamDistanceArray), count($streamHeartArray));
      $graphData[0][0] = 'distance';
      $graphData[0][1] = 'heart';

      for($k = 0; $k < $itemCountAltitude ; $k++)
      {
          $graphData[$k+1][0] = round(($streamDistanceArray[$k] / METERS_PER_KILOMETER),4);
          $graphData[$k+1][1] = $streamHeartArray[$k];
      }
      //debug($graphData);exit;

    $this->set('graphData', $graphData);
    $this->set('chartType', 'Area');
    $this->set('chartDiv', 'heartChart');
    $this->set('vAxisTitle', '');
    $this->set('height','200');
    $this->set('width','550');
    $this->set('chartAreaHeight', '70');
    $this->set('chartAreaWidth', '400');
    $this->set('chartAreaLeft', '60');
    $this->set('chartAreaTop', '20');
    $this->set('hAxisFontSize', 8);
    $this->set('verticalHLabels',false);
?>
    <div class="col-5 rounded" style="background:#EBEDEF; width:590px; height:285px;;padding:20px 20px 20px 20px"><h5 style="">Heart Rate</h5><?=$this->element('charts2')?></div>
<?php
}

?>
