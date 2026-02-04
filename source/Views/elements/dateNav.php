<?php

  $nextDate = date(YMD, strtotime($currentDate.' + '.$increment));
  $previousDate = date(YMD, strtotime($currentDate.' - '.$increment));
  $dateFormat = isset($dateFormat) ? $dateFormat : YMD;
  $currentDate = date($dateFormat, strtotime($currentDate));
  $link = isset($link) ? $link :  APP_NAME.'/activities/timeframes';
  ?>
  <div class="btn-group" role="group" aria-label="Basic Example" >

    <a href="<?=$link.$previousDate?>">
      <button type="button" class="btn btn-secondary btn-forwardbackward" style="">&nbsp;<img src="/img/icons/icon-previous.png" height="16" width="16" style="">&nbsp;</button>
    </a>
    <div style="margin-right:5px; margin-left:5px;padding-top:7px">&nbsp;<?=$currentDate?>&nbsp;</div>
<?php
    if (strtotime($nextDate) <= strtotime(date(YMD)))
    {
?>
      <a href="<?=$link.$nextDate?>">
        <button type="button" class="btn btn-secondary btn-forwardbackward float-right">&nbsp;<img src="/img/icons/icon-next.png" height="16" width="16" style="">&nbsp;</button>
      </a>
<?php
    } else
    {
?>
      <button type="button" disabled class="btn btn-secondary btn-forwardbackward float-right">&nbsp;<img src="/img/icons/icon-next.png" height="16" width="16" style="">&nbsp;</button>
<?php
    }
?>
  </div>
