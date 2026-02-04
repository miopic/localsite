<?php
$chartsEnabled = true;

$this->set('$chartsEnabled', $chartsEnabled);
	foreach($types as $key=>$type)
	{
		$types[$key]['active'] = "";
	}
	$types[$typeID]['active'] = "active";

	$this->set('incrementDate', $increment);
	$typeList[0] = 'All';
	$metric = isset($metric) ? $metric : 'distance';

?>


<style type="text/css">
	td.day
	{
		width: 12%;
		border-spacing: 8px;
		height:124x;
	}

	.date-range
	{
		height:15px;
		font-size:11px;
		text-transform: uppercase;
		font-weight: bold;
		padding-left: 4px;
	}
	.week-duration,
	.week-elevation
	{
		font-size: 12px;
		text-align: right;
		float:right;
		fill: #666;
		padding-top: 2px;
		height:20px;
	}
	.week-duration
	{
		text-align: left;
		float:left;
			padding-left: 4px;
	}
	.week-distance-units,
	.week-distance
	{
		height:40px;
		clear:both;
		padding-top: 2px;
		padding-left: 4px;
		font-size: 31px;
    	font-weight: 300;
    	fill: #444;
	}
	.week-distance-units
	{
		font-size: 20px;
		font-weight: 300;

	}
	.week-goal
	{
		font-size: 12px;
		text-align: left;
		float:left;
		fill: #666;
		padding-top: 2px;
		height:14px;
		fill: #aaa;
	}
	a.activity-link:link
	{
		text-decoration: none;
	}
		a.activity-link:hover
	{
		color:red;
	}
</style>
<?php
		$this->set('actionStr',APP_NAME.'/activities/logbook/');
?>
<?=$this->element('activitiesTools');?>

<div class="row rounded border" style=" margin-bottom:20px; height:350px; ;padding-top:10px;">
	<?php
	$this->set('data', $data);
	$this->set('chartType', 'Column');
	$this->set('chartDiv', 'weeks');
	$this->set('vAxisTitle', $metrics[$metric]);
	echo $this->element('charts2');
	?>

</div>
<link href='/css/calendar.css' rel='stylesheet' type='text/css'>

<div id="row" style="margin-bottom: 10px; ">
	<table class="calendar">
			<thead>
				<tr class="">
					<th class="day-name left-border" style="width:12.5%">&nbsp;</th>
					<th class="day-name" style="width:12.5%">Mon</th>
					<th class="day-name" style="width:12.5%">Tue</th>
					<th class="day-name" style="width:12.5%">Wed</th>
					<th class="day-name" style="width:12.5%">Thu</th>
					<th class="day-name" style="width:12.5%">Fri</th>
					<th class="day-name" style="width:12.5%">Sat</th>
					<th class="day-name" style="width:12.5%">Sun</th>
				</tr>
			</thead>
			<tbody>
<?php

			//totals are the weekly totals without regard to type
			foreach ($totals as $index=>$weekTotal)
			{
			
				$this->stopwatch->convertToUnits($weekTotal, $this->units);
				$w = date('M d', strtotime($index)).'-';
				$toDay = date('d', strtotime($index." +6 days"));
				$w .= $toDay;
				$duration = isset($weekTotal['moving_time']) ? $weekTotal['moving_time'] : 0;
				$distance = isset($weekTotal['distance']) ? number_format($weekTotal['distance'] , 1) : 0;
				$unitsLabel = $this->units  == UNITS_METRIC ? 'km' : 'mi';
?>

				<tr class="week">
					<td class="day left-border" style="width:12.5%">
						<div class="date-range"><?=$w?></div>
							<div class="week-distance"><?=$this->stopwatch->elapsed($duration)?></div>
						<div class="week-duration"><?=$distance?>&nbsp;<?=$unitsLabel?></div>
					</td>
					<?php

					for($j = 0; $j < 7; $j++)
					{

						$activities = $weekTotal['activities'];
						$currentDayOfWeek = date(YMD, strtotime($index." +".$j." days"));
						$dayNum = date('M d',strtotime($currentDayOfWeek ));
					?>
						<td class="day align-top"  style="width:12.5%">
						<?php
							//Process the activities if there are any
							$numberClass = "offnumber";
							echo '<div class="number '.'$numberClass.">'.$dayNum.'</div>';
							if ((isset($activities[$currentDayOfWeek])) && (count($activities[$currentDayOfWeek]) > 0))
							{

								for ($k = 0; $k < count($activities[$currentDayOfWeek]); $k++)
								{
									$record = $records[$activities[$currentDayOfWeek][$k]];
									$this->stopwatch->convertToUnits($record, $this->units);
									$icon = $this->html->image(LOCATION_TYPEIMAGES.$types[$record['activitytype_id']]['image'], array('height'=>14, 'width'=>14));
									$eventString = $this->stopwatch->elapsed($record['moving_time'], array('decimals'=>0,'hoursnoshow'=>1));

									$decimalPoints =  ($record['activitytype_id'] != SWIMMING) ? 2 : 0;

									$eventString .= ', '  . number_format($record['distance'],$decimalPoints) ;
									$icon = $this->html->image(LOCATION_TYPEIMAGES.$types[$record['activitytype_id']]['image'], array('height'=>16, 'width'=>16, 'style'=>'margin-bottom:4px'))."&nbsp;";
	      					$eventString = $icon.$this->html->link($eventString, APP_NAME.'/activities/view/'.$record['id'], array('class'=>'activity-link', 'target'=>'_blank'));
									echo '<div class="event">'.$eventString.'</div>';
								}
							}
						}
						?>
						</td>
					<?php
					}
					?>
				</tr>
			</tbody>
		</table>
</div>
