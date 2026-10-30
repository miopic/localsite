<?php
$this->set('recordList', $farthest);
?>
<div class="row">
	<h4>Farthest</h4>
	<?=$this->element('activitiesRecords')?>
</div>
<?php
$this->set('recordList', $mostlElevation);
?>
<?php
if ($activitytype_id != 'Swim')
{
?>
<div class="row">
	<h4>Most Elevation Gain</h4>
	<?=$this->element('activitiesRecords')?>
</div>
<?php
}
?>