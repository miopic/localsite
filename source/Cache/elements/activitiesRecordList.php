<?php
    $dateFormat = isset($dateFormat) ? $dateFormat : 'Y-m-d H:i D';

    
    $headings = array
    (
    'date'     => array('class'=>' table-dark', 'style'=>'background-color:#3498DB'),
    'activity'  => array('class'=>' table-dark text-center ', 'style'=>'background-color:#3498DB'),
    'name'  => array('class'=>'table-dark text-left', 'style'=>'background-color:#3498DB'),
    'moving'  => array('class'=>'table-dark text-end  text-end', 'style'=>'background-color:#3498DB'),
    'distance'  => array('class'=>'text-center ', 'type'=>'icon', 'value'=>ICON_DISTANCE, 'style'=>'background-color:#3498DB'),
    'rate'      => array('class'=>'text-center ', 'type'=>'icon','style'=>'background-color:#3498DB', 'value'=>ICON_SPEED),
    'altitude'  => array('class'=>'text-center','type'=>'icon',  'value'=>ICON_ALTITUDE, 'style'=>'background-color:#3498DB'),
    'Calories'  => array('class'=>'text-center', 'type'=>'icon', 'value'=>ICON_CALORIES, 'style'=>'background-color:#3498DB'),
    'Kudos'     => array('class'=>'text-center ','type'=>'icon',  'value'=>ICON_KUDOS, 'show'=>SHOW_KUDOS, 'style'=>'background-color:#3498DB'),
    'Achieve'   => array('class'=>'text-center ','type'=>'icon',  'value'=>ICON_ACHIEVEMENTS, 'style'=>'background-color:#3498DB')
    );

    $rowOptions = array
    (
        'start_date_local' => array('style'=>'width:100px'),
        'activitytype_id'       => array('class'=>'text-center','style'=>'width:40px'),
         'name'				  => array('style'=>'width:300px'),
        'moving_time'           => array('class'=>'text-end', 'style'=>'width:40px'),
        'distance'              => array('class'=>'text-center', 'style'=>'width:40px'),
        'rate'              => array('class'=>'text-center', 'style'=>'width:40px'),
        'total_elevation_gain'  => array('class'=>'text-center' , 'style'=>'width:40px'),
        'calories' => array('class'=>'text-center', 'style'=>'width:40px'),
        'kudos_count'                 => array('class'=>'text-center','show'=>SHOW_KUDOS , 'style'=>'width:40px'),
        'achievement_count'     => array('class'=>'text-center', 'style'=>'width:40px' ),

    );

  foreach($records as $index=>$row)
	{
    $whichYear = date('Y',strtotime($records[$index]['start_date_local']));
    $whichActivity = $records[$index]['activitytype_id'];
    $server = $_SERVER['HTTP_HOST'];
    $recordLink = 'http://'.$server .'/rule10/activities/view/'.$records[$index]['id'];
    $stravaLink = 'https://www.strava.com/activities/'.$records[$index]['activity_id'];
    $records[$index]['start_date_local'] = $this->html->link(date($dateFormat, strtotime($records[$index]['start_date_local'])), $recordLink);
    $typeID = $records[$index]['activitytype_id'];
    $records[$index]['rate'] = 0;
    $this->stopwatch->convertToUnits($records[$index], $measurementUnits);
    $records[$index]['activitytype_id'] = $this->html->link($this->html->image('types/'.$types[$records[$index]['activitytype_id']]['image'], array('height'=>'20', 'width'=>'20')), $stravaLink,array('target'=>'detail'));

    $records[$index]['moving_time'] = $this->stopwatch->elapsed($records[$index]['moving_time'], array('decimal'=>0));
    $records[$index]['total_elevation_gain'] = number_format($records[$index]['total_elevation_gain'],0);
    $records[$index]['calories'] = number_format($records[$index]['calories'],0);
    $records[$index]['kudos_count'] = number_format($records[$index]['kudos_count'],0);
    $records[$index]['achievement_count'] =  number_format($records[$index]['achievement_count'],0);

	}
  // are there records to output?
  if (isset($records)  && (count($records) > 0))
  {
?>
    <?=$this->tag->recordList($records, $rowOptions, $headings, 'table table-hover table-bordered table-striped ');?>
<?php
  }
?>
