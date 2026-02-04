<?php
$chartsEnabled = true;
$this->set('chartsEnabled', $chartsEnabled);
$overviewTabLabel = $timeframe == 'year' ? date('Y', strtotime($currentDate)) : date("M 'y", strtotime($currentDate));
$u = $this->auth->user('units');

if (count($records) > 0)
{
	// put records in day buckets so the calendar can display them in the right square
	foreach ($records as $i=>$r)
	{
		$this->stopwatch->convertToUnits($records[$i], $u);
		if ($timeframe == 'month')
		{

			$icon = $this->html->image(LOCATION_TYPEIMAGES.$this->types[$r['activitytype_id']]['image'], array('height'=>12, 'width'=>12));
			$dateIndex = date(YMD, strtotime($r['start_date_local']));
			$eventString = $this->stopwatch->elapsed($r['moving_time']);
			$eventString = '&nbsp;'.$this->stopwatch->elapsed($r['moving_time']).', '.number_format(round($records[$i]['distance'],1), 1);
			$eventString = $icon.'&nbsp;'.$this->html->link($eventString, APP_NAME.'/activities/view/'.$r['id'], array('class'=>'activity-link'));
			$dates[$dateIndex]['events'][count($dates[$dateIndex]['events'])] = $eventString;
			$this->set('label', date(Y,strtotime($currentDate)));
		}
	}
}
	$this->set('dates', $dates);
	$this->set('recordList', $yearTotals);
	$this->set('link',APP_NAME.'/activities/timeframes/');
	$this->set('actionStr',APP_NAME.'/activities/timeframes/');
	$this->set('incrementDate', $increment);

	$this->set('distanceDecimals', 0);
	$this->set('grandTotalsEnabled', true);
	$this->set('latitude', DEFAULT_LATITUDE);
	$this->set('longitude', DEFAULT_LONGITUDE);
	$this->set('dateField', 'month');


	$this->set('enableTimeframes', true);

?>

<?=$this->element('activitiesTools');?>
<!-- Tabs -->
<div class="row" >
	<nav>
  	<div class="nav nav-tabs" id="nav-tab" role="tablist"  style="padding-left:0px; margin-left:0px; margin-bottom:30px">
	  	<button class="nav-link active" id="nav-overview-tab" data-bs-toggle="tab" data-bs-target="#nav-overview" type="button" role="tab" aria-controls="nav-overview" aria-selected="true"><?=$overviewTabLabel?></button>
			<?=($timeframe == 'month') && (count($records) > 0) ? '<button class="nav-link " id="nav-calendar-tab" data-bs-toggle="tab" data-bs-target="#nav-calendar" type="button" role="tab" aria-controls="nav-calendar" aria-selected="true">Calendar</button>' : ''?>
			<button class="nav-link" id="nav-yearbymonth-tab" data-bs-toggle="tab" data-bs-target="#nav-yearbymonth" type="button" role="tab" aria-controls="nav-yearbymonth" aria-selected="true">Data by Month</button>
			<button class="nav-link" id="nav-records-tab" data-bs-toggle="tab" data-bs-target="#nav-records" type="button" role="tab" aria-controls="nav-records" aria-selected="true">Records</button>
		</div>
	</nav>
</div>


<div class="tab-content" id="nav-tabContent">

	<!-- Overview Tab Content -->
	<div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab" tabindex="0">
		<?php
		if (count($records) > 0)
		{
			$this->set('data', $graphRecords);
			$this->set('chartType', 'Column');
			$this->set('chartDiv', 'days');
			$this->set('colors', $colorsForRecords);
			$this->set('vAxisTitle', $metrics[$metric]);
		?>
			<div class="row " style=" margin-bottom:20px; height:350px; padding-top:10px;" id="chartHolder">
				<?=$this->element('charts2')?>
			</div>
			<?php
			$this->set('data', $graphMonths);
			$this->set('chartType', 'Column');
			$this->set('chartDiv', 'mos');
			$this->set('colors', $colorsForMonths);
			?>
			<div class="row " style=" margin-bottom:20px; height:350px;padding-top:10px;" id="chartHolder2">
				<?=$this->element('charts2')?>
			</div>

			<div class="row" style="" id="">
				<?=$this->element("activitiesTotalsList");?>
			</div>

			<div class="row" style="" id="">

				<style>
				a:link {
					color: inherit;
					text-decoration: none;
				}
				a:hover {
					color: green;

				}
				</style>
				<ul>
					<?php
					$recentCount = 6;

					if (count($records) >= 1 )
					{
						$recents = array();
						$i = 0;
						for ($j = (count($records)-1); ($j > (count($records) - 1 - $recentCount) && isset($records[$j])); $j--)
						{
							$recents[$i]  = $records[$j];
							$i++;
						}
						$this->set('recordList', $recents);
						?>
						<div class="row">
							<h5>recent</h5>
							<?=$this->element('activitiesRecords')?>
						</div>
					<?php
					}
					?>
				</ul>
			</div>
		<?php
		} else
		{
		?>
			<div class="row" style="height:500px" id="">
				<h1>GET MOVING!</h1>
			</div>
		<?php
		}
		?>
	</div>

	<!-- Calendar Tab Content -->
	<?php
	if (($timeframe == 'month')  && (count($records) > 0))
	{
	?>
		<div class="tab-pane fade show" id="nav-calendar" role="tabpanel" aria-labelledby="nav-calendar-tab" tabindex="1">
			<div class="row" id="">
				<?=$this->element('calendar');?>
			</div>
		</div>
	<?php
	}
	?>

	<!-- YearByMonth Tab Content -->
	<div class="tab-pane fade show " id="nav-yearbymonth" role="tabpanel" aria-labelledby="nav-yearbymonth-tab" tabindex="2">
		<div class="row" id="">
			<?php
			$this->set('recordList', $byMonth); ?>
			<?= $this->element("activitiesTotalsList");?>
		</div>
	</div>
	<div class="tab-pane fade show" id="nav-records" role="tabpanel" aria-labelledby="nav-records-tab" tabindex="3">
		<?php
		krsort($records);
		$this->set('recordList', $records);
		?>
		<div class="row">
			<?=$this->element('activitiesRecords')?>
		</div>
	</div>
</div>
