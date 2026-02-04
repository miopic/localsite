<?php
if ($this->units == UNITS_METRIC)
{
  $distance = ($record['activitytype_id'] == SWIMMING) || ($record['activitytype_id'] == OPEN_SWIM)  ? $record['distance'] : number_format($record['kilometers'], 2) ;
  $elevation = $record['activitytype_id'] == SWIMMING ? '&nbsp;' : round($record['total_elevation_gain'],0);

} else
{
  $distance = ($record['activitytype_id'] == SWIMMING) || ($record['activitytype_id'] == OPEN_SWIM) ? number_format($record['yards']) : number_format($record['miles'],2) ;
  if ($record['activitytype_id'] == SWIMMING)
  { 
    $elevation =  '&nbsp;';
  } else
  {
      $elevation = !is_null($record['total_elevation_gain_feet']) ? round($record['total_elevation_gain_feet'],0) : 0;
  }
}
 $stravaLink = '';
  if ($record['activity_id'] != '')
  {
    $stravaLink = $this->html->link(''.$this->html->image(LOCATION_LOGOS.'strava.png', array('height'=>20, 'width'=>20,'style'=>'float:center')).'','https://www.strava.com/activities/'.$record['activity_id'], array('escape'=>false, 'target'=>'_blank'));
  }

?>
<table class="table ">
  <thead>
    <tr class="table-primary fs-6">
      <td class="text-left">City/Town</td>
      <td class="text-center">Activity</td>
      <td class="text-center">Moving</td>
      <td class="text-center"><img src="/img/icons/icon-distance.png" height="20" width="20"></td>
      <td class="text-center"><img src="/img/icons/icon-speed.png" height="20" width="20"></td>
      <td class="text-center"><img src="/img/icons/icon-stopwatch.png" height="20" width="20"></td>
      
      <td class="text-center"><img src="/img/icons/icon-mountain.png" height="20" width="20"></td>
      <td class="text-center"><img src="/img/icons/icon-calories.png" height="20" width="20"></td>
      <td class="text-center">Avg HR</td>  
      <td class="text-center">Max HR</td>  
      <?php
      if ($stravaLink != "")
      {
      ?>
          <td class="text-center">&nbsp;</td> 
      <?php
      }
      ?>
    </tr>
  </thead>
  <tbody>
      <tr class="fs-6  table-success">
        <td class="text-left"><?=$record['start_locality']?></td>

        <td class="text-center"><?=$iconInTitle?></td>
        <td class="text-center"><?=$this->stopwatch->elapsed($record['moving_time'], array('decimals'=>0))?></td>

        <td class="text-center"><?=$distance?></td>
        <td class="text-center"><?=round($record['mph'], 2)?></td>
        <td class="text-center"><?=$this->stopwatch->elapsed($record['pace'])?></td>
        
        <td class="text-center"><?=$elevation?></td>
        <td class="text-center"><?=$record['calories']?></td>
        <td class="text-center"><?=round($record['average_heartrate'],0)?></td>
        <td class="text-center"><?=round($record['max_heartrate'],0)?></td>
        <?php
        if ($stravaLink != "")
        {
        ?>
          <td class="text-center"><?=$stravaLink?></td> 
        <?php
        }
        ?>
      </tr>
  </tbody>
</table>