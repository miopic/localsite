	
			<?php  
					if ($record['activity_id'] == SWIMMING)
					{
						if ($this->units == UNITS_METRIC)
						{
							$distance = $record['meters'];
						} else
						{
							$distance = $record['yards'];
						}
					} else
					{
						if ($this->units == UNITS_METRIC)
						{
							$distance = $record['kilometers'];
						} else
						{
							$distance = $record['miles'];
						}
					}
					$calories = $record['calories'];
			?>
<div class="row">

	<form id="editActivity" method="POST" action="<?=APP_NAME?>/activities/edit" class="rounded p-4" style="">
		<div class="col-6 mb-1 float-start">
			<div class="row">
				
				<div class="mb-2">
	  				ID: <?=$record['id']?>
	  				<input type="hidden" class="form-control" id="id" name="id" value="<?=$record['id']?>" >
	  			</div>

				<div class="mb-2">
	  				<label for="activitytype_id class="form-label">City/Town</label>
				    <input type="text" class="form-control" id="type" name="start_date_local" value="<?=$record['start_locality']?>" type="datetime-local">
				</div>
				<div class="mb-2">
	  				<label for="activitytype_id class="form-label">Date Time</label>
				    <input type="text" class="form-control" id="type" name="start_date_local" value="<?=$record['start_date_local']?>" type="datetime-local">
				</div>
				<div class="mb-2">
	  				<label for="activitytype_id class="form-label">Activity Type</label>
				    <input type="text" class="form-control" id="type" name="activitytype_id" value="<?=$record['activitytype_id']?>">
				</div>
				<div class="mb-2">
				    <label for="name" class="form-label" >Subject</label>
				    <input type="text" class="form-control" id="name" name="name" value="<?=$record['name']?>">
				</div>
				<div class="mb-2">
				    <label for="distance" class="form-label" >moving_time: <?=$this->stopwatch->elapsed($record['moving_time'])?></label>
				    <input type="text" class="form-control" id="distance" name="moving_time" value="<?=$record['moving_time']?>">  
				</div>
				<div class="mb-2">
				    <label for="distance" class="form-label" >elapsed_time: <?=$this->stopwatch->elapsed($record['elapsed_time'])?></label>
				    <input type="text" class="form-control" id="distance" name="elapsed_time" value="<?=$record['elapsed_time']?>">  
				</div>
				<div class="mb-2">
				    <label for="distance" class="form-label" >Distance</label>
				    <input type="text" class="form-control" id="distance" name="distance" value="<?=$distance?>">  
				</div>
				<div class="mb-2">
				    <label for="calories" class="form-label" >total_elevation_gain</label>
				     <input type="text" class="form-control" id="total_elevation_gain" name="total_elevation_gain" value="<?=$record['total_elevation_gain']?>">  
				</div>
				<div class="mb-2">
				    <label for="calories" class="form-label" >calories</label>
				     <input type="text" class="form-control" id="calories" name="calories" value="<?=$calories?>">  
				</div>
				  <button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</div>
	</form>
</div>