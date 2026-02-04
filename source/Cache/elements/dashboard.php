<?php

  //Load activity types as possible columns
  $tempTotals = $totals;
  $this->set('month', $month);
  $this->set('dates', $dates);
  $this->set('showMonthNav', false);
?>

<div class="container" style="margin-left:20px">
 <?=$this->element('dateNav');?>
    <ul class="nav nav-tabs active" style="width:100%; margin-top:10px; margin-bottom:20px; border-bottom:1px solid #dee2e6">
      <li class="nav-item in " >
        <a class="nav-link active" data-toggle="tab" href="#summary">summary</a>
      </li>
     <li class="nav-item in " >
        <a class="nav-link" data-toggle="tab" href="#activities">activities</a>
      </li>
      <li class="nav-item in " >
        <a class="nav-link" data-toggle="tab" href="#highlights">highlights</a>
      </li>
      
    </ul>
    <div class="tab-content " style="min-height:800px">
      <div class="tab-pane   tabPanelContainer active" id="summary">
        <?php 
          //$this->set('totals', $tempTotals); 
          $this->set('chunkLabels', array(
            'week'=>$fromDateWeek, 
            'month'=>date('M-y',strtotime($fromDateMonth)), 
            'year'=>date('Y', strtotime($fromDateYear))
          ));
        ?>
        <?= $this->element('activitiesProgress');?>
        <?= $this->element('activitiesBudget');?>
        <?= $this->element("activitiesTotals");?>  
      </div>
      <div class="tab-pane   tabPanelContainer " id="activities">
<?

     foreach($records as $l=>$record)
        {
          echo '<div class="row rowSpacing20" style="margin-left:0px">';
?>           
          <div class="col-sm-6" style="padding-left: 0px">
            One of two columns
          </div>
         
          <div class="col-sm-3">
<?
             $json = $record['streams'];
              

              //get map
              
                $temp = $json[1]->data;
                $points = array();
                foreach($temp as $index=>$ps)
                {
                  $points[$index]['lat'] = $ps[0];
                  $points[$index]['lng'] = $ps[1];
                }
             
                $this->set('points', $points);
              $this->set('height', '200');
              $this->set('width', '200px');
              
               $this->set('zoom', '10');
              //$this->set('width', '100%');
              
              $this->set('mapDiv', 'map'.$l);
              echo $this->element('omMapPath');
     
          echo '</div>';
          echo '</div>';
 
        }
  ?>



      </div>     
      <div class="tab-pane  tabPanelContainer " id="highlights">
        <h4>longest this year</h4>
<?

        $this->set('records', $farthestThisYear);
        echo $this->element('activitiesRecordList');
?>
      <br>
       <h4>longest this month</h4>
<?

        $this->set('records', $farthestThisMonth);
        echo $this->element('activitiesRecordList');
?>
      </div>
    </div>
 
</div>

<div class="container" style="padding-left:0px">
