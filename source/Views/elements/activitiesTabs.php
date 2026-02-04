

<nav>
  <div class="nav nav-tabs " id="nav-tab" role="tablist"  style="padding-left:0px; margin-left:0px; margin-bottom:30px">
    <button class="nav-link  active" id="nav-overview-tab" data-bs-toggle="tab" data-bs-target="#nav-overview" type="button" role="tab" aria-controls="nav-overview" aria-selected="true">Overview</button>
  		<?php
		  	if (isset($photos) && (count($photos) > 0))
				{
		?>
		    	<button class="nav-link" id="nav-photos-tab" data-bs-toggle="tab" data-bs-target="#nav-photos" type="button" role="tab" aria-controls="nav-photos" aria-selected="false">Photos&nbsp;(<?=count($photos)?>)</button>
		<?php
				}
		?>

<?php
  	if (isset($efforts) && (count($efforts) > 0))
		{
?>
    	<button class="nav-link" id="nav-efforts-tab" data-bs-toggle="tab" data-bs-target="#nav-efforts" type="button" role="tab" aria-controls="nav-efforts" aria-selected="false">Efforts (<?=count($efforts)?>)</button>
<?php
		}
?>
		<button class="nav-link" id="nav-detail-tab" data-bs-toggle="tab" data-bs-target="#nav-detail" type="button" role="tab" aria-controls="nav-detail" aria-selected="false">Details</button>

<?php


?>
  </div>
</nav>
