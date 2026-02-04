<?php
	$metricSelect = isset($metricSelect) ? $metricSelect : true;

	//Activity types select valuesw
	$this->set('selectedItem', $typeID);
	$this->set('selectName', 'typeID');
	$this->set('selectID', 'typeID');
	$selectActivityTypes =$this->element('select');
		$this->set('selectedItem',$metric);
		$this->set('selectName', 'metric');
		$this->set('selectID', 'metric');
		$this->set('elements', $metrics);
	$selectMetric = "&nbsp;";
	if ($metricSelect)
	{
		$selectMetric = $this->element('select');
	}
?>

<form method="POST" action="<?=$actionStr?>" class="form-inline border-bottom pb-3" style="float:right;padding-left:5px; padding-right:5px; width:100%">
	<div class="row" >

		<div class="col-2">
				<input class="form-control" id="currentDate" name="currentDate" type="date" value="<?=$currentDate?>">
		</div>
		<div class="col-2">
			<?=$selectActivityTypes?>			
		</div>	  
		<div class="col-2">
			<?=$selectMetric?>
		</div>



		<div class="col-1">
			<button type="submit" class="btn btn-primary">Submit</button>&nbsp;&nbsp;
		</div>
		<div class="col-1">
			&nbsp;
		</div>
		<div class="col-3 text-end">
			<div class="">
				<?=$this->element('dateNav');?>
			</div>
		</div>
	</div>
	<div class="row mt-4" >
		
		<div class="col-3">
			<table class="table table-sm table-bordered">
				<thead>
			    <tr>
			      <th scope="col">from</th>
			      <th scope="col">to</th>
						<th scope="col">by</th>
			    </tr>
				</thead>
				<tbody>
			    <tr>
			      <td><?=$fromDate?></td>
			      <td><?=$toDate?></td>
			      <td><?=$this->action?></td>
			    </tr>
				</tbody>
			</table>
		</div>
		<div class="col-9">
			&nbsp;
		</div>
	</div>
</form>
