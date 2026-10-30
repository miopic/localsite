<?php

class activitiesModel extends ModelClass
{
	const SQL_DAY = "DATE_FORMAT(start_date_local,'%Y-%m-%d') as day ";
	const SUM_FIELDS = 	"
  	`activities`.`activitytype_id` AS `activitytype_id`,
		SUM(`activities`.`moving_time`) AS `moving_time`,
		SUM(`activities`.`elapsed_time`) AS `elapsed_time`,
		SUM(`activities`.`distance`) AS `distance`,
		SUM(`activities`.`kilometers`) AS `kilometers`,
		SUM(`activities`.`miles`) AS `miles`,
		SUM(`activities`.`yards`) AS `yards`,
		(CASE
		    WHEN (SUM(`activities`.`moving_time`) <> 0) THEN (SUM(`activities`.`miles`) / (SUM(`activities`.`moving_time`) / 3600))
		    ELSE 0
		END) AS `mph`,
		(CASE
		    WHEN
		        ((`activities`.`activitytype_id` = 'Swim')
		            AND (SUM(`activities`.`yards`) <> 0))
		    THEN
		        (SUM(`activities`.`moving_time`) / (SUM(`activities`.`yards`) / 100))
		    WHEN (SUM(`activities`.`miles`) <> 0) THEN (SUM(`activities`.`moving_time`) / SUM(`activities`.`miles`))
		    ELSE 0
		END) AS `pace`,
    SUM(`activities`.`total_elevation_gain`) AS `total_elevation_gain`,
    SUM(`activities`.`total_elevation_gain_feet`) AS `total_elevation_gain_feet`,
    COUNT(0) AS `count`,
    SUM(`activities`.`calories`) AS `calories`,
    AVG(`activities`.`average_heartrate`) AS `average_heartrate`,
    (SUM((`activities`.`moving_time` * `activities`.`average_heartrate`)) / SUM(`activities`.`moving_time`)) AS `weighted_average__heartrate`";



	var $UPDATE_FIELDS = array( "id", "activity_id", "user_id", "athlete_id","activitytype_id", "api", "start_date", "start_date_local", " name", "distance", "distance_source", "moving_time", "elapsed_time", "total_elevation_gain", "total_elevation_loss", "start_elevation", "end_elevation",  "workout_type", "timezone", "utc_offset", "location_city", "location_state", "location_country",   "comment_count", "athlete_count",  "map_id", "polyline", "summary_polyline", "trainer", "commute",  "start_lat", "start_lng", "end_lat", "end_lng", "start_addr", "end_addr", "average_speed", "max_speed", "average_temp", "average_watts", "kilojoules", "device_watts", "has_heartrate", "average_heartrate", "max_heartrate", "elev_high", "elev_low", "upload_id", "external_id", "pr_count", "total_photo_count",  "description", "calories", "perceived_exertion", "device_name", "embed_token", "streamAltitude", "streamDistance", "streamHeart", "streamTime", "streamTemp", "streamVeloc", "created", "modified");


	var $sqlDateFields = array('all'=>'', 'day'=>'day', 'week'=> 'week', 'month'=>'month', 'quarter'=>'', 'year'=>'year');

	var $activityTypes = null;
  var $activityTypesName = 'activitytypes';
  var $gearName = 'gears';
  var $fromDate = null;
  var $toDate = null;
  var $order = 'ASC';
  var $group = null;
  var $fields = null;
	var $resultTypes = null;
  var $modelName = 'activities';
  var $graphTotals = null;
  var $orderDirection = 'DESC';
  var $name = 'activities';
	var $typeField = 'activitytype_id';
 	var $typesInResult = null;

  public function __construct()
  {
    parent::__construct();
    $this->activityTypes = array();
    return true;
  }
		
		public function typesInDateRange($fromDate = null, $toDate = null, $typeID = null)
		{
			if (is_null($fromDate)  || is_null($toDate))
			{
				return false;
			}
			$options = array();
			//Get distinct activitytypes
			$options['fields']  = "DISTINCT activitytype_id as activitytype_id";
			$options['order'] = "activities.activitytype_id ASC";
			$options['conditions'] = 'start_date_local >= "'. $fromDate  . '" AND start_date_local < "'. $toDate .'"';
			if (!is_null($typeID)  && ($typeID != 0) && ($typeID != ''))
			{
				$options['conditions']  .= ' AND activitytype_id = '."'".$typeID."'";
			}
			$temp = parent::find($options);
			if ($temp && (count($temp) > 0))
			{
				$this->typesInResult = array();
				foreach ($temp as $i => $data)
				{
					$this->typesInResult[$data['activitytype_id']] = array();
					$this->typesInResult[$data['activitytype_id']]['activitytype_id'] = $data['activitytype_id'];
				}
				return $this->typesInResult;
			}
			return false;
		}

		public function allByType($typeID = null)
		{
			$this->model = 'allbytype';
			$results = parent::find();
			$this->model = 'activities';
			return $results;
		}

		public function totalsByType($fromDate = null, $toDate = null, $typeID = null)
		{
			$fromDate = is_null($fromDate) ? DEFAULT_BIRTHDAY : $fromDate;
			$toDate = is_null($toDate) ? END_OF_TIME : $toDate;

			$fields = 'activitytype_id, '.self::SUM_FIELDS;

			$conditions = 'start_date_local >= "'.$fromDate.'" AND start_date_local < "'.$toDate.'"';
		  $conditions .= (!is_null($typeID)) && ($typeID > 0) ?  ' AND activitytype_id = '."'".$typeID."'" : '';
			$results = parent::find(array(
				'conditions'=>$conditions,
				'fields' =>  $fields,
				'group' => 'activitytype_id',
				'limit' => 1000
			));

			return $results;
		}
		public function daysByType($fromDate = null, $toDate = null, $typeID = null)
		{
			$this->model = 'daysbytype';
		
			$conditions = "day >= '".$fromDate."' AND day <= '".$toDate."'";
			$conditions .= !is_null($typeID) && ($typeID != 0)? " AND activitytype_id = "."'". $typeID. "'" : '';
			$results = parent::find(array(
				'conditions'=>$conditions,
				'limit' => 1000
			));
			$this->model = 'activities';
			return $results;
		}
		public function timeframeByType($timeframe = NULL, $fromDate = null, $toDate = null, $typeID = null)
		{
	
			$fields = self::SUM_FIELDS;
			
			switch ($timeframe)
			{
				case 'month':
					$fields = "DATE_FORMAT(`activities`.`start_date_local`,
                '%Y-%m-01') AS `month`, ".$fields;
        	$group = "DATE_FORMAT(`activities`.`start_date_local`,'%Y-%m-01') , `activities`.`activitytype_id`";
         	$order = "DATE_FORMAT(`activities`.`start_date_local`,'%Y-%m-01') , `activities`.`activitytype_id`";
					
					$conditions = "start_date_local >= '".$fromDate."' AND start_date_local < '".$toDate."'";
					$conditions .= !is_null($typeID) && ($typeID != 0)? " AND activitytype_id = "."'". $typeID. "'" : '';
				break;
				case 'year':
					$fields = "DATE_FORMAT(`activities`.`start_date_local`,
                '%Y-01-01') AS `year`, ".$fields;
        	$group = "DATE_FORMAT(`activities`.`start_date_local`,'%Y-01-01') , `activities`.`activitytype_id`";
         	$order = "DATE_FORMAT(`activities`.`start_date_local`,'%Y-01-01') , `activities`.`activitytype_id`";
							$conditions = "start_date_local >= '".$fromDate."' AND start_date_local < '".$toDate."'";
					$conditions .= !is_null($typeID) && ($typeID != 0)? " AND activitytype_id = "."'". $typeID. "'" : '';

				break;
			}

			$limit = 1000;
			$results = parent::find(array(
				'fields' => $fields,
				'conditions' => $conditions,
				'group'  => $group,
				'order'  => $order,
				'limit' => $limit
			));

			return $results;
		}

		
		public function yearsByType($fromYear, $toYear,  $typeID = null)
		{
				$this->model = 'yearsbytype';
				$conditions = "year >= ".$fromYear." AND year < ". $toYear." ";
				$conditions .= !is_null($typeID) && ($typeID > 0) ? 'AND activitytype_id = '."'".$typeID."'" : "";
				$results = parent::find(array(
					'conditions'=>$conditions,
				));
				$this->model = 'activities';
				return $results;
		}

		function weekTotals($fromWeek, $toWeek, $typeID)
		{
			$temp = $this->weekTotalsByType($fromWeek, $toWeek, $typeID);
			$totals = array();
			for ($i = count($temp) - 1; $i >= 0; $i--)
			{
				if(!isset($totals[$temp[$i]['week']]))
				{
				$totals[$temp[$i]['week']] = array(
					'total_elevation_gain'=>0,
					'moving_time'=>0,
					'elapsed_time'=>0,
					'distance'=>0,
					'count'=>0,
					'calories'=>0,);
				} 
				
				$totals[$temp[$i]['week']]['total_elevation_gain'] = $totals[$temp[$i]['week']]['total_elevation_gain'] + $temp[$i]['total_elevation_gain'];
			 	$totals[$temp[$i]['week']]['moving_time'] += $temp[$i]['moving_time'];				
				$totals[$temp[$i]['week']]['elapsed_time'] += $temp[$i]['elapsed_time'];
				$totals[$temp[$i]['week']]['distance'] += $temp[$i]['distance'];
				$totals[$temp[$i]['week']]['count'] += $temp[$i]['count'];
				
				$totals[$temp[$i]['week']]['calories'] += $temp[$i]['calories'];

			}
		
			return $totals;
		}
		function weekTotalsByType($fromWeek, $toWeek, $typeID = null)
		{			
			$this->model = 'weeksbytype';
			$conditions = "week >= '".$fromWeek."' AND week < '". $toWeek ."' ";
			if (!is_null($typeID) && ($typeID != '') && ($typeID !=0 ))
			{
				$conditions .= ' AND activitytype_id = '."'".$typeID."'";
			}
			
			$stuff = $this->find(array('conditions' => $conditions, 'limit'=>5000));

			$this->model = 'activities';
			return $stuff;
		}


    
 }
 ?>
