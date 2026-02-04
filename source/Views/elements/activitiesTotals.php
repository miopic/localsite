 <?php
$dateField = isset($dateField) ? $dateField : 'start_date_local';
$enableDateColumn = isset($enableDateColumn) ? $enableDateColumn : true;
$grandTotalsEnabled = isset($grandTotalsEnabled) ? $grandTotalsEnabled : true;
$distanceDecimals = isset($distanceDecimals) ? $distanceDecimals : 1;

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
          <th class="text-end">Average<br>Moving</th>
          <th class="text-end" type="icon">
            <img src="/img/icons/icon-distance.png" height="20" width="20">
          </th>
          <th class="text-end" type="icon">
            <img src="/img/icons/icon-speed.png" height="20" width="20">
          </th>
          <th class="text-end" type="icon">
            <img src="/img/icons/icon-stopwatch.png" height="20" width="20">
          </th>
          <th class="text-end" type="icon">
            <img src="/img/icons/icon-mountain.png" height="20" width="20">
          </th>
          <th class="text-end" type="icon"><img src="/img/icons/icon-calories.png" height="20" width="20">
          </th>
         
        </tr>
      </thead>
      <tbody>
		<?php
    $totalCount = 0;
    $totalTime = 0;
    $totalDistance = 0;
    $totalAscent = 0;
    $totalCalories = 0;
   

		foreach ($totalsList as $id=>$details)
        {
        	$date = $details[$dateField];
          $totalCount += $details['count'];
          $avgTime = $this->stopwatch->elapsed($details['moving_time'] / $details['count']);
        	$count = $details['count'];

          $totalTime += $details['moving_time'];
        	$moving_time = $this->stopwatch->elapsed($details['moving_time']);
        	if ($this->units == UNITS_METRIC)
        	{
            $distance = $details['kilometers'];
        		$distance = $details['kilometers'];

        		$distance = $distance < 20? round($distance, 2) : ($distance < 100 ? round($distance, 1) : round($distance, 0)); 
            $totalAscent += $details['total_elevation_gain'];
        		$ascent = number_format($details['total_elevation_gain'],0);
        		
        	} else {
            $totalDistance += $details['miles'];
        		$distance = $details['miles'];
        		$distance = $distance < 100 ? round($distance, 2) : round($distance, 0);
            $totalAscent += $details['total_elevation_gain_feet'];
        		$ascent = is_null($details['total_elevation_gain_feet']) ? 0 : number_format(round($details['total_elevation_gain_feet'],0));
        		
        	}

        	$speed = number_format(round($this->stopwatch->speed($this->units, $details),2),2);
        	$pace = $this->stopwatch->pace($this->units, $details);

          $totalCalories += $details['calories'];
          $calories = number_format($details['calories'],0);

     	?>
        	<tr>
        		<td class="text-left" style="font-weight:normal"><?=$date?></td>
        		<td class="text-center" style="font-weight:normal"><?=isset($types[$details['activitytype_id']]['image']) ? $this->html->image(LOCATION_TYPEIMAGES.$types[$details['activitytype_id']]['image'],array('height'=>'20', 'width'=>'20')) : '&nbsp;'?></td>
        		<td class="text-center" style="font-weight:normal"><?=$count?></td>
        		<td class="text-end" style="font-weight:normal"><?=$moving_time?></td>
            <td class="text-end" style="font-weight:normal"><?=$avgTime?></td>
        		<td class="text-end" style="font-weight:normal"><?=$distance?></td>
        		<td class="text-end" style="font-weight:normal"><?=$speed?></td>
        		<td class="text-end" style="font-weight:normal"><?=$pace?></td>
            <td class="text-end" style="font-weight:normal"><?=$ascent?></td>
            <td class="text-end" style="font-weight:normal"><?=$calories?></td>
        
        	</tr>
        
        <?php
        }
        $totalSpeed = number_format($totalDistance / ($totalTime / HOUR), 2);
        $totalPace = $this->stopwatch->elapsed($totalTime / $totalDistance);
        $avgTotalTime =  $this->stopwatch->elapsed($totalTime / $totalCount);
        $totalCount = number_format($totalCount,0);
        $totalTime = $this->stopwatch->elapsed($totalTime);
        $totalDistance = number_format($totalDistance,2);
        $totalAscent = number_format($totalAscent,0);
        $totalCalories = number_format($totalCalories,0);
    
       
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
            <td class="text-center" style="font-weight:normal"><?=$totalCount?></th>
            <td class="text-end" style="font-weight:normal"><?=$totalTime?></td>
            <td class="text-end" style="font-weight:normal"><?=$avgTotalTime?></td>
            <td class="text-end" style="font-weight:normal"><?=$totalDistance?></td>
            <td class="text-end" style="font-weight:normal"><?=$totalSpeed?></td>
            <td class="text-end" style="font-weight:normal"><?=$totalPace?></td>
            <td class="text-end" style="font-weight:normal"><?=$totalAscent?></td>
            <td class="text-end" style="font-weight:normal"><?=$totalCalories?></td>
            
          </tr>
      </tbody>
</table>