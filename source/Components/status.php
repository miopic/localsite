<?php
//Depencencies
// settings.php
class stravaComponent
{

  var $defaultAvatarPassed = 'avatar/athlete/medium.png';
  var $params = null;
  var $activityJson;
  var $activityModel = 'Activity';
  var $effortModel = 'Effort';
  var $segmentModel = 'Segment';
  var $convert = true;
  var $athlete = null;
  var $after = '1539145545';
  var $tokenType = null;
  var $myStravaID = '5583256';
  var $userModel = 'User';
  var $callBackDomain = 'dmarks.net';
  var $callCount = 0;
  var $refreshToken = null;
  var $expiresAt = null;
  var $stravaTypes = array(
      'Ride'=>CYCLING,
      'Run'=>RUNNING,
      'Swim'=>SWIMMING,
      'Hike'=>HIKING,
      'Walk'=>WALKING,
      'OpenSwim'=>OPEN_SWIM,
      'AlpineSki'=>SKIING,
      'Workout'=>WALKING,
      'Kayaking' => KAYAKING
    );
  var $stravaAPIVersion = 'Strava V3 API';
  var $segmentsUnparsed = '';

  var $lastJSON = '';
  var $jsonStreams = '';
  var $enableGettingStreams = true;

  var $climbCategories = array('', '4', '3', '2', 'HC');

//Mapping of Strava fields to database fields


  var $stravaAthleteFields = array
  (
    'id' => 'strava_id',
    'username' => 'username',
    'resource_state' =>null,
    'firstname' => 'firstname',
    'lastname' => 'lastname',
    'city' => 'city',
    'state' => 'state',
    'country' => 'country',
    'sex' => 'sex',
    'premium' => 'premium',
    'created_at' => 'strava_created',
    'updated_at' => 'strava_updated',
    'badge_type_id' => null,
    'profile_medium' => 'profile_medium',
    'profile' => 'profile',
    'friend' => null,
    'follower' => null,
    'follower_count' => 'follower_count',
    'friend_count' => 'friend_count',
    'mutual_friend_count' => null,
    'athlete_type' => 'athlete_type' ,
    'date_preference' => 'date_preference',
    'device_name' => 'device_name',
    'measurement_preference' => 'measurement_preference',
    'clubs' => true,
    'email' => null,
    'ftp' => null,
    'weight' => 'weight'
  );
  var $streamTypes = array(
      'latlng'    => 'Point',
      'heartrate' => 'Heartrate',
      'cadence'  => 'Cadence',
      'time'      => 'Duration',
      'distance'  => 'Distance',
      'altitude'  => 'Elevation',
      'temp'      => 'Temp',
      'velocity_smooth' => 'Velocity'
    );
  var $defaults = array
  (
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HEADER => 0,
          CURLOPT_URL => null,
          CURLOPT_FRESH_CONNECT => 1,
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_FORBID_REUSE => 1,
          CURLOPT_TIMEOUT => 4,
          CURLOPT_POSTFIELDS => null
  );
  var $Gear = array();  //An array of Gear set by the controller to convert Strava Gear to an id in the database. Optional, Strava component will always return the name of the gear for an activity

  var $clientId = '5532';
  var $clientSecret = '9b37b4d017ae4f1d9531df6f7d85d0e05a14637f';
  //var $accessToken = '12aed856d07a596bc3d4f2012c7c73401a7d64d0';
  //var $accessToken = '07eb8904b50193aa3f84e04be249c1d7e9ce3cd0';
  var $accessToken = '';
  var $page = 1;
  var $perPage = 1;
  var $post = null;
  var $curlHandle = null;

  var $baseURL = 'https://www.strava.com/api/v3/';

  var $oAuthRequest = 'https://www.strava.com/oauth/authorize?';
  var $jsonResults = null;
  var $results = null;
  var $tokenURL = 'https://www.strava.com/oauth/token';
  var $errorMessage = null;   //Error message returned from Strava


  function __construct()
  {
       define('STRAVA_PROFILE_LINK', 'https://www.strava.com/athletes/');

  }

  public function getAuthUrl($scope = 'read_all')
  {
    //debug('get auth url');
    //debug($scope);
  $obj = '';
    $obj = 'activity:';

    $this->errorMessage = null;
    $scope = is_null($scope) ? 'read_all' : $scope;
   $this->oAuthRequest = 'https://www.strava.com/oauth/authorize?client_id=5532&redirect_uri=https://'.$this->callBackDomain.'/rule10/activities/strava_callback&scope='.$obj.$scope;
    $temp = '';
    //debug($this->oAuthRequest);
    return $this->oAuthRequest;
    return false;
  }

  public function setPerPage($n = 1)
  {
    $this->errorMessage = null;
    if ($n >= 1)
    {
      $this->perPage = $n;
    } else {
      $this->perPage = 1;
    }
    return true;
  }
  public function setPage($n = 1)
  {
    $this->errorMessage = null;
    if ($n >= 1) {
      $this->page = $n;
    } else {
      $this->page = 1;
    }
    return true;
  }

  public function getAccessToken($code = null)
  {
    $this->errorMessage = null;
    if (!is_null($code))
    {
      //set curl opts
      unset($this->post);
      $this->post = array(
        'client_id'   => $this->clientId,
        'client_secret'  => $this->clientSecret,
        'code'      => $code
        );

      $curlOpts = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
             CURLOPT_URL => $this->tokenURL,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_POSTFIELDS => http_build_query($this->post)
        );
        unset($this->defaults);
         $this->defaults = $curlOpts;
         $this->curlHandle = curl_init();
         curl_setopt_array($this->curlHandle, ($this->defaults));
      $temp = curl_exec($this->curlHandle);
      $temp = json_decode($temp);



      if (!isset($temp->errors))
      {

        if (isset($temp->access_token))
        {
          $this->accessToken = $temp->access_token;
          if (isset($temp->athlete))
          {
            $this->athlete = array();
            foreach ($temp->athlete as $f => $v) {
              if (($f != 'bikes')  && ($f != 'shoes')) {
                $this->athlete[$f] = $v;
              }
            }
          }
          $this->tokenType = isset($temp->token_type) ? $temp->token_type : null;
          return $temp;
        }
      } else
      {

      }
      return false;
      }
    return false;
  }
  public function timeRemaining($t = null)
    {
      $currentTime = time();
      return (($t - $currentTime));
    }

  public function resetCallCount()
  {
    $this->callCount = 0;
  }
  public function incrementCallCount()
  {
    $this->callCount = $this->callCount + 1;
  }
  public function getCallCount()
  {
    return $this->callCount;
  }
  public function refreshAccessToken(&$u = null )
  {

    if (!is_null($this->refreshToken))
    {
      //set curl opts
      unset($this->post);
      $this->post = array
      (
        'client_id'   => $this->clientId,
        'client_secret'  => $this->clientSecret,
        'grant_type' => 'refresh_token',
        'refresh_token'      => $this->refreshToken,
      );

      $curlOpts = array
      (
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $this->tokenURL,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 4,
        CURLOPT_POSTFIELDS => http_build_query($this->post)
      );
      unset($this->defaults);
      $this->defaults = $curlOpts;
      $this->curlHandle = curl_init();
      curl_setopt_array($this->curlHandle, ($this->defaults));
      $temp = curl_exec($this->curlHandle);
    
      $temp = is_null($temp) ? false : $temp;
      //debug('raw json returned in refreshAccessToken');
      //debug($temp);
      $temp = $temp ? json_decode($temp) : $temp;
      echo "<h1>decoded json from Strava</h1>";
      debug($temp);
      //debug('decoded json returned in refreshAccessToken');
      //debug($temp);
      if (!isset($temp->errors))
      {
        if (isset($temp->access_token))
        {
          $this->accessToken = $temp->access_token;
          $u['access_token'] = $temp->access_token;
          $this->refreshToken = $temp->refresh_token;

          $u['refresh_token'] = $temp->refresh_token;
          $this->expiresAt = $temp->expires_at;
          $u['expires_at'] = $temp->expires_at;
          //debug('u in strava->refreshToken');
          //debug($u);
          if (isset($temp->athlete))
          {
            $this->athlete = array();
            foreach ($temp->athlete as $f => $v) {
              if (($f != 'bikes')  && ($f != 'shoes')) {
                $this->athlete[$f] = $v;
              }
            }
          }
          $this->tokenType = isset($temp->token_type) ? $temp->token_type : false;
          return true;
        }
      } else {
        debug($temp->errors);
      }

      return false;
      }

    return false;
  }
  public function init($curlOpts = null)
  {

     $this->post = array('page'=>$this->page, 'per_page'=>$this->perPage);
     if (is_null($curlOpts))
     {

      }
      unset($this->defaults);
      $this->defaults = $curlOpts;
    $this->curlHandle = curl_init();

    return true;
  }

  public function close()
  {
    return (curl_close($this->curlHandle));
  }

  //activity - gets activity without the streams of gps or heartrate data and puts it into the objects results array
  public function activity($strava_id = null)
  {
    //debug($strava_id);
    $url = $this->baseURL . 'activities/';
    if (!is_null($strava_id)) {
      $activity = $this->curlStrava('activities/'.$strava_id);
      $this->activityJson = $this->lastJSON;
      //debug($activity);
      if (is_null($activity) || !$activity)
      {
        $this->errorMessage =  'Strava failed to retrieve activity ['.$strava_id.']';
        return false;
      }
      //clear the previous results from the Strava object

      unset($this->results);
      $this->results = array();
      $this->results = $this->parseActivity($activity);


      //Streams of data do not come from the activities end point so you have to get them separately
      //  gps comes in  the streams and is more reliable than the polyline
      if ($this->getStream($strava_id) && ($this->enableGettingStreams))
      {
        $this->jsonStreams = $this->lastJSON;
        $this->results['Activity']['jsonStreams'] = $this->jsonStreams;
      }
      return $this->results;
    }
    return false;
  }
  public function getzones($strava_id)
  {
    //id refers to the id of the activity associated with the kudos
    if (!is_null($strava_id))
    {
      //https://www.strava.com/api/v3/athletes/:id
      $tempJson = $this->curlStrava('activities/'.$strava_id.'/zones');

      if (!$tempJson)
      {
        return false;
      } else
      {
        $record = array();
        $i = 0;
        $foundHeartZones = null;
        while (($i < count($tempJson)) && is_null($foundHeartZones))
        {
          if ($tempJson[$i]->type == 'heartrate')
          {
            $foundHeartZones = $i;
          }
          $i += 1;
        }

        if (!is_null($foundHeartZones))
        {
          $record = $tempJson[$foundHeartZones]->distribution_buckets;
          return json_encode($record);
        } else {
          return false;
        }


        return true;
      }

    }
    $this->errorMessage = 'Strava Activity ID not specified';
    return false;
  }

  public function parseActivity($activity)
  {

    if (is_null($activity) || !$activity)
    {
      $this->errorMessage = 'strava crapped out';
      return false;
    }
    $record[$this->activityModel] = array();
    
    foreach($activity as $stravaField=>$stravaValue)
    {

      $record[$this->activityModel]['api'] = $this->stravaAPIVersion;
         switch ($stravaField)
        {
          case 'id':
             $record[$this->activityModel]['activity_id'] = $stravaValue;
             break;
          case 'type':
              if ((strlen($activity->map->summary_polyline) != 0) && ($activity->type == 'Swim'))
              {
                $record[$this->activityModel]['activitytype_id'] = $this->stravaTypes['OpenSwim'];
                $record[$this->activityModel][$stravaField] = 'OpenSwim';
              } else
              {
                $record[$this->activityModel]['activitytype_id'] = $this->stravaTypes[$stravaValue];
                $record[$this->activityModel][$stravaField] = $stravaValue;
              }


          break;
          case 'athlete':
            $record[$this->activityModel]['athlete_id'] = isset($stravaValue->id) ? $stravaValue->id : '';
          break;
          case 'gear':
          break;
          case 'gear_id':

          break;

          //Exclude polyline because GPS points come in the jsonStream

          case 'map':
            $record[$this->activityModel]['polyline'] = isset($stravaValue->id) ? $stravaValue->id : '';
            $record[$this->activityModel]['polyline'] = isset($stravaValue->polyline) ? $stravaValue->polyline : '';
            $record[$this->activityModel]['summary_polyline'] = isset($stravaValue->summary_polyline) ? $stravaValue->summary_polyline: '';
          break;
          case 'start_latlng':
            if (isset($record[$this->activityModel]['start_latitude']) && isset($record[$this->activityModel]['start_longitude']))
            {
             $record[$this->activityModel]['start_latitude'] = $stravaValue[0];
             $record[$this->activityModel]['start_longitude'] = $stravaValue[1];
            }
          break;
          case 'end_latlng':
            if (isset($record[$this->activityModel]['endLat']) && isset($record[$this->activityModel]['endLon']))
            {
             $record[$this->activityModel]['endlat'] = $stravaValue[0];
             $record[$this->activityModel]['endlon'] = $stravaValue[1];
            }
          break;
          case 'start_date_local':
            $stravaValue = str_replace('T', ' ', $stravaValue);
            $stravaValue = str_replace('Z', ' ', $stravaValue);
            $record[$this->activityModel][$stravaField] = $stravaValue;
          break;
          case 'start_date':
            $stravaValue = str_replace('T', ' ', $stravaValue);
            $stravaValue = str_replace('Z', ' ', $stravaValue);
            $record[$this->activityModel]['start_date'] = $stravaValue;
          break;
          case 'photos':
               $record[$this->activityModel]['jsonPhotos']  = json_encode($stravaValue->primary);
          break;


          case 'workout_type':
           $record[$this->activityModel][$stravaField] = 0;
            if (is_numeric($stravaValue))
            {
              $record[$this->activityModel][$stravaField] = $stravaValue;
            }
          break;
          case 'commute':
          case 'has_heartrate':
            $record[$this->activityModel][$stravaField] = 0;
            if ($stravaValue)
            {
              $record[$this->activityModel][$stravaField] = 1;
            }
          break;
          case 'average_heartrate':
           $record[$this->activityModel][$stravaField] = 0;
            if ($stravaValue != '')
            {
              $record[$this->activityModel][$stravaField] = $stravaValue;
            }
          break;
          case 'timezone':
          case 'utc_offset':
          case 'device_name':
          default:
             $record[$this->activityModel][$stravaField] = $stravaValue;
             if ($stravaField == 'distance')
             {
               $record[$this->activityModel]['distance_source'] = $stravaValue;
             }
          break;

        }
    }

    return $record;
  }
  public function activities()
  {
     $this->post = array('page'=>$this->page, 'per_page'=>$this->perPage, 'after'=>$this->after);
    $temp = $this->curlStrava('athlete/activities?page='.$this->page.'&per_page='.$this->perPage.'&after='.$this->after);

    debug($temp);
    if ($temp)
    {
      foreach($temp as $i=>$values)
      {
        $this->results[$i]= $this->parseActivity($values);
      }
      return true;
    }
    return false;
  }
  public function getStream($wid = null, $types = null) {
    if (!is_null($wid)) {
      $types = is_null($types) ? 'time,latlng,altitude,heartrate,temp,velocity_smooth' : $types;
      $temp = $this->curlStrava('activities/'.$wid.'/streams/'.$types);
      $temp = $this->lastJSON;
        $j = 0;
        if (!$temp)
        {
          return false;
        }
        $streams = $this->streamsToArr($temp);



        return $streams;
    }
    return false;
  }
  public function segment($wid = null)
  {
    $url = $this->baseURL . 'segments/';
    if (!is_null($wid))
    {
      $segment = $this->curlStrava('segments/'.$wid);
      //debug( $this->lastJSON);
      $r = array();
      if (!is_null($segment))
      {
        foreach ($segment as $segmentField=>$segmentValue)
        {

           switch ($segmentField)
          {

            case 'end_latlng':
            case 'start_latlng':

            break;
            case 'map':
              $r['polyline'] = $segmentValue->polyline;
            break;
            case 'athlete_segment_stats':
              $r['pr_elapsed_time'] = $segmentValue->pr_elapsed_time;
              $r['pr_date'] = $segmentValue->pr_date;
              $r['effort_count'] = $segmentValue->effort_count;
            break;
            default:
              $r[$segmentField] = $segmentValue;
            break;
          }

        }
        unset($this->results);
        $this->results = $r;
        return $r;
      } else
      {
        echo 'Strava failed to retrieve segment ['.$wid.']';exit;
      }
    }


  }
  function segmentEfforts($segmentID = null)
  {
    $url = $this->baseURL . 'segments/'.$segmentID.'/all_efforts';
     if (!is_null($segmentID))
    {
    $segmentEfforts = $this->curlStrava('segments/'.$segmentID.'/all_efforts');
    debug($segmentEfforts);
    exit;
    }
  }
  public function saveAsJson($fname, $record) {

    if (file_exists($fname))
    {
      unlink($fname);
    }
     if (!file_exists($fname)) {
      $json = json_encode($record);
      $handle = fopen($fname, "w");
      if (!$handle) {}
      if(fwrite($handle, $json)) {
        //debug('strava saved json');
      } else {
        //debug('strava did not save json');
      }
      fclose($handle);
      return true;
    }
    return false;
  }

  public function getJson($fname) {
    if (file_exists($fname) && ($fname != '')) {
      $filesz = filesize($fname);
      $handle = fopen($fname, "r");
      $json = fread($handle, $filesz);
      fclose($handle);
      $decoded = json_decode($json);

      if ($decoded != '')
      {
        $decoded = get_object_vars($decoded);
        if (isset($decoded[$this->activityModel])) {
          $decoded[$this->activityModel] = get_object_vars($decoded[$this->activityModel]);
        }
        if (isset($decoded['Point'])) {
          for ($i = 0; $i < count($decoded['Point']); $i++) {
            $decoded['Point'][$i] = get_object_vars($decoded['Point'][$i]);
          }

          foreach($decoded['Point'] as $key=>$data) {
            if (isset($decoded['Point'][$key]['duration'])) {
              unset($decoded['Point'][$key]['duration']);
            }
            if (isset($decoded['Point'][$key]['seconds'])) {
              unset($decoded['Point'][$key]['seconds']);
            }
          }
        }

        if (isset($decoded['Duration'])) {
          for ($i = 0; $i < count($decoded['Duration']); $i++) {
            $decoded['Duration'][$i] = $decoded['Duration'][$i];
          }
        }
      }
      return $decoded;
    }
    return false;
  }
  public function getKudos($strava_id = null)
  {
    //id refers to the id of the activity associated with the kudos
    if (!is_null($strava_id))
    {
      //https://www.strava.com/api/v3/athletes/:id
      $tempJson = $this->curlStrava('activities/'.$strava_id.'/kudos');

      if (!$tempJson)
      {
        return false;
      } else
      {
        $record = array();

        foreach($tempJson as $index=>$obj)
        {

          foreach($obj as $field=>$value)
          {
            $record[$index][$field] = $value;
          }

        }

        return $record;
      }

    }
    $this->errorMessage = 'Strava Activity ID not specified';
    return false;
  }

  public function getAthleteStats($id = null)
  {
    $results = null;
     if (!is_null($id))
     {
        $tempJson = $this->curlStrava('athletes/'.$id.'/stats');
        $results = $tempJson;
     }
     return $results;
  }
  public function getAthlete($id = null)
  {
    if (!is_null($id))
    {
      //https://www.strava.com/api/v3/athletes/:id
      $tempJson = $this->curlStrava('athletes/'.$id);
      if (!$tempJson)
      {
        return false;
      } else
      {
        $record = array('Friend'=>array());
        foreach($tempJson as $field=>$value)
        {

          if (isset($this->stravaAthleteFields[$field]) && (!is_null($this->stravaAthleteFields[$field])))
          {
            if ($field == 'clubs')
            {
              $record['Friend']['clubs'] = array();
              for($i = 0; $i < count($value); $i++)
              {
                  foreach($value[$i] as $clubField => $clubValue)
                  {
                     $record['Friend']['clubs'][$i][$clubField] = $clubValue;
                  }
              }
               $record['Friend']['clubs'] = Set::combine($record['Friend']['clubs'],'{n}.id','{n}');
            } else
            {
               $record['Friend'][$this->stravaAthleteFields[$field]] = $value;
            }
          }
        }
        return $record;
      }

    }
    $this->errorMessage = 'Strava ID not specified';
    return false;
  }

  public function curlStrava($stravaCall = '')
  {
    $url = $this->baseURL . $stravaCall;
      if (isset($this->params['named']['url']))  {debug($url);}


      $curlOpts = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HEADER => 0,
             CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_HTTPHEADER =>  array("Authorization: Bearer ".$this->accessToken),

        );
        if (!is_null($this->post))
        {
        //  $curlOpts[CURLOPT_POSTFIELDS] = http_build_query($this->post);
        }
      if (isset($this->params['named']['curl']))
      {
      //  debug($curlOpts);
      }
      $this->curlHandle = curl_init();
      curl_setopt_array($this->curlHandle, ($curlOpts));
      $temp = curl_exec($this->curlHandle);
      $this->incrementCallCount();
      $this->lastJSON = $temp;
      $tempJson = json_decode($temp);

      //Polyline decode
      //$points = $this->polylineDecode($tempJson[0]->map->summary_polyline);

      if (isset($tempJson->errors)) {
        $this->errorMessage = $tempJson->message;

        return false;
      }
      return $tempJson;
  }
  public static function decode( $string )
  {
    $points = array();
    $index = $i = 0;
    $previous = array(0,0);
    if (!is_null($string))
    {
      while ($i < strlen($string))
      {
        $shift = $result = 0x00;
        do {
            $bit = ord(substr($string, $i++)) - 63;
            $result |= ($bit & 0x1f) << $shift;
            $shift += 5;
        } while ($bit >= 0x20);
        $diff = ($result & 1) ? ~($result >> 1) : ($result >> 1);
        $number = $previous[$index % 2] + $diff;
        $previous[$index % 2] = $number;
        $index++;
        $precision = 5;
        $points[] = $number * 1 / pow(10, $precision);
    }
    $j = 0;
    $i = 0;
    $latLong = array();
    while ($i < count($points))
    {
      if (isset($points[$i]) && isset($points[$i+1]))
      {
        $latLong[$j]['lat'] = $points[$i];
        $latLong[$j]['lng'] =  $points[$i + 1];
      }
      $i = $i + 2;
      $j++;
    }
    return $latLong;

  }
  return array();
}

    function gear($gear_id = null)
    {
      if (!is_null($gear_id))
      {

        //is the gear in the cache?

          //the gear was not in the cache so call strava
          $metaGear = $this->curlStrava('gear/'.$gear_id);


        return $metaGear;

      }


    }


    function athlete($athlete_id = null)
    {
      if (!is_null($athlete_id))
      {
        //is the gear in the cache?
        $metaAthlete = Cache::read($athlete_id);
        if ($metaAthlete)
        {
          return $metaAthlete;
        } else
        {
          //the gear was not in the cache so call strava
          $metaGear = $this->curlStrava('athletes/'.$athlete_id);
           Cache::write($athlete_id, $metaAthlete);

        }
        return $metaGear;

      }


    }
    function photos($activity_id = null, $size = '128x128')
    {
      $images = array();
      if (!is_null($activity_id))
      {
        //is the gear in the cache?
        //the gear was not in the cache so call strava
        $metaPhotos = $this->curlStrava('activities/'.$activity_id.'/photos?photo_sources=true&size='.$size);
        if (isset($metaPhotos) && $metaPhotos && (count($metaPhotos) > 0))
        {
          foreach($metaPhotos as $key=>$image)
          {
            $tempUrl = get_object_vars($image->urls);

            $images[$key]['url'] = $tempUrl;

            $images[$key]['latitude'] = isset($image->location[0]) ? $image->location[0] : null;
            $images[$key]['longitude'] = isset($image->location[1]) ? $image->location[1] : null;
            $images[$key]['caption'] = $image->caption;
          }
        } else
        {

        }
      }

      return $images;
    }
 public function updateAthlete(&$user)
  {
    $stravaAthlete = $this->athlete($user[$this->userModel]['strava_id']);
    //debug($stravaAthlete);
    $fieldsChanged = '';
    foreach ($stravaAthlete as $key=>$value)
    {



        switch ($key)
        {
          case 'premium':
               if (($value) && (!$user[$this->userModel][$key]))
            {
              $user[$this->userModel][$key] = true;
              $fieldsChanged .= $key .' ';
            } elseif ( ((!$value) && ($user[$this->userModel][$key])))
            {
              $user[$this->userModel][$key] = false;
              $fieldsChanged .= $key .' ';
            }
          break;
          case 'profile':
          case 'profile_medium':
          case 'friend_count':
          case 'follower_count':
            if ($value != $user[$this->userModel][$key])
            {
              $user[$this->userModel][$key] = $value;
              $fieldsChanged .= $key .' ';
            }
          break;
        }
    }
    return $fieldsChanged;
  }

  public function streamsToArr($json = '')
  {
    if ($json != '')
    {
      $temp = json_decode($json);
      $streams = array();

      foreach ($temp as $stream)
      {
        $currentType = isset($stream->type) ? $stream->type : null;
        if (isset($stream->data))
        {
          if (gettype($stream->data) == 'array')
          {
            for ($i = 0; $i < count($stream->data); $i++)
            {
              switch ($currentType)
              {
                case 'latlng':
                  $streams[$this->streamTypes[$currentType]][$i]['latitude'] = $stream->data[$i][0];
                  $streams[$this->streamTypes[$currentType]][$i]['longitude']  = $stream->data[$i][1];
                break;
                case 'time':
                case 'altitude':
                case 'distance':
                case 'cadence':
                case 'heartrate':
                  $streams[$this->streamTypes[$currentType]][$i] = $stream->data[$i];
                  if ($currentType == 'time') {
                    if ($i == 0) {
                      $lastDuration =  $stream->data[$i];
                      $streams['Duration'] = array();
                    }
                    $streams['Duration'][$i] = array();

                    $streams['Duration'][$i]['seconds'] = $stream->data[$i] - $lastDuration;
                    $lastDuration =  $stream->data[$i];
                    $streams['Duration'][$i]['duration'] = $stream->data[$i];
                  }
                break;

              case 'time':
              default:
                if ($currentType == null)
                {
                  return false;
                }
              break;
              }
            }
          }
        } else {
          return false;
        }
      }
      return $streams;
    }
    return false;
  }


}
?>
