<?php
	if (count($efforts) > 0)
	{
?>

			<div class="row">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th class="">climb</th>
							<th>segment</th>
							<th>date</th>
							<th class="text-right">moving</th>
							<th class="text-right">distance</th>
							<th>strava_id</th>
						</tr>
					</thead>
					<tbody>
<?php
			foreach($efforts as $key=>$effort)
			{
?>
				<tr>
					<td><?=$effort['pr_rank']?></td>
					<td><?=$this->html->link($effort['name'], APP_NAME.'/segments/view/'.$effort['segment_id'])?></td>
					<td>&nbsp;</td>
					<td><?=$effort['start_date_local']?></td>
					<td><?=$this->stopwatch->elapsed($effort['moving_time'], array('decimals'=>0))?></td>
					<td><?=number_format($effort['distance'] / METERS_PER_KILOMETER, 2)?></td>
					<td><?=$this->html->link($effort['segment_id'], 'https://www.strava.com/segments/'.$effort['segment_id'])?></td>
				</tr>
<?php
			}
?>
					</tbody>
			</table>
	</div>
<?php
	}

?>
