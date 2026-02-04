<?php
		// Display alerts if they're are any
		if (count($this->app->alerts) > 0)
	 	{

?>
			<div class="row">
<?php
			while ($a = $this->app->popAlert())
			{
?>
					<div class="alert alert-dismissible fade show <?=$a['type']?>" role="alert">
						<?=trim($a['message'])?>
						<button type="button" class="close float-end" data-bs-dismiss="alert" aria-label="Close" id="close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
<?php
			}
?>
		</div>
<?php
		}

?>
