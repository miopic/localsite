<?php

	if (count($efforts) > 0)
	{

		$headings =
			array
			(
				'&nbsp;' 	=> array(),
				'climb'  	=> array('class'=>'text-center'),
				'segment' 	=> array(),
				'date' => array(),
				'moving' 	=>  array('class'=>'text-right'),
				'distance' 	=>  array('class'=>'text-right'),
				'strava_id'	=> array('class'=>'text-left'),

			);
		$rowOptions = array
        (
          	'pr_rank'           => array('class'=>'text-center'),
          	'climb_category'    => array('class'=>'text-center'),
        	'name'				=> array(),
        	'start_date_local'       => array(),
          	'moving_time'		=> array('class'=>'text-right'),
          	'distance'          => array('class'=>'text-right'),
          	'segment_id'			=> array('class'=>'text-left'),
        );

?>

		<div class="row">

			<?=$this->tag->open('table',array('class'=>'table table100 table-bordered table-hover', 'style'=>'margin-left:60px'))?>
			<?=$this->tag->open('thead',array('class'=>''))?>
			<?= $this->tag->headings($headings, array('style'=>''))?>
			<?=$this->tag->close('thead')?>
			<?=$this->tag->open('tbody')?>
<?php
			foreach($efforts as $key=>$effort)
			{
				$row['pr_rank'] = $effort['pr_rank'];
				$row['name'] = $this->html->link($effort['name'], '/segments/view/'.$effort['segment_id']);
			//	$row['climb_category'] = !isset($effort['segment_id']) && is_null($effort['segment_id']) ? "not saved" : $effort['climb_category'];
			//	$row['climb_category'] = $row['climb_category'] == 0 ? '&nbsp;' : $row['climb_category'];
			$row['climb_category'] = '';
				$row['start_date_local'] = $effort['start_date_local'];
				$row['moving_time'] = $this->stopwatch->elapsed($effort['moving_time'], array('decimals'=>0));
				$row['distance'] = number_format($effort['distance'] / METERS_PER_KILOMETER, 2);
				$row['segment_id'] = $this->html->link($effort['segment_id'], 'https://www.strava.com/segments/'.$effort['segment_id']);
				echo $this->tag->row($row, $rowOptions);
			}
?>
			<?=$this->tag->close('tbody')?>
			<?=$this->tag->close('table')?>

	</div>
<?php
	}

?>
