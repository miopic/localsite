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
	$missingJsonRecords = array();
    $conditions = "(jsonStreams = '') AND (strava_id <> '')";
    $fields = "id, strava_id, start_date_local, jsonStreams";
    $missingJsonRecords = $activities->find(array('fields'=>$fields, 'conditions'=>$conditions, 'limit'=>'50', 'order'=>'id DESC'));
    $countMissingJsonRecords = count(($missingJsonRecords));
    $i = 1;
    foreach($missingJsonRecords as $key=>$missingRecord)
    {
      echo $i." Getting Strava Record: ".$missingRecord['strava_id'].", ";
       $recordFromStrava = $strava->activity($missingRecord['strava_id']);
      if ($recordFromStrava)
      {
        echo "retrieved, ";
        $missingJsonRecords[$key]['jsonStreams'] = $strava->jsonStreams; 
        if ($activities->update($missingJsonRecords[$key]))
        {
          echo ' saved.'; 
        } else
        {
          echo ' NOT saved.';
        }

      } else
      {
        echo "not retrieved.<br>";
      }
      echo '<BR>';
      $i = $i + 1;
    }
 ?>
 