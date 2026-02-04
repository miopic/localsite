<?php
require_once('Controller.php');
class activitiesController extends Controller
{
	
	const DEFAULT_FROM_DATE = '1988-01-01';
	const DEFAULT_TO_DATE = '2100-01-01';
	const DEFAULT_TIMEFRAME = 'month';
	const DEFAULT_FILTER_TYPE_ID = 0;
	const DEFAULT_METRIC = DEFAULT_METRIC;
	const RECENT_RECORDS = 6;
	const LOG_LOOKBACK = 19;   //for the logbook action this sets the number of weeks from which to retrieve database


	var $action = null;
	var $app = null;
	var $html = null;
	var $stopwatch = null;
	var $statusTotals = array();
	var $typesFoundInRecords = null;
	var $colors = null;
  var $otherModels = array('activitytypes', 'users');
	var $helpers = array( 'html', 'stopwatch');
  var $components = array('strava', 'geocode', 'time');
  //var $allow = array('year', 'month', 'logbook','view', 'fetch');
	var $types;
	var $metrics = array('distance' => 'Distance', 'moving_time' => 'Duration', 'total_elevation_gain_feet' => 'Ascent', 'calories' => 'Calories', 'weighted_average__heartrate'=>'Avg Heart Rate');

	var $metric = self::DEFAULT_METRIC;
	var $typeID = self::DEFAULT_FILTER_TYPE_ID;
	var $fromDate = null;
	var $toDate = null;
	var $timeframe =  self::DEFAULT_TIMEFRAME;
	var $currentDate = null;
	var $textSearch = null;
	var $user = null;

	var $goals = array( 
		2024 => array(
			'units' => DEFAULT_UNITS,
			SWIMMING => 260000,
			WALKING => 366,
			CYCLING => 7500
		));


	public function __construct($controllerName, $controllerAction)
	{
		global $DEFAULT_LOCATION;
    parent::__construct($controllerName, $controllerAction);
		$x = debug_backtrace();
		$this->user = $this->users->getByID(2);


		$this->types = $this->activitytypes->find();

    $typeList = array();
    foreach($this->types as $key=>$data)
    {
      $typeList[$key] = $data['activitytype_id'];
    }
		$typeList[0] = 'All';
		ksort($typeList);

    $this->strava->activityModel = 'activities';
    $this->set('elements', $typeList);
		$this->set('metrics', $this->metrics);
		$this->set('types', $this->types);
	}
	
	public function getActivityNameByID($id = 1)
	{
		$found = false;
		return $this->types[$id]['name'];
	}
	public function beforeAction()
	{
		parent::beforeAction();
    
    //$this->strava->Gear = $this->activities->gears;
    $this->strava->userModel = 'user';
    if (!is_null($this->user))
    {
	    $this->strava->refreshToken = $this->user['refresh_token'];
	    $this->strava->accessToken = $this->user['access_token'];
	    $this->strava->expiresAt = $this->user['expires_at'];

			 
      $timeRemaining = $this->strava->timeRemaining($this->user['expires_at']);
      if (($timeRemaining < HOUR) && !is_null($this->user))
      {
				$rtoken = $this->strava->refreshAccessToken($this->user);
       	if ($rtoken)
        {
          if ($this->users->update($this->user))
					{
						//echo '<h1>APP: token updated</h1>';
					} else {
						echo '<h1>ERROR: token NOT updated</h1>';

					}
        } else
        {
          $this->app->pushAlert("Unable to retrieve a Strava token");
          return false;
        }

      $this->app->pushAlert('Token refreshed');
      $this->set('expiresAt', date('Y-m-d H:i',$this->user['expires_at']));
      $timeRemaining = ($this->user['expires_at'] - time())/HOUR;
      $this->set('timeRemaining', date('Y-m-d H:i',$this->user['expires_at']));
      } else
      {
      	// token did not need refreshing
      }
    }
			
				$fromY = date('Y');
				$toY = date('Y', strtotime($fromY." +1 year"));
				$this->statusTotals['year']  =  $this->activities->yearsByType( $fromY, $toY , null);
				foreach($this->statusTotals['year'] as $key=>$data)
				{
					
						$dist =  round(($data['miles'] ),0);
					
					switch ($data['activitytype_id'])
					{
						case CYCLING:
							$img = $this->html->image(LOCATION_TYPEIMAGES.IMG_CYCLING, array('height'=>20, 'width'=>20));
							$this->status[STATPOSITION_CYCLING] =  $img."&nbsp;" . number_format($dist);
						break;
						case SWIMMING:
							$img = $this->html->image(LOCATION_TYPEIMAGES.IMG_SWIMMING, array('height'=>20, 'width'=>20));
							$this->status[STATPOSITION_SWIMMING] = $img."&nbsp;".number_format($dist);
						break;
						case WALKING:
						  $img = $this->html->image(LOCATION_TYPEIMAGES.IMG_WALKING, array('height'=>20, 'width'=>20))."&nbsp;";
							$this->status[STATPOSITION_WALKING] = $img ."&nbsp;".number_format($dist);
						break;
						case SKIING:
						$img = $this->html->image(LOCATION_TYPEIMAGES.IMG_SKIING, array('height'=>20, 'width'=>20))."&nbsp;";

							$this->status[STATPOSITION_SKIING] .= $img.'&nbsp;'.number_format($dist);
						break;
					}
				}
				if ($this->request == POST)
		    {
					$this->textSearch = isset($this->data['textSearch']) ? $this->data['textSearch'] : null;
					$this->fromDate = isset($this->data['fromDate']) ? $this->data['fromDate'] : null;
					$this->toDate = isset($this->data['toDate']) ? $this->data['toDate'] : null;
					$this->currentDate = isset($this->data['currentDate']) ? $this->data['currentDate'] : null;

					$this->typeID = !isset($this->data['typeID']) || ($this->data['typeID'] == 0) ? null : $this->data['typeID'];
					$this->timeframe = (isset($this->data['timeframe']) and !(is_null($this->data['timeframe']))) ? $this->data['timeframe'] : self::DEFAULT_TIMEFRAME;
					$this->metric = (isset($this->data['metric']) and !(is_null($this->data['metric']))) ? $this->data['metric'] :  self::DEFAULT_METRIC ;
				} else {
					
				}
	}
	public function afterAction()
	{

	}
	
	


	public function fetch()
	{
		//get the last activity saved to the db
		$temp = $this->activities->find( array('fields'=>'id, timezone, start_date_local', 'order'=>'start_date_local DESC', 'limit'=>1));
		foreach($temp as $key=>$data)
		{
			$lastActivity = $data;
			break;
		}
		
		//sets the date in strava after which I want to retrieve strava activities

		$this->strava->after = strtotime($lastActivity['start_date_local']);
		$defaultTimeZone = date_default_timezone_get();
		$whereTimezoneStarts = strpos($lastActivity['timezone'], ' '); 

		$exctractedTimezone = substr($lastActivity['timezone'],$whereTimezoneStarts+1,1000);
		date_default_timezone_set($exctractedTimezone);
		
		//retrieve any new activities
		$temp = $this->strava->activities();

		$stravaActivities = null;
		if (!$temp)
		{
			 if ($this->strava->errorMessage != '')
			 {
			 	echo "STRAVA: Strava returned an error-> ".$this->strava->errorMessage;
				exit;
			 } else
			 {
		
			 }

		 //strava activities found
		} else {

			 $stravaActivities = $this->strava->results;
		}
		
		if ((!is_null($stravaActivities)) && (count($stravaActivities) > 0))
 		{	
		 	foreach ($stravaActivities as $r=>$foo)
		 	{
		 	
		 		$stravaActivities[$r] = $foo['activities'];
		 		unset($foo['activities']);	
		 	}
	
			$j = 0;
			$savedRecords = array();
			if (!is_null($stravaActivities))
			{	
				foreach($stravaActivities as $stravaActivity)
				{
					$detail = $this->strava->activity($stravaActivity['activity_id']);
					if ($detail)
					{
						$tempJSON = isset($detail['Activity']) ? $detail['Activity']['jsonStreams'] : "";
						
						$detail = $detail['activities'];
						
						$segment_efforts = isset($detail['segment_efforts']) ? $detail['segment_efforts'] : array();
						unset($detail['segment_efforts']);
						unset($detail['splits_metric']);
						unset($detail['splits_standard']);
						unset($detail['laps']);
						unset($detail['stats_visibility']);

						if ($tempJSON != "")
						{
							$this->strava->parseStravaStreams($tempJSON, $detail);
						}
						//Geography: google geocode						
						$this->fillGeo($detail);
						$insertedID = $this->activities->insert($detail);
						if ($insertedID)
						{
							//need to retrieve the record because the databas
							$savedRecords[$j] = $this->activities->getByID($insertedID);
							$j = $j + 1;
						}			
					}
				}
			}
			$this->set('recordList', $savedRecords);
		}
	}

	public function view($id = null)
	{


		$aMonthAgoForStrava = strtotime("-1 year");
		$fromDateForStrava = strtotime("2019-05-01");
		$somethingHasChanged = false;
		//If no id was passed find the most recent activity
		if ($this->request == POST)
		{
			$gotoDate = $this->data['gotoDate']." 0:00";

			
			$options = array('limit'=>1, 'conditions'=> "start_date_local > ". "'".$gotoDate."'", 'order'=>'activities.id ASC');
		} elseif (!is_null($id) && ($id != ''))
		{
			$options = array('fields'=>'*','conditions'=>"activities.id = ".$id." OR activities.activity_id = '".$id."'", 'limit'=>1, 'order'=>'activities.id DESC');
    } else
    {
			$options = array( 'limit'=>1, 'order'=>'activities.id DESC');
    }
		$record =$this->activities->find($options);

    if (!isset($record[0]))
		{
			echo '<h1>Record not found: '.get_class($this).'</h1>';
			exit;
		} else {
      $record = $record[0];
      $id = $record['id'];
      $this->set('record', $record);
    }
		$points = $this->strava->decode($record['polyline']);
		$this->set('points', $points);
    //find the record that is prior to this one in time
		$previous =$this->activities->find(array('conditions'=>'activities.start_date_local < "'.$record['start_date_local'].'"', 'limit'=>1, 'order'=>'activities.start_date_local DESC'));
		$this->set('previous',array_pop($previous));
    //find the record that is after this one in time
		$next =$this->activities->find(array('conditions'=>'activities.start_date_local > "'.$record['start_date_local'].'"', 'limit'=>1, 'order'=>'activities.start_date_local ASC'));
		$this->set('next', array_pop($next));
    $last = $this->activities->find(array('limit'=>1, 'order'=>'activities.start_date_local desc'));
		$this->set('last',array_pop($last));
    $first = $this->activities->find(array('limit'=>1, 'order'=>'activities.start_date_local asc'));
		$this->set('first',array_pop($first));

		/*
    //Get the strava activity associated with this record, to see if any data has changed.
		//But only if the activity is newer than a month or earlier than $fromDateForStrava "blackout window for Strava requests"
		if($this->auth->loggedIn())
		{
			$currentRecordTime = strtotime($record['start_date_local']);
			$changes = array();
			$changes['id'] = $record['id'];
    		$stravaActivity = isset($record['activity_id']) ? $this->strava->activity($record['activity_id']) : null;

			if (!$stravaActivity   )
	 		{
				$message = json_decode($this->strava->lastJSON);
				$this->set('statusMessage','<span style="color:red;font-weight: bold;magin-left:40px;">'.'Strava Message: '. $message->message.'</span>');

			} else
			{

					$stravaActivity = $stravaActivity['activities'];
				//$stravaEfforts = isset($stravaActivity['activities']['segment_efforts']) ? $stravaActivity['activities']['segment_efforts'] : array();
				//Workaround for a weirdness
				if (!isset($record['polyline']))
				{
					$record['polyline'] = "";
				}


			}
			
		    if ($somethingHasChanged)
		    {
		    	unset($record['day']);
		    	unset($record['week']);
		    	unset($record['month']);
		    	unset($record['year']);
		        $result = $this->activities->update($record);
		        if ($result)
		        {
		          $this->set('statusMessage','Record: '. $record['id']. ' has been successfully updated!');
		        } else
		        {
		          $this->set('statusMessage','Record: '. $record['id']. ' was NOT successfully updated!');
		          $this->app->clearAlerts();
		          $this->app->pushAlert($this->activities->SQL);
		          $this->app->pushAlert($this->activities->error);
		        }
		      }
		
		} */

	}

	
  public function edit($id = null)
  {

    if ($this->request == POST)
    {

    	$record = $this->activities->getByID($this->data['id']);
  

    	//metric?
    	if ($this->units == UNITS_METRIC)
    	{
    		//if its metric and swimming than the distance is meters.  if its metric and anything else its kilometers and is converted to meters
    		$this->data['distance'] = $this->data['activitytype_id'] == SWIMMING ? $this->data['distance'] :  $this->data['distance']  * METERS_PER_KILOMETER ;
    	} else 
    	//UNITS_STATUTE
    	{ 

    		//if its statues and swimming than the distance is yards convert to meters.  if its statues and anything else its miles and is converted to meters
    		$this->data['distance'] = $this->data['activitytype_id'] == SWIMMING ? $this->data['distance'] * METERS_PER_YARD:  $this->data['distance']  * METERS_PER_MILE ;
    			debug('statute '.	$this->data['distance'] );
    			exit;
    	}
      $s = $this->activities->update( $this->data);

      $record = $this->activities->getByID($this->data['id']);
      if ($s)
      {
      	$this->redirect('/app/activities/view/'.$this->data['id']);
      	//convert to meters as needed
      	//check to see if distance changed
      } else
      {
        echo 'not updated<br>';
      }
    // request GET
    } else {
    	if ($id == null)
    	{
      	echo 'NO ID SPECIFIED FOR EDIT';exit;
    	} else 
    	{
    		$record = $this->activities->getByID($id);

    		
    	}
    }
    
    $this->set('record', $record);
    $this->set('id', $id);
    
    //get the record to edit
  }

  // strava_oauth: get a token and a refresh token via a strava page for user permission
  public function auth_strava()
  {
		 $authUrl = $this->strava->getAuthUrl();
		 //debug('From activities controller: '.$authUrl);
		 //exit;
      $this->redirect($authUrl);
  }

	// strava_callback: is call back function from a request to authorize
	//https://www.strava.com/oauth/authorize?client_id=5532&redirect_uri=https://multifork.com/activities/strava_callback&response_type=code&scope=activity:read_all
	public function strava_callback()
	{
		if (isset($_GET['error']) && ($_GET['error'] == 'access_denied'))
		{
		   return false;
		}

		//Get the code from the key value parameters in the call back
		$jsonObj = $this->strava->getAccessToken($_GET['code']);

		//Add error trapping here
		$this->currentUser['user']['id'] = 2;
		$this->currentUser['user']['expires_at'] = $jsonObj->expires_at;
		$this->currentUser['user']['access_token'] = $jsonObj->access_token;
		$this->currentUser['user']['refresh_token'] = $jsonObj->refresh_token;
		debug($this->currentUser['user']);

		if ($this->users->update($this->currentUser['user']))
		{
			debug('user saved');
		} else {
			debug('user NOT saved');
		}
		 debug($this->currentUser);
	}


	


  public function search($fromDate = null, $toDate = null, $typeID = null)
  {
		$conditions = "";
		$searchTotals = null;
		$activityTypeList[0] = 'All';
		$selectedValue = 0;
		if ($this->request == GET)
		{

			$this->fromDate = is_null($fromDate) || ($fromDate == "")? $this->time->getWeek(date(YMD)) : $fromDate;
			$this->toDate = is_null($toDate) || ($toDate = "")? date(YMD, strtotime($fromDate." +1 week")) : $toDate;
			$this->typesID  =  $typeID;
			$this->textSearch = null;
		}
		
		$conditions = "activities.start_date_local >= '".$this->fromDate."'";
		$conditions .= " AND activities.start_date_local < '".$this->toDate."'";
		$searchTotals = $this->activities->totalsByType($this->fromDate, $this->toDate,  $this->typeID);
		$searchTotals = count($searchTotals) > 0 ? $searchTotals : null;
		$records = $this->activities->find(array('conditions'=>$conditions, 'order' =>'activities.start_date_local DESC', 'limit'=>50));


		if (($this->textSearch != '') && (!is_null($this->textSearch)))
		{
			$conditions = "activities.name like '%".$this->textSearch."%'";
		}
		$records = $this->activities->find(array('conditions'=>$conditions, 'order' =>'activities.start_date_local DESC', 'limit'=>50));

		$toDate = count($records) > 0 ? date(YMD, strtotime($records[count($records)-1]['start_date_local'])) : date(YMD);
		$fromDate =  count($records) > 0 ? date(YMD, strtotime($records[0]['start_date_local'])) : date("Y-01-01");

		$this->set('count', $this->activities->count());
		$this->set('conditions', $conditions);
    $this->set('records', $records);
    $this->set('textSearch', $this->textSearch);
    $this->set('fromDate', $this->fromDate);
    $this->set('toDate', $this->toDate);
    $this->set('activityTypeList', $activityTypeList);
		$this->set('selectedValue', $selectedValue);
		$this->set('searchTotals', $searchTotals);
		$this->set('typeID', $this->typeID);
		
  }
  public function day($date = null)
  {
  	if ($this->request == GET)
		{
  		$this->fromDate = is_null($date) ? date('Y-m-d') : $date;
  	} else {
  		$this->fromDate = $this->data['currentDate'];
  	}
  	 $this->toDate = date("Y-m-d", strtotime($this->fromDate. " +1 day"));

  	
  	$records = $this->activities->find(array('conditions'=>'start_date_local >= "'.$this->fromDate.'" AND start_date_local < "'.$this->toDate.'"'));
  	$dayTotalsByType  = $this->activities->daysByType($this->fromDate, $this->toDate,$this->typeID);
  	$this->set('dayTotalsByType', $dayTotalsByType);	
  	$this->set('fromDate', $this->fromDate);
  	$this->set('records', $records);
  	$this->set('typeID', null);
  	$this->set('metric', "distance");
  	$this->set('currentDate', $this->fromDate);
		$this->set('increment', 'day');
		$this->set('fromDate', $this->fromDate);
		$this->set('toDate', $this->toDate);

  }

  public function logbook($date = null, $typeID = null, $metric = null)
  {
  		$weeksBack = 52;
		if ($this->request == GET)

		{
			$this->currentDate = is_null($date) ? date(YMD) : $date;
			$this->typeID = is_null($typeID) ? self::DEFAULT_FILTER_TYPE_ID : $typeID;
			$this->metric = is_null($metric) ? self::DEFAULT_METRIC : $metric;
			$this->currentDate = isset($this->currentDate) ? $this->currentDate : date(YMD);
			//The current week starting on monday
			$this->currentDate = is_null($date) || ($date == '') ? $this->time->getWeek(date(YMD)) : $this->time->getWeek(date(YMD, strtotime($date)));
		}

	
    $this->fromDate = $this->time->getWeek(date(YMD, strtotime($this->currentDate." -".$weeksBack." weeks")));
		$this->toDate = date(YMD, strtotime($this->currentDate." +7 days"));
    $graphWeeksByActivity = $this->activities->weekTotalsByType($this->fromDate, $this->toDate, $this->typeID);
		//Get the totals for each week without regard to activity type, which will appear in each week row of the page
		$totalsByWeek = $this->activities->weekTotals($this->fromDate, $this->toDate, $this->typeID);


    $order = 'start_date_local';
    $conditions = 'start_date_local >= "'.$this->fromDate.'" AND start_date_local < "'.$this->toDate.'"';
    if (($this->typeID != 0) && ($this->typeID != ''))
    {
      $conditions .= " AND activitytype_id = "."'".$this->typeID."'";
    }
    $fields = 'id, activitytype_id, start_date_local,week, distance, moving_time, total_elevation_gain, calories';
    $records = $this->activities->find(array('fields'=>$fields,'conditions'=>$conditions, 'order'=>'start_date_local, activitytype_id', 'limit'=>5000));
		$typesFoundInRecords = $this->activities->typesInDateRange($this->fromDate, $this->toDate, $this->typeID);
		$graphData = $this->prepGraphData($graphWeeksByActivity,'week', $this->fromDate, $this->toDate, $this->metric , 'week',$graphIncrement = 'week',  'm-d', 'week');
		$this->set('colors', $this->colors ? $this->colors : '');

		$itemCount = count($totalsByWeek);
		//put records in a day bucket of the week.
		//for($i = 0; $i < count($records); $i++)
		foreach ($records as $i => $record)
		{
			
			$weekOfRecord = $this->getWeek($records[$i]['week']);
	
			$day = date(YMD, strtotime($records[$i]['start_date_local']));

			if (!isset($totalsByWeek[$weekOfRecord]))
			{
				$totalsByWeek[$weekOfRecord]['week'] = $weekOfRecord;
				$totalsByWeek[$weekOfRecord]['total_elevation_gain'] = 0;
				$totalsByWeek[$weekOfRecord]['moving_time'] = 0;
				$totalsByWeek[$weekOfRecord]['distance'] = 0;
				$totalsByWeek[$weekOfRecord]['count'] = 0;
				
				$totalsByWeek[$weekOfRecord]['calories'] = 0;
			}
			if (!isset($totalsByWeek[$weekOfRecord]['activities'][$day]))
			{
				$totalsByWeek[$weekOfRecord]['activities'][$day] = array();
			}
			$totalsByWeek[$weekOfRecord]['activities'][$day][count($totalsByWeek[$weekOfRecord]['activities'][$day])] = $i;
		}

		krsort($totalsByWeek);
		$this->set('metric',$this->metric);
		$this->set('fromDate', $this->fromDate);
		$this->set('toDate', $this->toDate);
		$this->set('currentDate', $this->currentDate);
		$this->set('data', $graphData);
    $this->set('typeID', $this->typeID);
    $this->set('totals', $totalsByWeek);
    $this->set('timeframe', 'week');
    $this->set('increment', '1 week');
		$this->set('$weeksBack',$weeksBack);
    $this->set('link',APP_NAME.'/activities/logbook/');
		$this->set('records', $records);
  }

  public function week($currentDate = null)
	{

		if ($this->request == GET)
		{
			$currentDate = is_null($currentDate) || ($currentDate == "")? $this->time->getWeek(date(YMD." D")) : $this->time->getWeek($currentDate);
			$this->fromDate = date(YMD." D",strtotime($currentDate));
			$this->toDate = date(YMD." D",strtotime($this->fromDate." + 7 days"));

		}
		$date = array();

		$i = $this->fromDate;
		while (strtotime($i) < strtotime($this->toDate))
		{
			$dates[$i] = null;
			$i = date(YMD." D", strtotime($i." +1 day"));
		}
		debug($dates);
		$grandTotalDateFormat = "M d";
		exit;
	}



	public function month($currentDate = null, $typeID = null, $timeframe = null, $metric = null)
  {
		if ($this->request == GET)
		{
			$this->currentDate = is_null($currentDate) || ($currentDate == '') ? date(YMD) : $currentDate;
			$this->typeID = is_null($typeID) ? self::DEFAULT_FILTER_TYPE_ID : $typeID;
			$this->timeframe = is_null($timeframe) ? self::DEFAULT_TIMEFRAME : $timeframe;
			$this->metric = is_null($metric) ? self::DEFAULT_METRIC : $metric;
		}
		$this->fromDate = date(MONTH, strtotime($this->currentDate));  //set the fromDate to the beginning of the year
		$dates = $this->time->month($this->fromDate);
		$grandTotalDateFormat = "M 'y";
		$this->toDate = date(YMD, strtotime($this->currentDate. " +1 day"));
		$monthTotalsByType  = $this->activities->monthsByType($this->fromDate, $this->toDate,$this->typeID);

		$order = "DATE_FORMAT(start_date_local, '%Y-%m-%d'), activitytype_id ASC";
		$options = array(
			'fields'=>"id, user_id, start_locality, activitytype_id,  activity_id, start_date_local, name, moving_time, elapsed_time, distance, miles, kilometers, average_temp, total_elevation_gain, total_elevation_gain_feet,  calories, yards, mph, pace ",
			'conditions'=>'start_date_local >= "'.$this->fromDate.'" AND start_date_local < "'.$this->toDate.'"', '
			order'=>$order,
			'limit'=>1000
		);

		if ($this->typeID > 0)
		{
			$options['conditions'] .= ' AND activitytype_id = '.$this->typeID;
		}
		$records = $this->activities->find($options);

		//Collect the data from the records to graph
		if ($this->timeframe == 'year')
		{
			$graphDateFormat = 'z';
		} elseif ($this->timeframe == 'month')
		{
			$graphDateFormat = 'd';
		} else
		{
			$graphDateFormat = 'D';
		}




		$graphRecords = $this->prepGraphData($records,'start_date_local', $this->fromDate, $this->toDate, $this->metric, $this->timeframe, 'day', $graphDateFormat);
		$this->set('colorsForRecords', $this->colors);


		if ($this->timeframe == 'month')
		{
			$calStart = $this->time->getWeek($this->fromDate);
			$temp = date('Y-m-t',strtotime($this->fromDate));
			$lastCalWeek = $this->time->getWeek($temp);
			$calLast = date('Y-m-d',strtotime($this->time->getWeek($lastCalWeek)." +6 days"));

		}
		$this->set('metric', $this->metric);
    $this->set('dates', $dates);

		//$this->set('byMonth', $yearByMonth);
    $this->set('records', $records);
		$this->set('graphRecords', $graphRecords);
		$this->set('monthTotalsByType', $monthTotalsByType);
    $this->set('fromDate',$this->fromDate);
    $this->set('toDate',$this->toDate);
		$this->set('currentDate', $this->currentDate);
    $this->set('typeID', $this->typeID);
    $this->set('increment', '1 day');
  }

  
	public function year($currentDate = null, $typeID = null, $timeframe = null, $metric = null)
  {
		if ($this->request == GET)
		{
			$this->currentDate = is_null($currentDate) ? date(YMD) : $currentDate;
			$this->typeID = is_null($typeID) ? self::DEFAULT_FILTER_TYPE_ID : $typeID;
			$this->timeframe = is_null($timeframe) ? self::DEFAULT_TIMEFRAME : $timeframe;
			$this->metric = is_null($metric) ? self::DEFAULT_METRIC : $metric;
		}

	 //POST and GET Handling Moved to before action
	 $this->timeframe = 'year';
		$this->fromDate = date(YEAR, strtotime($this->currentDate));  //set the fromDate to the beginning of the year
		$dates = $this->time->year($this->fromDate);
		$grandTotalDateFormat = "Y";
		$fromYear = date('Y', strtotime($this->fromDate));
		$toYear = $fromYear+1;
		$yearToDateByType = $this->activities->yearsByType($fromYear, $toYear, $this->typeID);

		$this->toDate = date(YMD, strtotime($this->currentDate. " +1 day"));
		$yearByMonth= $this->activities->monthsByType($this->fromDate, $this->toDate,$this->typeID);
		
		$yearToDate = null;

		$fields = "id, user_id, activitytype_id, week,start_locality, activity_id, start_date_local, name, moving_time, elapsed_time, distance, miles, kilometers, yards, mph, pace,  average_temp, total_elevation_gain, total_elevation_gain_feet, calories ";
		$order = "DATE_FORMAT(start_date_local, '%Y-%m-%d'), activitytype_id ASC";
		$options = array(
			'fields'=>$fields,
			'conditions'=>'start_date_local >= "'.$this->fromDate.'" AND start_date_local < "'.$this->toDate.'"', '
			order'=>$order,
			'limit'=>1000
		);

		if ($this->typeID > 0)
		{
			$options['conditions'] .= ' AND activitytype_id = '."'".$this->typeID."'";
		}

		$records = $this->activities->find($options);
		$this->set('records', $records);


		//Collect the data from the records to graph
		if ($this->timeframe == 'year')
		{
			$graphDateFormat = 'M-d';
		} elseif ($this->timeframe == 'month')
		{
			$graphDateFormat = 'd';
		} else
		{
			$graphDateFormat = 'D';
		}

		$graphRecords = $this->prepGraphData($records,'start_date_local', $this->fromDate, $this->toDate, $this->metric, $this->timeframe, 'day', $graphDateFormat);
		$this->set('colorsForRecords', $this->colors);
		$graphRecordsCumm = array();

		for ($k = 0; $k < count($graphRecords); $k++)
		{
			$graphRecordsCumm[$k] = array();
			if ($k == 0)
			{
				$graphRecordsCumm[$k] = $graphRecords[$k];
			} elseif (($k == 1))
			{
				for($m = 0; $m < count($graphRecords[$k]); $m++)
				{
					$graphRecordsCumm[$k][$m] =  $graphRecords[$k][$m];
				}
			} else 
			{
				for($m = 0; $m < count($graphRecords[$k]); $m++)
				{

					if ($m == 0)
					{
						$today = date(YMD);
						$whichYear = date('Y-',strtotime($this->fromDate));
						$dataDate = $whichYear."-" .$graphRecords[$k][0];
						$answer = strtotime(date(YMD, strtotime($whichYear.$graphRecords[$k][0])))  <= strtotime(date(YMD)) ? "true" : "false";
					}
					if (strtotime(date(YMD, strtotime($whichYear.$graphRecords[$k][0])))  <= strtotime(date(YMD)))
					{
						if ($m > 0)
						{
							$prev = (float) $graphRecordsCumm[$k-1][$m];
							$current = (float)  $graphRecords[$k][$m];
							$graphRecordsCumm[$k][$m] =  $prev + $current;
						} else
						{
							$graphRecordsCumm[$k][$m] = $graphRecords[$k][$m];
						}
						} else 
						{
								$graphRecordsCumm[$k][$m] = $m == 0 ?  $graphRecords[$k][$m] : 0;
						}
					}
				}
			}

		$graphMonths = $this->prepGraphData($yearByMonth, 'month', date("Y-01-01",strtotime($this->fromDate)), date(YMD,strtotime($this->toDate. "-1 day")), $this->metric, 'year', 'month', 'M');
		$this->set('colorsForMonths', $this->colors);
		//calculate calendar window if timeframe = MONTH
		if ($this->timeframe == 'month')
		{
			$calStart = $this->time->getWeek($this->fromDate);
			$temp = date('Y-m-t',strtotime($this->fromDate));
			$lastCalWeek = $this->time->getWeek($temp);
			$calLast = date('Y-m-d',strtotime($this->time->getWeek($lastCalWeek)." +6 days"));

		}
		$this->set('metric', $this->metric);
    $this->set('dates', $dates);

		$this->set('byMonth', $yearByMonth);
    $this->set('records', $records);
		$this->set('graphRecords', $graphRecords);
		$this->set('graphRecordsCumm', $graphRecordsCumm);
		$this->set('graphMonths', $graphMonths);
		$this->set('yearTotals', $yearByMonth);
    $this->set('fromDate',$this->fromDate);
    $this->set('toDate',$this->toDate);
		$this->set('currentDate', $this->currentDate);
    $this->set('typeID', $this->typeID);
    $this->set('increment', '1 day');
		$this->set('timeframe',$this->timeframe);
		$this->set('yearToDateByType',$yearToDateByType);
  }

	public function all($type = null)
		{

			if ($this->request == GET)
			{
				$this->typeID = $type;
			}
			$fromDate = '1980-01-01';
			$toDate = date(YEAR, strtotime("+1 year ".date(YEAR)));
			
				
			$allTime = $this->activities->allByType($this->typeID);
		
			$byYear = $this->activities->yearsByType(DEFAULT_BIRTHDAY, END_OF_TIME,$this->typeID);
			

			$graphData = array();

			$this->set('allTime', $allTime);
			$this->set('byYear', $byYear);
			$this->set('data', $graphData);
			if ($this->colors != null)
			{
				$this->set('colors', $this->colors);
			}
			$this->set('metric', $this->metric);
			$this->set('typeID', $this->typeID);
		}

	public function delete($id = null)
	{
		if (($id != null) )
		{
			$this->activities->delete($id);
			echo "deleted activity ".$id;
			exit;
			return true;
		}
		echo "could not delete ".$id;
		exit;
		return false;

	}

	private function parseStravaStreams($json, &$record)
	{
 		$json = json_decode($json);
		foreach ($json as $i => $stream)
		{
			//ddebug($stream->type);
			switch ($stream->type)
			{
				case 'latlng':
				case 'cadence':

				break;
				case 'altitude':
					$record['streamAltitude'] = isset($record['streamAltitude']) ? $record['streamAltitude'] : '';
					if (($record['streamAltitude'] == ''))
					{
						$record['streamAltitude'] = json_encode($stream->data);
						$somethingHasChanged = true;
						$this->app->pushAlert("Altitude stream saved.");
					}
				break;
				case 'distance':
						$record['streamDistance'] = isset($record['streamDistance']) ? $record['streamDistance'] : '';
					if (($record['streamDistance'] == '') )
					{
						$record['streamDistance'] =json_encode($stream->data);
						$somethingHasChanged = true;
						$this->app->pushAlert("Distance stream saved.");
					}
				break;
				case 'heartrate':
					$record['streamHeart'] = isset($record['streamHeart']) ? $record['streamHeart'] : '';
					if (($record['streamHeart'] == ''))
					{
						$record['streamHeart'] = json_encode($stream->data);
						$somethingHasChanged = true;
						$this->app->pushAlert("Heart stream saved.");
					}
				break;
				case 'time':
					$record['streamTime'] = isset($record['streamTime']) ? $record['streamTime'] : '';
					if (($record['streamTime'] == ''))
					{
						$record['streamTime'] = json_encode($stream->data);
						$somethingHasChanged = true;
						$this->app->pushAlert("Time stream saved.");
					}
				break;
				case 'temp':
					$record['streamTemp'] = isset($record['streamTemp']) ? $record['streamTemp'] : '';
					if (($record['streamTemp'] == ''))
					{
						$record['streamTemp'] = json_encode($stream->data);
						$somethingHasChanged = true;
						$this->app->pushAlert("Time stream saved.");
					}
				break;
				case 'velocity_smooth':
					$record['streamVeloc'] = isset($record['streamVeloc']) ? $record['streamVeloc'] : '';
					if (($record['streamVeloc'] == ''))
					{
						$changes['streamVeloc'] = json_encode($stream->data);
						$record['streamVeloc'] = $changes['streamVeloc'];
						$somethingHasChanged = true;
						$this->app->pushAlert("Time stream saved.");
					}
				break;
			}
		}
		return ($somethingHasChanged);
	}

	

	//function : prepGraphData
	//records : records contain data to be graphed
	//dateField : field that contains the date for the horizontal access
	private function prepGraphData($records, $dateField = 'start_date_local', $fromDate = null, $toDate = null, $metric = self::DEFAULT_METRIC, $timeframe = 'month',$graphIncrement = 'day',  $graphDateFormat = 'd')
	{
	
		$dateField = is_null($dateField) ? 'start_date_local' : $dateField;
		$this->typesFoundInRecords = $this->activities->typesInDateRange($fromDate, $toDate);
		
		$graphRecords = array(); 
		if ($this->typesFoundInRecords)
		{
			$graphRecords['types'][0] = 'date';
			$k = 1;
			$this->colors = "[";
			$first = true;
			foreach($this->typesFoundInRecords as $activitytype_id=>$meta)
			{
					if (!$first)
					{
						$this->colors .= ',';
					} else {
						$first = false;
					}
					$this->colors .= isset($this->types[$activitytype_id]['color']) ? "'".$this->types[$activitytype_id]['color']."'" : "'".DEFAULT_COLOR."'";
					$graphRecords['types'][$k] = $this->types[$activitytype_id]['short'];

					//$graphRecords['types'][$k] = $activitytype_id;
					$k++;
			}
			$this->colors .= "]";
			switch($timeframe)
			{
				case 'all':
				case 'week':
					$endOfTimeFrame = $toDate ;
				break;
				case 'year':
				case 'month':
						$endOfTimeFrame = date(YMD, strtotime($fromDate." +1 ".$timeframe));
				break;

			}
			//debug('from:'.$fromDate.'<br>to:'.$toDate.'<br>end:'.$endOfTimeFrame);
			$i = date(YMD, strtotime($fromDate));
			while (strtotime($i) < strtotime($endOfTimeFrame))
			{
				$graphRecords[$i][0] = date($graphDateFormat, strtotime($i));
				$j = 1;
				foreach ($this->typesFoundInRecords as $type_id => $meta) {
					$graphRecords[$i][$j] = 0;
						$j++;
				}

				$i = date(YMD, strtotime($i." +1 ".$graphIncrement));
			}
			$keyTypes = array_keys($this->typesFoundInRecords);

			foreach ($records as $i=>$record)
			{
				if ($dateField == 'year')
				{
					$dateIndex =  $record[$dateField];
				} else
				{
					$dateIndex = date(YMD, strtotime($record[$dateField]));
				}

				// add into graphdata the appropriate metric. leave unit conversion to view
				$putTheMetricHere = array_search($record['activitytype_id'], $keyTypes)+1;
				if (isset($graphRecords[$dateIndex]))
				{
					
						//statute metrics
						switch ($metric)
						{
							case 'distance':
							case 'miles':
								$graphRecords[$dateIndex][$putTheMetricHere ] +=  ($record['miles']);
							break;
							case 'moving_time':
								$graphRecords[$dateIndex][$putTheMetricHere] += ($record[$metric]) / HOUR;
							break;
							case 'total_elevation_gain_feet':
								$graphRecords[$dateIndex][$putTheMetricHere ] += ($record['total_elevation_gain_feet']) ;
							break;
							case 'calories':
							$graphRecords[$dateIndex][$putTheMetricHere ] += ($record[$metric]) ;
							break;
						}
					
				}
		}

		$j = 0;

		foreach($graphRecords as $r=>$info)
		{
			$graphAbleData[$j] = $info;
			$j++;
		}
			return $graphAbleData;
		}
	}
	




	public function getupdate($id = null)
	{
		if (!is_null($id) && ($id != ''))
		{
			$options = array('fields'=>'*','conditions'=>"activities.id = ".$id." OR activities.activity_id = '".$id."'", 'limit'=>1, 'order'=>'activities.id DESC');
    } else
    {
			$options = array( 'limit'=>1, 'order'=>'activities.id DESC');
    }
		$record =$this->activities->find($options);
		$record = $record[0];
		$this->set('record', $record);
	}

	public function getzones($date = null, $jsonTime = null, $jsonHeart = null)
	{
		if (is_null($date))
		{
				return false;
		}
		//cleveland clinic and polar
		$zones = array(0.5, 0.6, 0.7, 0.8, 0.9);

		$age = $this->time->age($date);
		$maxhr = 220 - $age;
		$hrzones = array();
		for ($k = 0; $k < count($zones); $k++)
		{

			$hrzones['z'.($k+1).'time'] = 0;
			$hrzones['z'.($k+1).'min'] = 10*round($zones[$k]*$maxhr/10,1);
			if ($k < (count($zones) - 1))
			{
				$hrzones['z'.($k+1).'max'] = 10*round($zones[$k+1]*$maxhr/10,1);
			} else
			{
				$hrzones['z'.($k+1).'max'] = $maxhr;
			}
		}
		$arrTime = json_decode($jsonTime);
		$arrHeart = json_decode($jsonHeart);
		for ($j = 1; $j < count($arrTime); $j++)
		{
			$duration = $arrTime[$j] - $arrTime[$j-1];
			$hr = $arrHeart[$j];
			for ($m = 1; $m <= 5; $m++)
			{
				if (($hr >= $hrzones["z".$m."min"]) && ($hr < $hrzones["z".$m."max"]))
				{
					$hrzones["z".$m."time"] += $duration;
				}
			}
		}
		return($hrzones);
	}

	public function myzones()
	{
		//get all the records with hasheartrate and myhrzones = 1
		$totalRecordsNeedingUpdatedZones = $this->activities->count('has_heartrate AND (myhrzones = 1)');
		debug($totalRecordsNeedingUpdatedZones);
		$records = $this->activities->find(array(
			'fields'=>'id, start_date_local, has_heartrate, streamTime, streamHeart, myhrzones, z1min, z1max, z1time, z2min, z2max, z2time, z3min, z3max, z3time, z4min, z4max, z4time, z5min, z5max, z5time',
			'conditions' => 'has_heartrate AND (myhrzones = 1)',
			'order' => "start_date_local DESC",
			'limit' => 200
			)
		);
		debug(count($records));

		//debug($this->activities->SQL);

		//go through the record and calculate age.
		for ($i = 0 ; $i < count($records); $i++)
		{
			if (($records[$i]['streamTime'] != "") && ($records[$i]['streamHeart'] != ""))
			{
				$updatedHR = $this->getzones($records[$i]['start_date_local'],$records[$i]['streamTime'],$records[$i]['streamHeart']);
				foreach($updatedHR as $field=>$value)
				{
					$records[$i][$field] = $value;
				}
				$records[$i]['myhrzones'] = 0;
				//debug($records[$i]);

				if ($this->activities->update($records[$i]))
				{
				echo 'Record Updated: <a href="/app/activities/view/'.$records[$i]['id'].'">'. $records[$i]['id']."</a><BR>";
				} else {
						$this->msg("Record NOT Updated: ", $records[$i]['id']);
				}
			} else {
				echo 'what the fuck  <a href="/app/activities/view/'.$records[$i]['id'].'">'.$records[$i]['id'].'</a><BR>';
			}

		}
		$totalRecordsNeedingUpdatedZones = $this->activities->count('has_heartrate AND (myhrzones = 1)');
		debug($totalRecordsNeedingUpdatedZones);

		exit;
	}

	public function fillGeo(&$r)
	{
			global $DEFAULT_LOCATION;
		if (isset($r['polyline']) && ($r['polyline'] != "") && ($r['activitytype_id']) != SWIMMING)
		{
			$points = $this->strava->decode($r['polyline']);
			if (count($points))
			{
				//start point
				$temp = $this->geocode->reverse($points[0]['lat'], $points[0]['lng']);
				$r['start_lat'] = $points[0]['lat'];
				$r['start_lng'] =  $points[0]['lng'];
				$r['start_addr_id'] = $temp['place_id'];
				$r['start_addr'] = $temp['formatted_address'];
				$r['start_neighborhood'] = $temp['neighborhood'];
				//debug($temp['country_long']); exit;
				$r['start_country_long'] = $temp['country_long'];
				$r['start_country_short'] = $temp['country_short'];
				$r['start_aal3_long'] = $temp['aal3_long'];
				$r['start_aal3_short'] = $temp['aal3_short'];
				$r['start_aal2_long'] = $temp['aal2_long'];
				$r['start_aal2_short'] = $temp['aal2_short'];
				$r['start_aal1_long'] = $temp['aal1_long'];
				$r['start_aal1_short'] = $temp['aal1_short'];
				$r['start_postal_code'] = $temp['postal_code'];
				$r['start_locality'] = (trim($temp['locality']) == "") || (is_null($temp['locality'])) ? DEFAULT_LOCALITY : $temp['locality'];
				
				//end point
				$temp = $this->geocode->reverse($points[count($points)-1]['lat'], $points[count($points)-1]['lng']);
				$r['end_lat'] = $points[count($points)-1]['lat'];
				$r['end_lng'] =  $points[count($points)-1]['lng'];
				$r['end_addr_id'] = $temp['place_id'];
				$r['end_addr'] = $temp['formatted_address'];
				$r['end_neighborhood'] = $temp['neighborhood'];
				$r['end_country_long'] = $temp['country_long'];
				$r['end_country_short'] = $temp['country_short'];
				$r['end_aal3_long'] = $temp['aal3_long'];
				$r['end_aal3_short'] = $temp['aal3_short'];
				$r['end_aal2_long'] = $temp['aal2_long'];
				$r['end_aal2_short'] = $temp['aal2_short'];
				$r['end_aal1_long'] = $temp['aal1_long'];
				$r['end_aal1_short'] = $temp['aal1_short'];
				$r['end_postal_code'] = $temp['postal_code'];
				$r['end_locality'] = $temp['locality'];
				return true;
			} else
			{

				return false;
			}
		} else 
		{
				foreach ($DEFAULT_LOCATION as $field=>$value)
				{
					$r['start_'.$field] = $value;
					$r['end_'.$field] = $value;
				}
				
					
		} 
		return false;
	}

}
?>
