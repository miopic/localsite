<?php
$chartsEnabled = true;
$overviewTabLabel = date("D M d, 'y", strtotime($fromDate));
$u = $this->user;
$this->set('typeID', is_null($typeID) ? 0 : $typeID);

$increment = 'day';
	
	$this->set('link',APP_NAME.'/activities/day/');
	$this->set('actionStr',APP_NAME.'/activities/day/');
	$this->set('incrementDate', $increment);

	$this->set('distanceDecimals', 2);
	/*
	$this->set('grandTotalsEnabled', true);
	
	$this->set('latitude', DEFAULT_LATITUDE);
	$this->set('longitude', DEFAULT_LONGITUDE);
	*/
	$this->set('enableTimeframes', false);
	$this->set('dateFormat', YMDDYHI);
	$this->set('fromDate', $fromDate);
	$this->set('metricSelect', false);
	
?>

<?=$this->element('activitiesTools');?>
<?php
$this->set('recordList', $records);
?>
<div class="row">
	<?=$this->element('activitiesRecords')?>
</div>
