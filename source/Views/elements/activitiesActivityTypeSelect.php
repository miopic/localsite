<?php
$link = isset($link) ? $link : '/rule10/activities/logbook';
?>
<form method="POST" action="<?=$link?><?=$typeID?>" class="form-inline" style="float:right;padding-left:5px; padding-right:5px; width:100%">
  <?php
  $this->set('selectName', 'typeID');
  $typeList[0] = 'All';
  ksort($typeList);
  $this->set('elements', $typeList);
  ?>

   <input type="hidden" id="currentDate" name="currentDate" value="<?=$currentDate?>">
  <?=$this->element('select');?>&nbsp;&nbsp;<?=$this->html->link($this->html->image(LOCATION_ICONS.ICON_DOWNLOAD,array('height'=>30, 'width'=>30,  'style'=>'text-left')),'/rule10/activities/fetch/', array('escape'=>false));?>
</form>
