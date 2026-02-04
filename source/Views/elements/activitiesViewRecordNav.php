<form action="<?=APP_NAME?>/activities/view" id="searchForm" method="POST">

<div id="summary" class="row mb-5" style="">
	<div class="col-5" style="margin-bottom:0px; padding-bottom:0px;">
		<span style="font-size:24px;font-weight:275" class="mt-3"><?=$pageTitle?>:&nbsp;<?=$record['name']?></span>
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
			<?=$searchLink?>
			<?=$this->element('recordNav')?>
		</div>
	</div>
</div>
</form>
