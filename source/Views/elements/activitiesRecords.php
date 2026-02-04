<?php
$distanceDecimals = isset($distanceDecimals) ? $distanceDecimals : DEFAULT_DECIMALS;
$dateFormat = isset($dateFormat) ? $dateFormat : YMDDY;
?>
<style>
  a:link
  {
    color: black
  }
  a:hover
  {
    text-decoration: underline;
    color:green
  }
  a:visited
  {
    color:black
  }
  </style>
<table class="table table-striped table-hover">
  <thead class="">
    <tr style="border-top:2px solid black; border-bottom:2px solid black">
      <td>Date</td>
      <td>City/Town</td>
      <td class="text-center">Activity</td>
      <td  class="text-left" >Name</td>
      <td class="text-end">Moving</td>
      <td class="text-end" type="icon">
        <img src="/img/icons/icon-distance.png" height="20" width="20">
      </td>
      <td class="text-end" type="icon">
        <img src="/img/icons/icon-speed.png" height="20" width="20">
      </td>
      <td class="text-end" type="icon">
        <img src="/img/icons/icon-stopwatch.png" height="20" width="20">
      </td>
      <td class="text-end" type="icon">
        <img src="/img/icons/icon-mountain.png" height="20" width="20">
      </td>
      <td class="text-end" type="icon"><img src="/img/icons/icon-calories.png" height="20" width="20">
      </td>
    </tr>
  </thead>
  <tbody>

      <?php

      foreach ($recordList as $id=>$r)
      { 
        $distanceDecimals = $r['activitytype_id'] == SWIMMING ? 0 : 2;
        $distance = $r['activitytype_id'] == SWIMMING ? $r['yards'] : $r['miles'];
      ?>
        <tr style="font-weight:normal; border-bottom:1px solid black; border-top:1px solid black">
          <td class="text-left" ><?=$this->html->link(date($dateFormat, strtotime($r['start_date_local'])), APP_NAME.'/activities/view/'.$r['id'])?></td>
          <td class="text-left" ><?=$r['start_locality']?></td>
          <td class="text-center" ><?=isset($types[$r['activitytype_id']]['image']) ? $this->html->image(LOCATION_TYPEIMAGES.$types[$r['activitytype_id']]['image'],array('height'=>'20', 'width'=>'20')) : '&nbsp;'?></td>
          <td class="text-left" ><?=$r['name']?></td>
          <td class="text-end" ><?=isset($r['moving_time']) ? $this->stopwatch->elapsed($r['moving_time'],array('decimals'=>0)) : 0?></td>
          <td class="text-end" ><?=number_format($distance, $distanceDecimals)?></td>
          <td class="text-end" ><?=number_format($r['mph'], $distanceDecimals)?></td>
          <td class="text-end" ><?=$this->stopwatch->elapsed($r['pace'])?></td>
          <td class="text-end" ><?=isset($r['total_elevation_gain_feet']) ? number_format($r['total_elevation_gain_feet'], 0) : 0?></td>
          <td class="text-end" ><?=isset($r['calories']) ? number_format($r['calories'], 0) : 0?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
  