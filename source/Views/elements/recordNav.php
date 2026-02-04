<?php
$navLink = isset($navLink) ? $navLink : APP_NAME.'/activities/view/';
?>
<button type="button" class="btn btn-secondary"   onclick="window.location.href='<?=APP_NAME?>/activities/edit/<?=$record['id']?>'" >edit</button>

<?php
$navIMG = $this->html->image(LOCATION_ICONS.ICON_FIRST,array('height'=>16, 'width'=>16,  'style'=>''));
?>
<button type="button" class="btn btn-secondary"   onclick="window.location.href='<?=APP_NAME?>/activities/view/<?=$firstID?>'" ><?=$navIMG?></button>

<?php
$navIMG = $this->html->image(LOCATION_ICONS.ICON_PREVIOUS,array('height'=>12, 'width'=>12 ,  'style'=>''));
?>
<button type="button" class="btn btn-secondary"   onclick="window.location.href='<?=APP_NAME?>/activities/view/<?=$previousID?>'" ><?=$navIMG?></butto1n>

<?php
$navIMG = $this->html->image(LOCATION_ICONS.ICON_NEXT,array('height'=>12, 'width'=>12));
?>
<button type="button" class="btn btn-secondary"   onclick="window.location.href='<?=APP_NAME?>/activities/view/<?=$nextID?>'" ><?=$navIMG?></butto1n>


<?php
$navIMG = $this->html->image(LOCATION_ICONS.ICON_LAST,array('height'=>16, 'width'=>16, 'style'=>''));
?>
<button type="button" class="btn btn-secondary"   onclick="window.location.href='<?=APP_NAME?>/activities/view/<?=$lastID?>'" ><?=$navIMG?></butto1n>
