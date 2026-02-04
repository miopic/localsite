<?
  $dayNames = isset($dayNames) ? $dayNames : array('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY');
  define("SHOW_OFF_MONTH_NUMBERS", false);
  $monthTitle = date("F - Y",strtotime($month));
  $previousMonth = date(YMD,strtotime($month. ' -1 month'));
  $nextMonth = date(YMD,strtotime($month. ' +1 month'));
  $link = '/'.$this->controller.'/'.$this->action .'/';
 
  if (isset($summaryContent) || true)
  {
    $showSummary = isset($showSummary) ? $showSummary : false;
     $colSpan = 7;
    $colWidth = '14%';
  } else 
  {
    $showSummary = true;
    $colSpan = 8;
    $colWidth = '12%';
  } 
  $showMonthNav = isset($showMonthNav) ? $showMonthNav : false;
?>

<link href='/css/calendar.css' rel='stylesheet' type='text/css'>
<style type="text/css">
  
</style>

<div class="container" style="margin-left: 0px">
  <div class="row">
    <table class="calendar">
      <tr class="monthTitle">
                <th colspan="<?=$colSpan?>" class="left-border">
<?
              if ($showMonthNav)
              {
?>
                  <span style="float:left">&nbsp;&nbsp;<a href="<?=$link?><?=$previousMonth?>">&nbsp;&nbsp;<img src="/img/<?=LOCATION_ICONS?><?=ICON_PREVIOUS?>"></a></span>
<?
              }
?>

                  <?=strtoupper($monthTitle)?>
<?
              if ($showMonthNav)
              {
?>
                  <span style="float:right"><a href="<?=$link?><?=$nextMonth?>"><img src="/img/<?=LOCATION_ICONS?><?=ICON_NEXT?>"></a>&nbsp;&nbsp;&nbsp;&nbsp;</span>

<?
              }
?>
              </th>

      </tr>
      <tr>
<?
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
      if ($showSummary)
      {
        echo '<th class="day-name">SUMMARY</th>';
      }
?>        
        

      </tr>
<?
      $keys = array_keys($dates);
      for($j = 0; $j < 6; $j++)
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

            if (date("m",strtotime($month)) == date("m",strtotime($dates[$currentKey]['date'])) || (SHOW_OFF_MONTH_NUMBERS))
            {
              $day = date("j",strtotime($dates[$currentKey]['date']));
              $borderNumber = "border-bottom:1px solid black";
              $offMonthNumber = "";
            } else
            {
               $offMonthNumber = "offMonthNumber";
            }
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

            <td valign="top" class="day <?=$leftBorder?> " >
              <div class="number <?=$offMonthNumber?> <?=$today?> " ><?=$day?></div>
<?
              if (count($dates[$currentKey]['events']))
              {
                for ($l = 0; ($l < count($dates[$currentKey]['events'])) && ($l < 4); $l++)
                {
                  echo '<div class="event">'.$dates[$currentKey]['events'][$l].'</div>';
                }       
              }
              
?>
              
            </td>
<?
          }
          if ($showSummary)
          {

            echo '<td valign="top" class="day" ><?=$j?></td>';
          }
          echo "</tr>";
        }

?>

    </table>

   </div>
  </div>
