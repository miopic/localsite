 <?php
$dateField = isset($dateField) ? $dateField : 'start_date_local';
$enableDateColumn = isset($enableDateColumn) ? $enableDateColumn : true;
$grandTotalsEnabled = isset($grandTotalsEnabled) ? $grandTotalsEnabled : true;
$distanceDecimals = isset($distanceDecimals) ? $distanceDecimals : 2;
$grandTotals = array(
  'count'=>0,
  'moving_time'=>0,
  'miles'=>0,
  'total_elevation_gain'=>0,
  'calories'=>0,
  'kudos_count'=>0,
  'achievement_count'=>0

);
if (count($totalsList) > 0)
{
  if ($grandTotalsEnabled)
  {
    	foreach($totalsList as $typeID=>$totals)
    	{
        $grandTotals['count'] += isset($totals['count']) ? $totals['count'] : 0;
        $grandTotals['moving_time'] += $totals['moving_time'];
        $grandTotals['miles'] += $totals['miles'];
        $grandTotals['total_elevation_gain'] += $totals['total_elevation_gain'];
        $grandTotals['calories'] += $totals['calories'];
    	}
      $grandTotals['mph'] = $grandTotals['miles'] / ($grandTotals['moving_time'] / HOUR);
      $grandTotals['pace'] = $grandTotals['moving_time'] /$grandTotals['miles'];
  }
 
  ?>



    <table class="table table-striped table-hover">
      <thead class="table-success">
        <tr style="border-top:2px solid black; border-bottom:2px solid black">
          <?php
          if ($enableDateColumn)
          {
          ?>
            <th>Date</th>
          <?php
          }
          ?>
          <th class="text-center">Activity</th>
          <th class="text-center">Count</th>
          <th class="text-end">Moving</th>
          <th class="text-end" type="icon">
            <img src="/img/icons/icon-distance.png" height="20" width="20">
          </th>
          <th class="text-end" type="icon">
            <img src="/img/icons/icon-speed.png" height="20" width="20">
          </th>
          <th class="text-end" type="icon">
            <img src="/img/icons/icon-stopwatch.png" height="20" width="20">
          </th>
          <th class="text-center" type="icon">
            <img src="/img/icons/icon-mountain.png" height="20" width="20">
          </th>
          <th class="text-center" type="icon"><img src="/img/icons/icon-calories.png" height="20" width="20">
          </th>
         
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($totalsList as $id=>$details)
        {
        $distance = number_format($details['miles'],$distanceDecimals);
          $details['total_elevation_gain'] = is_null($details['total_elevation_gain']) || ($details['total_elevation_gain'] == '') || ($details['total_elevation_gain'] == 0)
            ? 0 : $details['total_elevation_gain'];
        ?>
          <tr style="font-weight:normal; border-bottom:1px solid black; border-top:1px solid black">
            <?php
            if ($enableDateColumn)
            {
              switch ($dateField)
              {
                case "year":
                  $details[$dateField] = date("Y",strtotime($details[$dateField]));
                break;
                case "month":
                  $details[$dateField] = date("Y-M",strtotime($details[$dateField]));
                break;
                default:
                break;
              }
            ?>
              <td class="text-left" style="font-weight:normal"><?=$details[$dateField]?></td>
            <?php
            }
            ?>
            <td class="text-center" style="font-weight:normal"><?=isset($types[$details['activitytype_id']]['image']) ? $this->html->image(LOCATION_TYPEIMAGES.$types[$details['activitytype_id']]['image'],array('height'=>'20', 'width'=>'20')) : '&nbsp;'?></td>
            <td class="text-center" style="font-weight:normal"><?=isset($details['count']) && ($details['count'] > 0)? number_format($details['count'],0) : 1?></td>
            <td class="text-end" style="font-weight:normal"><?=isset($details['moving_time']) ? $this->stopwatch->elapsed($details['moving_time'],array('decimals'=>0)) : 0?></td>
            <td class="text-end" style="font-weight:normal"><?=$distance?></td>

            <td class="text-end" style="font-weight:normal"><?=round($details['mph'],1)?></td>
            <td class="text-end" style="font-weight:normal"><?=$this->stopwatch->elapsed($details['pace'])?></td>
            <td class="text-center" style="font-weight:normal"><?=number_format($details['total_elevation_gain'],0)?></td>
            <td class="text-center" style="font-weight:normal"><?=isset($details['calories']) ? number_format($details['calories'], 0) : 0?></td>
      
          </tr>
        <?php
        }
        if ($grandTotalsEnabled)
        {
            $distance = number_format($grandTotals['miles'],$this->stopwatch->howmanydecimals($grandTotals['miles']));


          ?>
          <tr style="font-weight:normal; border-top:2px solid black; border-bottom:2px solid black">
            <?php
            if ($enableDateColumn)
            {
            ?>
              <td class="text-left" style="font-weight:normal; border-bottom:none">&nbsp;</td>
            <?php
            }
            ?>
            <td class="text-center" style="font-weight:normal">TOTALS</th>
            <td class="text-center" style="font-weight:normal"><?=$grandTotals['count']?></th>
            <td class="text-end" style="font-weight:normal"><?=$this->stopwatch->elapsed($grandTotals['moving_time'])?></td>
            <td class="text-end" style="font-weight:normal"><?=$distance?></td>
            <td class="text-end" style="font-weight:normal"><?=number_format($grandTotals['mph'], 1)?></td>
            <td class="text-end" style="font-weight:normal"><?=$this->stopwatch->elapsed($grandTotals['pace'])?></td>
            <td class="text-center" style="font-weight:normal"><?=number_format($grandTotals['total_elevation_gain'],0)?></td>
            <td class="text-center" style="font-weight:normal"><?=number_format($grandTotals['calories'],0)?></td>
        
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
<?php
}
?>
