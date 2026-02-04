<?php

	include('../geography.php');
	include(CONFIG."utilities.php");
	include(CONFIG."settings.php");
	include(CONFIG."database.php");
	include(CONFIG."routes.php");
	include(MODELS."model.php");
	include(MODELS."users.php");
	include(MODELS."activities.php");
	include(COMPONENTS."strava.php");
	include(COMPONENTS."geocode.php");
	echo "Starting Command Line Strava Connector\n";

	$users = new usersModel;
	$activities = new activitiesModel;
	$u = $users->find(array('conditions' => 'id = 2'));
	$u = $u[0];
	$strava = new stravaComponent;
	$geocode = new geocodeComponent;

	$temp = $activities->find( array('order'=>'start_date_local DESC', 'limit'=>1));
    foreach($temp as $key=>$data)
    {
      $lastActivity = $data;
      break;
    }


    $page = 1;
    $strava->accessToken = $u['access_token'];
    $strava->tokenExpiresAt = $u['expires_at'];
    $strava->after = isset($lastActivity['start_date_local']) ? strtotime($lastActivity['start_date_local']." -3 days") : strtotime(date()).'';
    $strava->setPerPage(21);
    $strava->setPage($page);
    //Get recent activities from Strava
    $temp = $strava->activities();
    if ($temp)
    {
    	$stravaActivities = $strava->results;
    	echo "Strava returned: ".count($stravaActivities)."\n";
    } else
    {
    	$stravaActivities = null;
    	echo "shit\n";
    }
    $strava->close();


    $externalIDs = array();
    $saveTheseRecords = array();
    $index = 0;
    if (!is_null($stravaActivities))
    {
      $k = 0;
      $saveTheseRecords = array();
      foreach($stravaActivities as $stravaActivity)
      {
      	
        $externalIDs[$k] = $stravaActivity['Activity']['strava_id'];
        $existingRecord = $activities->findByStravaID($stravaActivity['Activity']['strava_id']);
        
        if (count($existingRecord) == 0)
        {
          $saveTheseRecords[$k] = $stravaActivity;
          $k++;
        }
      }
   
    }
    echo "Save  ".count($saveTheseRecords)."records.\n";
    //&& (isset($this->params['named']['saveall']))
    

    if (count($saveTheseRecords))
    {
      $saveCount = 0;
      foreach ($saveTheseRecords as $wid)
      {
        $r = $strava->activity($wid['Activity']['strava_id']);  
        if (is_null($strava->errorMessage)  && $r)
        {                 
          //Hacky think left over from converting from cakephp
          $r['Activity']['user_id'] = 2;
          $r['Activity']['start_address'] = $temp[0]['name']; 
          unset($temp);
          $temp = isset($r['Activity']['endlat'],$r['Activity']['endlon']) ? $geocode->reverse($r['Activity']['endlat'],$r['Activity']['endlon']) : "";
          if (isset($temp[0]))
          {
          	$r['Activity']['finish_address'] = $temp[0]['name'];
          }
          $r['Activity']['jsonStreams'] = isset($strava->jsonStreams) && ($strava->jsonStreams != '') ? $strava->jsonStreams : '';
          //Strava provides units in Meters, so set the units for the Model to metric so it doesn't assume imperial and convert to Metric
          if ($activities->insert($r['Activity']))
          {
            $saveCount++;        
          } else
          {
          	 echo "something bad happened\n";
    		exit;
          }
          
          
        }
      
      }
  
//===
		echo "Strava records saved: ".$saveCount;                 
    } else {
    	echo "No strava records to save.\n";
    }
?>