<?php

$chartsEnabled = true;

if (isset($record))
{
	
	$record['total_elevation_gain']  = is_null($record['total_elevation_gain']) ? 0 : $record['total_elevation_gain'];
	if (($record['activitytype_id'] != SWIMMING) && ($record['polyline'] != ""))
	{
		$this->set('mapsEnabled', true);
		$mapsEnabled = true;
	} else {
		$this->set('mapsEnabled', false);
		$mapsEnabled = false;
	}
	
	$pageTitle = date('Y-m-d, D	H:i', strtotime($record['start_date_local'])).'';

	//START: Navigation Links
	$this->set('nextID',is_null($next) ? null : $next['id']);
	$this->set('lastID', is_null($last) ? null : $last['id']);
	$this->set('firstID', is_null($first) ? null : $first['id']);
	$this->set('previousID',is_null($previous) ? null : $previous['id']);

	$stravaLink = '';
	
	$navIMG = $this->html->image(LOCATION_ICONS.ICON_SEARCH,array('height'=>20, 'width'=>20,  'style'=>''));
	$searchLink = $this->html->link('<button type="button" class="btn btn-secondary"  >&nbsp;'.$navIMG.'&nbsp;</button>','/rule10/activities/search/', array('escape'=>false));
	//END: Navigation Links
	//$this->stopwatch->convertToUnits($record, $this->units);

	$elapsed = $this->stopwatch->elapsed($record['elapsed_time'], array('decimals'=>0));
	$hravg = number_format($record['average_heartrate'],0);
	$hrmax= number_format($record['max_heartrate'],0);
	$total_elevation_gain= is_numeric($record['total_elevation_gain_feet']) ? number_format($record['total_elevation_gain_feet'] ,0) : $record['total_elevation_gain'];

	$temperature = is_null($record['average_temp']) ? '' : number_format($record['average_temp'] , 1);
	
	$calories = number_format($record['calories'], 0);
	$icon = '&nbsp;';
	$iconInTitle = '';
	
	if ($types[$record['activitytype_id']]['image'] != '')
	{
		$icon = $this->html->image(LOCATION_TYPEIMAGES.$types[$record['activitytype_id']]['image'], array('height'=>20, 'width'=>20));
		$iconInTitle = $this->html->image(LOCATION_TYPEIMAGES.$types[$record['activitytype_id']]['image'], array('height'=>30, 'width'=>30,'style'=>'padding-top:0px;margin-top:0px'));
	}

	$icon = ($record['activity_id'] != '') ? $this->html->link($icon, 'https://www.strava.com/activities/'.$record['activity_id'], array('escape'=>false, 'target'=>'_blank')) : $icon;

	$record['stopped_time'] = $this->stopwatch->elapsed($record['elapsed_time'] - $record['moving_time'], array('decimals'=>0,'hoursnoshow'=>1));

	$this->set("iconInTitle", $iconInTitle);
	$this->set('record', $record);
	$this->set('chartsEnabled', $chartsEnabled);
	$this->set("distance",$record['distance']);
	$this->set("duration", $record['moving_time']);
	$this->set("total_elevation_gain", $total_elevation_gain);
	$this->set("calories", $calories);
?>

<form action="<?=APP_NAME?>/activities/view" id="searchForm" method="POST">

<div id="summary" class="row mb-5" style="">
	<div class="col-5" style="margin-bottom:0px; padding-bottom:0px;">
		<span style="font-size:24px;font-weight:275" class="mt-3"><?=$pageTitle?></span>
		<br>
		<span style="font-size:20px;font-weight:275" class=""><?=$record['name']?></span>
		<p class="mt-3">
			<?php
			if ($record['description'] != '')
			{
			?>
					<p><?=$record['description']?></p>
			<?php
			} else
			{
			?>
				&nbsp;
			<?php
			}
			?>
		</p>
	</div>


	<div class="col-2 pe-0 text-end">
			<button type="submit" class="btn btn-primary">Go To</button>
	</div>

	<div class="col-2 ps-0">
		<input class="form-control text-left" style="float:right" id="view" name="gotoDate" type="date" value="<?=date(YMD, strtotime($record['start_date_local']))?>" placeholder="">
	</div>
	<div class="col-3 text-end" >
		<div class="btn-group"  role="group" aria-label="" >
			<?=$stravaLink?>			
			<?=$this->element('recordNav')?>
		</div>
	</div>
</div>
</form>
<div class="row">
<?=$this->element('activitiesTableMetrics')?>
</div>
<?=$this->element('activitiesTabs');?>

<div class="tab-content" id="nav-tabContent">
	<div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab" tabindex="0">


		<?php
		if (isset($points) && (count($points) > 0) && ($mapsEnabled))
		{
				$this->set('mapDiv', "leafMapRoute");
				$this->set('height', '200');
				$this->set('width', '100%');
		?>
		<div class="row mb-4">
			<?=$this->element('leafMapRoute');?>
		</div>

		<?php
		}
		if (($record['activitytype_id'] != SWIMMING)  && ($record['streamAltitude'] != ''))
		{
?>
					<?php
						$this->stopwatch->units = DEFAULT_UNITS;
						$graphData = $this->stopwatch->graphStreamData($record, 'streamAltitude', 'altitude');
						$this->set('data', $graphData);
						$this->set('chartType', 'Area');
						$this->set('chartDiv', 'altitudeChart');
							$unitsForTitle = DEFAULT_UNITS == UNITS_METRIC ? "(meters)" : "(feet)";
						$this->set('title', 'Altitude '.$unitsForTitle);
						$this->set('vAxisTitle', '');
						$this->set('divHeight','90%');
						$this->set('divWidth', '100%');
						$this->set('height','100%');
						$this->set('width', '100%');
						$this->set('chartAreaHeight', '60%');
						$this->set('chartAreaWidth', '90%');
						$this->set('chartAreaLeft', '60');
						$this->set('chartAreaTop', '40');
						$this->set('hAxisFontSize', 8);
						$this->set('verticalHLabels',false);
						$this->set( 'colors', "['blue', 'blue', 'orange', 'green', 'purple']");
					?>
					<div class="row mb-2">
						<?=$this->element('charts2')?>
					</div>
		<?php
		}
		if (($record['streamHeart'] != ''))
		{
?>
					<?php
						$this->stopwatch->units = DEFAULT_UNITS;
						$graphData = $this->stopwatch->graphStreamData($record, 'streamHeart', 'heart');
						$this->set('data', $graphData);
						$this->set('chartType', 'Area');
						$this->set('chartDiv', 'heartChart');
							$unitsForTitle = DEFAULT_UNITS == UNITS_METRIC ? "(meters)" : "(feet)";
						$this->set('title', 'Heart Rate: '.round($record['average_heartrate'],0).'/'.round($record['max_heartrate'],0));
						$this->set('vAxisTitle', '');
						$this->set('divHeight','90%');
						$this->set('divWidth', '100%');
						$this->set('height','100%');
						$this->set('width', '100%');
						$this->set('chartAreaHeight', '60%');
						$this->set('chartAreaWidth', '90%');
						$this->set('chartAreaLeft', '60');
						$this->set('chartAreaTop', '40');
						$this->set('hAxisFontSize', 8);
						$this->set('verticalHLabels',false);
						$this->set( 'colors', "['blue', 'blue', 'orange', 'green', 'purple']");
					?>
					<div class="row mb-2">
						<?=$this->element('charts2')?>
					</div>
		<?php
		}
	?>

	</div>
	


<?php
		if (isset($efforts) && (count($efforts) > 0))
		{
?>
			<div class="tab-pane fade" id="nav-efforts" role="tabpanel" aria-labelledby="nav-efforts-tab" tabindex="0">
				<div class="row">
					<h4>Efforts</h4>
					<?=$this->element('activitiesEffortsList')?>
				</div>
			</div>
<?php
		}

?>

		<div class="tab-pane fade" id="nav-detail" role="tabpanel" aria-labelledby="nav-detail-tab" tabindex="0">
			<div class="row">
				<table class="table table-striped table-bordered table-hover table-responsive">
					<thead class="table-dark ">
						<tr>
							<th scope="col">field</th>
					
							<th scope="col">value</th>
						</tr>
					</thead>
					<tbody>
<?php
						foreach ($record as $key => $value)
						{
							if ($key == 'hrzones')
							{
								$value = json_encode($value);
							}
							if ($key == 'elapsed_time')
							{

								$value = $this->stopwatch->elapsed($value);
							}
							if (strstr($key, 'stream') || ($key == 'polyline') || (($key == 'summary_polyline')))
							{
								$value = 'stream';
							}
?>							<tr>
				      	<td style="word-wrap: break-word;min-width: 75px;max-width: 75px;"><?=$key?></td>
							  <td style="word-wrap: break-word;min-width: 200px;max-width: 200px;"><?=$value?></td>
							</tr>
<?php
						}
?>
					</tbody>
				</table>
			</div>
		</div>


	</div>

<?php
} else
{
	echo '<h1> NO RECORD DEFINED</h1>';
}
?>
