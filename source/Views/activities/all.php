<?php
	$chartsEnabled = true;
  $this->set('chartType', 'Column');
  $this->set('chartDiv', 'years');
  $this->set('vAxisTitle', $metrics[$metric]);
  $this->set('enableDateColumn', false);
  $this->set('totalsList', $allTime);
?>

<form method="POST" action="/app/activities/alltime" class="form-inline border-bottom pb-3" style="float:right;padding-left:5px; padding-right:5px; width:100%">
	<div class="row" >
		<div class="col-3">
			<?php
				$this->set('selectedItem', $typeID);
				$this->set('selectName', 'typeID');
				$this->set('selectID', 'typeID');
				?>
		  <?=$this->element('select');?>
		</div>
    <?php
		$this->set('selectedItem',$metric);
		$this->set('selectName', 'metric');
		$this->set('selectID', 'metric');
		$this->set('elements', $metrics);
		?>
		<div class="col-2">
			<?=$this->element('select');?>
		</div>
		<div class="col-2">
			<button type="submit" class="btn btn-primary">Submit</button>&nbsp;&nbsp;
		</div>
		<div class="col-1">
			&nbsp;
		</div>

	</div>
</form>

<div class="row rounded" style=" margin-bottom:20px; height:350px; background:lightgrey;padding-top:10px;" id="chartHolder">
  <?=$this->element('charts2')?>
</div>
<div class="row" style="" id="">
  <?= $this->element("activitiesTotalsList");?>
</div>
</div>
