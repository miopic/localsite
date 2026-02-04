<?php
$month = $currentDate;
$dayNames = isset($dayNames) ? $dayNames : array('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY');
define("SHOW_OFF_MONTH_NUMBERS", true);
$monthTitle = date("F - Y",strtotime($month));
$previousMonth = date(YMD,strtotime($month. ' -1 month'));
$nextMonth = date(YMD,strtotime($month. ' +1 month'));
$link = '/'.$this->controller.'/'.$this->action .'/';
//calculate calendar window if timeframe = MONTH
  $calStart = $this->time->getWeek($month);
  $temp = date('Y-m-t',strtotime($month));
  $lastCalWeek = $this->time->getWeek($temp);
  $calLast = date('Y-m-d',strtotime($this->time->getWeek($lastCalWeek)." +6 days"));
  $showSummary = isset($showSummary) ? $showSummary : false;
   $colSpan = 7;
  $colWidth = '14%';

$showMonthNav = isset($showMonthNav) ? $showMonthNav : false;
?>

<link href='/css/calendar.css' rel='stylesheet' type='text/css'>
<style type="text/css">

</style>

  <div class="row">

    <table class="calendar">
      <tr class="monthTitle">
        <th colspan="<?=$colSpan?>" class="left-border">
        <?php
        if ($showMonthNav)
        {
        ?><img src="/img/icons/icon-sun.png" height="12" width="12"><?=$sunInfo['sunrise']?>
          <span style="float:left">&nbsp;&nbsp;<a href="<?php=$link?><?=$previousMonth?>">&nbsp;&nbsp;<img src="/img/<?=LOCATION_ICONS?><?=ICON_PREVIOUS?>"></a></span>
        <?php
        }
        ?>
        <?=strtoupper($monthTitle)?>
<?php
          if ($showMonthNav)
          {
?>
            <span style="float:right"><a href="<?=$link?><?=$nextMonth?>"><img src="/img/<?=LOCATION_ICONS?><?=ICON_NEXT?>"></a>&nbsp;&nbsp;&nbsp;&nbsp;</span>
<?php
              }
?>
              </th>

      </tr>
      <tr>
<?php
      //Make the header with the names in the dayNames array
      foreach($dayNames as $i=>$name)
      {
        if ($i == 0)
        {
          $dayHeader = '<th class="day-name left-border">'.$name. '</th>';
        } else
        {
          $dayHeader = '<th class="day-name">'.$name. '</th>';
        }
        echo $dayHeader;
      }

?>


      </tr>
<?php

      $keys = array_keys($dates);
      for($j = 0; $j < 6; $j++)
      {
        if (strtotime($keys[7*$j]) <= strtotime($calLast))
        {

          echo '<tr class="week">';
          for($i = 0; $i < 7 ; $i++)
          {
            $k = (7*$j)+$i;
            $day = "&nbsp;";
            $paddingNumber = "padding:2px 3px 3px 5px;";
            $borderNumber = "";
            $backgroundColor = 'whitesmoke';
            $currentKey = $keys[$k];

            //Determine whether the date is in the month or not, which then determines the class style to apply to the number div
            if (date("m",strtotime($month)) != date("m",strtotime($dates[$currentKey]['date'])) && (SHOW_OFF_MONTH_NUMBERS))
            {

              $day = date("j",strtotime($dates[$currentKey]['date']));
              $borderNumber = "border-bottom:1px solid black";
              $numberClass = "offnumber";
              $numberLinkClass = "off";
            } else
            {
               $day = date("j",strtotime($dates[$currentKey]['date']));
               $borderNumber = "border-bottom:1px solid black";
               $numberClass  = "number";
               $numberLinkClass = "on";
            }
            $f = date(YMD, strtotime($dates[$currentKey]['date']));
            $t = Date(YMD, strtotime($dates[$currentKey]['date']." +1 day"));

            $today = "";

            if (strtotime($dates[$currentKey]['date']) == strtotime(date(YMD)))
            {
                $today = "today";
            }
            $leftBorder = '';
            if ($i == 0)
            {
              $leftBorder = 'left-border';
            }
?>

            <td valign="top" class="day <?=$leftBorder?> align-top" >
              <div class="<?=$numberClass?> <?=$today?>" ><a class="<?=$numberLinkClass?>" href="<?=APP_NAME?>/activities/search/<?=$f?>/<?=$t?>"><?=$day?></a></div>
              <?php
              $sunInfo = $this->stopwatch->sun($latitude, $longitude, $currentKey);
              $sunIcon = $this->html->image('icons/icon-sun.png', array('height'=>12, 'width'=>12));
              $sunInfo['sunrise'] = $sunIcon.' '.date('h:i A', strtotime($sunInfo['sunrise']));
              $sunInfo['sunset'] = date('h:i A', strtotime($sunInfo['sunset']));
              ?>
                <div class="event border-bottom" style=""><?=$sunInfo['sunrise']?>&nbsp;/&nbsp;<?=$sunInfo['sunset']?></div>
<?php


              if (count($dates[$currentKey]['events']))
              {
?>
  <?php
                for ($l = 0; ($l < count($dates[$currentKey]['events'])) && ($l < 4); $l++)
                {
                  echo '<div class="event">'.$dates[$currentKey]['events'][$l].'</div>';
                }

              }
?>
            </td>
<?php
          }

          echo "</tr>";
          }
        }

?>

    </table>

   </div>
