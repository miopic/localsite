<?php
  $chunkLabels = isset($chunkLabels) ? $chunkLabels : array('week'=>'Totals for week', 'month'=>'Totals for month', 'year'=>'Totals for year');
  $headings = array
  (
    'Date'     => array(),
    'Activity'  => array('class'=>'text-center'),
    'Count'     => array('class'=>'text-center'),
    'Moving'    => array('class'=>'text-center'),
      'Distance'  => array('class'=>'text-center', 'type'=>'icon', 'value'=>
  ICON_DISTANCE),
    
    'Speed'     => array('class'=>'text-center', 'type'=>'icon',  'value'=>ICON_SPEED),
    'Altitude'  => array('class'=>'text-center', 'type'=>'icon',  'value'=>ICON_ALTITUDE),
    'Calories'  => array('class'=>'text-center', 'type'=>'icon',  'value'=>ICON_CALORIES),
    'Kudos'     => array('class'=>'text-center', 'type'=>'icon',  'value'=>ICON_KUDOS, 'show'=>SHOW_KUDOS),
    'Achieve'   => array('class'=>'text-center', 'type'=>'icon',  'value'=>ICON_ACHIEVEMENTS),
    'Commute'   => array('class'=>'text-center', 'type'=>'icon',  'value'=>ICON_COMMUTE),
  );
?>


  <style type="text/css">
.table-dark {
    color: #000;
    background-color: aliceblue;
}
</STYLE>


<?php
      //if (isset($gt ) && count($gt) > 0)
      {
        foreach($totals as $whichTotalsChunk=>$chunk)

        {
  ?>
          <div class="row rowSpacing40">
          <?=$this->tag->open('table', array('class'=>'table table-striped table100'))?>
            <?=$this->tag->open('thead',array('class'=>'table-dark'))?>
            <?= $this->tag->headings($headings, array('style'=>'border-top:2px solid black'))?>
          <?=$this->tag->close('thead')?>
          <tbody>
  <?
          foreach ($totals[$whichTotalsChunk] as $id=>$row)
          {
            $row['activitytype_id'] = isset($row['activitytype_id']) ? $row['activitytype_id'] : $typeID;
            echo $this->tag->open('tr', array('style'=>'font-weight:normal; border-bottom:1px solid black; border-top:1px solid black'));
            $formattedValue = $chunkLabels[$whichTotalsChunk];
            echo $this->tag->cell($formattedValue, array('class'=>'text-left', 'style'=>'font-weight:normal'));
            $formattedValue = isset($types[$row['activitytype_id']]['image']) ? $this->html->image(LOCATION_TYPEIMAGES.$types[$row['activitytype_id']]['image'],array('height'=>'20', 'width'=>'20')) : '&nbsp;';
            echo $this->tag->cell($formattedValue, array('class'=>'text-center', 'style'=>'font-weight:normal'));

            $row['count'] = isset($row['count']) ? $row['count'] : 0;
            echo $this->tag->cell(number_format($row['count'] ,0),array('class'=>'text-center', 'style'=>'font-weight:normal;'));

            $row['moving_time'] = isset($row['moving_time']) ? $row['moving_time'] : 0;
            $row['commute'] = isset($row['commute']) ? $row['commute'] : 0;
            echo $this->tag->cell($this->stopwatch->elapsed($row['moving_time'],array('decimals'=>0)), array('class'=>'text-center', 'style'=>'font-weight:normal;'), array('class'=>'text-center', 'style'=>'font-weight:normal;'));
            echo $this->tag->cell($this->html->formatUnits($row['activitytype_id'],$row['distance'],1), array('class'=>'text-center', 'style'=>'font-weight:normal;'));


            switch ($row['activitytype_id']) {
        			case SWIMMING:
                  $speed = $this->stopwatch->elapsed($row['moving_time'] / (($row['distance']  * METERS_PER_YARD) / 100), array('decimals'=>0));
        				break;

        			  default:
                  //$temp = isset($row['avgDailyBudget']) && ($row['avgDailyBudget'] != 0) ? number_format(($row['distance']/METERS_PER_KILOMETER) / $row['avgDailyBudget'],2) : 'N/A';
                  $speed = $row['moving_time'] > 0 ? ($row['distance']/METERS_PER_KILOMETER) / ($row['moving_time'] / HOUR) : 0;
                  $speed = number_format($speed, 2);
        			break;
        		}
            //echo $this->tag->cell($temp, array('class'=>'text-center'));
            echo $this->tag->cell($speed, array('class'=>'text-center', 'style'=>'font-weight:normal;'));

            $row['total_elevation_gain'] = isset($row['total_elevation_gain']) ? $row['total_elevation_gain'] : 0;
            echo  $this->tag->cell(number_format($row['total_elevation_gain'],0), array('class'=>'text-center', 'style'=>'font-weight:normal'));

            $row['calories'] = isset($row['calories']) ? $row['calories'] : 0;
            echo $this->tag->cell(number_format($row['calories'],0), array('class'=>'text-center', 'style'=>'font-weight:normal'));

            $row['kudos'] = isset($row['kudos']) ? $row['kudos'] : 0;
            echo $this->tag->cell(number_format($row['kudos'],0), array('show'=>SHOW_KUDOS,'class'=>'text-center', 'style'=>'font-weight:normal'));

            $row['achievement_count'] = isset($row['achievement_count']) ? $row['achievement_count'] : 0;
            echo $this->tag->cell( number_format($row['achievement_count'],0), array('class'=>'text-center', 'style'=>'font-weight:normal'));
            echo $this->tag->cell( number_format($row['commute'],0), array('class'=>'text-center', 'style'=>'font-weight:normal'));
            echo $this->tag->close('tr');
          }
?>
          </tbody>
        </table>
      </div>
<?
        }
      }
?>
