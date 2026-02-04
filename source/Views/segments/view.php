
<div class="tab-content" id="nav-tabContent">
	<div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab" tabindex="0">




<?php

if ($this->auth->user('units') == METRIC)
{
  $record['distance'] = $this->stopwatch->meters2kilometers($record['distance']);
} else
{
  $record['elevation_high'] =  $this->stopwatch->meters2feet($record['elevation_high']);
  $record['elevation_low'] =  $this->stopwatch->meters2feet($record['elevation_low']);
  $record['distance'] =  $this->stopwatch->meters2miles($record['distance']);
}

?>

<div class="row mb-3">
  <div class="col-5">
    <H4><?=$record['name']?></H4>
  </div>
</div>

	<nav>
	  <div class="nav nav-tabs " id="nav-tab" role="tablist"  style="padding-left:0px; margin-left:0px; margin-bottom:30px">
	    <button class="nav-link  active" id="nav-overview-tab" data-bs-toggle="tab" data-bs-target="#nav-overview" type="button" role="tab" aria-controls="nav-overview" aria-selected="true">Overview</button>
	    <button class="nav-link" id="nav-efforts-tab" data-bs-toggle="tab" data-bs-target="#nav-efforts" type="button" role="tab" aria-controls="nav-efforts" aria-selected="false">Efforts (<?=count($efforts) ?>)</button>
			<button class="nav-link" id="nav-details-tab" data-bs-toggle="tab" data-bs-target="#nav-details" type="button" role="tab" aria-controls="nav-details" aria-selected="false">Details</button>
	  </div>
	</nav>

<div class="tab-content" id="nav-tabContent">
	<div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab" tabindex="0">
		<div class="row mb-4">
			<?php
			if (isset($points) && (count($points) > 0))
			{

					$this->set('mapDiv', "leafMapRoute");
					$this->set('height', '200');
					$this->set('width', '100%');
				}
			?>
		  <?=$this->element('leafMapRoute');?>
		</div>
		<div class="row mb-3">
		    <table class="table table-striped table-hover fs-6">
		      <thead>
		          <tr class="table-dark text-center">
								<th class="">Count</th>
		            <th class="">Distance</th>
								<th>PR Date</th>
								<th>PR Time</th>
								<th>Category</th>

		            <th>Avg Grade</th>
		            <th>Max Grade</th>
		            <th>Elev. High</th>
		            <th>Elev. Low</th>

		          </tr>
		      </thead>
		      <tbody>
		        <tr class="text-center">
							<td><?=$count?></td>
		          <td><?=number_format($record['distance'], 2) ?></td>
							<td><?=date('Y-m-d',strtotime($efforts[0]['start_date_local']))?></td>
							<td><?=$this->stopwatch->elapsed($efforts[0]['moving_time'])?></td>
		          <td><?=$record['climb_category']?></td>
		          <td><?=number_format($record['average_grade'],1)?>%</td>
		          <td><?=number_format($record['maximum_grade'],1)?>%</td>
		          <td><?=number_format($record['elevation_high'],0)?></td>
		          <td><?=number_format($record['elevation_low'],0)?></td>
		        </tr>
		      </tbody>
		    </table>

		</div>
		<?=$this->element('activitiesEffortsList')?>
	</div>
	<div class="tab-pane fade show" id="nav-efforts" role="tabpanel" aria-labelledby="nav-efforts-tab" tabindex="1">
			<h4>Efforts</h4>
			<?=$this->element('activitiesEffortsList')?>
	</div>
	<div class="tab-pane fade show" id="nav-details" role="tabpanel" aria-labelledby="nav-details-tab" tabindex="2">
		<div class="row">details</div>
	</div>
</div>
