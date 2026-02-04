<?
	if (count($efforts) > 0)
	{
	
		$headings =
			array
			(
				'&nbsp;' 	=> array(),
				'segment' 	=> array(),
				'date' => array('type'=>'link', 'value'=>$this->params['uri'].'sort:start_date_local/'),
				'moving' 	=>  array('class'=>'text-right'),
				'distance' 	=>  array('class'=>'text-right'),

			);
		$rowOptions = array 
        (
          	'pr_rank'           => array('class'=>'text-center'),
        	'name'				=> array(),
        	'start_date_local'       => array(),
          	'moving_time'		=> array('class'=>'text-right'),
          	'distance'          => array('class'=>'text-right'),
        );

?>

		<div class="row">
			<h3 class="rowSpacing20">Efforts</h3>

			<?=$this->tag->open('table',array('class'=>'table table100 table-bordered table-hover'))?>
			<?=$this->tag->open('thead',array('class'=>''))?>
			<?= $this->tag->headings($headings, array('style'=>''))?>
			<?=$this->tag->close('thead')?>
			<?=$this->tag->open('tbody')?>
<?
			foreach($efforts as $key=>$effort)
			{
				$row['pr_rank'] = $effort['pr_rank'];
				

				$row['name'] = $this->html->link($effort['name'], '/segments/view/'.$effort['segment_id']);
				$row['start_date_local'] = $effort['start_date_local']; 
				$row['moving_time'] = $this->stopwatch->elapsed($effort['moving_time'], array('decimals'=>0));
				$row['distance'] = number_format($effort['distance'] / METERS_PER_KILOMETER, 2);
				
				echo $this->tag->row($row, $rowOptions);
			}
?>
			<?=$this->tag->close('tbody')?>
			<?=$this->tag->close('table')?>
		</div>
<?		
	}
?>