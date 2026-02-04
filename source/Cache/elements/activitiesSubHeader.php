<?php
	$title = isset($title) ? $title : 'title';
	$nav = isset($nav) ? $nav : 'nav';
?>

<div class="container border-bottom">
	<div class="row rowSpacing20">
		<div class="col">
			<h3><?=$title?></h3>
		</div>
		<div class="col ">
			<span class="float-right"><h5><?=$nav?></h5></span>
		</div>
	</div>	
</div>


