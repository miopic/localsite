<?php

	include('../geography.php');
	include(CONFIG."utilities.php");
	include(CONFIG."settings.php");
	include(CONFIG."database.php");
	include(CONFIG."routes.php");
	include(MODELS."model.php");
	include(MODELS."contacts.php");
	include(COMPONENTS."strava.php");
	include(COMPONENTS."geocode.php");
	echo "Starting Command Line Strava Connector\n";

	$contacts = new contactsModel;
	$cons = $contacts->find(array('conditions' => 'lname = "" and fname = ""'));
  ;
  echo "Found ". count($cons)." contacts.\n";
    foreach($cons as $key=>$con)
    {
        echo "fileas=[".$con['fileas']."]\n";
        if (strrpos($con['fileas'], ","))
        {
          $parts = explode(",", $con['fileas']);
          if (count($parts) > 1)
          {
              echo "Part count ". count($parts)." contacts.\n";
              if (count($parts) >= 2)
              {
                $con['lname'] = trim($parts[0]);
                $con['fname'] = trim($parts[1]);
                echo $con['fname']. ' '. $con['lname']."\n";
                if ($contacts->update($con))
                {
                  echo "Record Saved\n";
                } else
                {
                  echo "Record NOT Saved\n";
                }
              }
          }
        } 
    }
 ?>
 