<?php
  $nextDate = isset($dateIncrement) ? date(YMD, strtotime($currentDate.'+ '.$dateIncrement)) : $nextDate;
  $previousDate = isset($dateIncrement) ? date(YMD, strtotime($currentDate.'- '.$dateIncrement)) : $previousDate;
  $dateFormat = isset($dateFormat) ? $dateFormat : YMD;
  $currentDate = date($dateFormat, strtotime($currentDate));

  $link = isset($link) ? $link :  APP_NAME.'/activities/logbook';
  $nextNav = '';
  if (strtotime($nextDate) <= strtotime(date(YMD)))
  {
    $nextNav = $this->html->link($this->html->image(LOCATION_ICONS.'icon-next.png'), $link.$nextDate);
  }
  $previousNav = $this->html->link($this->html->image(LOCATION_ICONS.'icon-previous.png'), $link.$previousDate);
  $dateLabelWidth = isset($dateLabelWidth) ? $dateLabelWidth :'90';
  $label = isset($label) ? $label : "";
  $label = $label != "" ? '<div style="float:left; margin-right:10px; font-size:16px; font-weight:bold;padding-bottom:5px">'.$label.'</div>' : "";
?>


<div class="btn-group" role="group" aria-label="Basic Example" >
  <a href="<?=$link.$previousDate?>">
    <button type="button" class="btn btn-secondary">&nbsp;<img src="/img/icons/icon-previous.png" height="16" width="16" style="">&nbsp;</button>
  </a>
  <div style="margin-right:5px; margin-left:5px;padding-top:7px">&nbsp;<?=$currentDate?>&nbsp;</div>
  <a href="<?=$link.$nextDate?>">
    <button type="button" class="btn btn-secondary">&nbsp;<img src="/img/icons/icon-next.png" height="16" width="16" style="">&nbsp;</button>
  </a>

</div>
