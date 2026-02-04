<?php
  class timeComponent
  {
  	var $latitude = DEFAULT_LATITUDE;
  	var $longitude = DEFAULT_LONGITUDE;
  	const IPGEO_APIKEY = 'c291aaff391b4879ac58aba959458c4f';

  	function geturl($url)
  	{
  		$curlOpts = array(
            CURLOPT_CUSTOMREQUEST => GET,
            CURLOPT_HEADER => 0,
             CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_HTTPHEADER =>  array("Authorization: Bearer "),

        );

        $this->curlHandle = curl_init();
      	curl_setopt_array($this->curlHandle, ($curlOpts));
      	$result = curl_exec($this->curlHandle);
      	return $result;
  	}

  	function sun($lat = null, $lon = null, $date = null)
  	{
  		$result = date_sun_info(strtotime($date), $lat, $lon);
  		foreach ($result as $key=>$value)
  		{
  			$result[$key] = date("Y-m-d H:i:s", $value);
  		}
  		$result['daylength'] = round((strtotime($result['sunset']) - strtotime($result['sunrise']))/ HOUR, 2);
    	return $result;
  	}
  	//sunrise-sunset.org
  	function sunriseSunset($lat = null, $lon = null, $date = null)
  	{
  		$url = 'https://api.sunrise-sunset.org/json?lat='.$lat.'&lng='.$lon.'&formatted=0&date='.$date;
  		$utc = false;
  		$temp = $this->geturl($url);
      	$temp = json_decode($temp, true);
      	$results = false;

      	if ($temp['status'] == OK)
      	{
	      	if (!$utc)
	      	{
		      	foreach($temp['results'] as $field=>$value)
		      	{
		      		switch ($field) {
		      			case 'day_length':
		      				# code...
		      				break;


		      			default:
	      				$temp['results'][$field] = date(YMD." H:i:s", strtotime($value));
	      				break;
		      		}
		      	}
		     }
		     $result = $temp['results'];
 		     $result['daylength'] = round($result['day_length'] / HOUR, 2);
 		     unset($result['day_length']);

		 } else
		 {
		 	debug($lat);debug($lon); debug($date);
		 	debug($temp);exit;
		 }

		 return $result;
  	}
    public function getWeek($d = null)
    {
        if (is_null($d))
        {
            $d = date(YMD);
        }
        $w = date('w',strtotime($d));
        $n = $w == 0 ? 6 : $w - 1;
        $m = date('Y-m-d',strtotime($d." -".$n." days"));
        return $m;
    }
    public function week($d = null)
    {
      if (is_null($d))
      {
        $d = date(YMD);
      }
      $dates = array();

      for ($i = 0; $i < 7; $i++)
      {
        $d = date(YMD, strtotime($d." +".$i." day"));
        $dates[$d] = array();

      }
      return $dates;
    }

    public function month($m = null)
    {
        $m = is_null($m) ? date(MONTH) : date(MONTH, strtotime($m));
      $i = $this->getWeek($m);
      $nextMonth = date(MONTH, strtotime($m." +1 month"));
      $lastMonthDay = date(YMD, strtotime($nextMonth." -1 day"));

      $n = date("N", strtotime($lastMonthDay));

      $n = 7 - $n;
      $loopLimit = date(YMD, strtotime($lastMonthDay." +".$n." days"));
      $dates = array();
      $j = 0;
      while ( ($j < 42))
      {
        $dates[date(YMD,strtotime($i))] = array('date'=>date(YMD." D",strtotime($i)), 'events'=>array());

        $i = date(YMD, strtotime($i." +1 day"));
        $j = $j +1;
      }
      return $dates;
    }
    public function year($y = null)
    {
        $y = is_null($y) ? date(YEAR) : date(YEAR, strtotime($y));
      $nextYear = date(YEAR, strtotime($y." +1 year"));
      $dates = array();
      $j = $y;
      $i = 0;
      while (strtotime($j) < strtotime($nextYear))
      //while ($i < 20)
      {
        $dates[$j] = array();
        $j = date(YMD, strtotime($j." +1 day"));
        $i = $i + 1;
      }
      return $dates;
    }
    public function age($day = null, $bday = DEFAULT_BIRTHDAY)
    {
      $yearSeconds = 31536000;

      $day = is_null($day) ? date(YMD) : $day;
      $yearSeconds = 31536000;
      if ((date("Y",strtotime($day)) % 4) == 0)
      {
        $yearSeconds = 31622400;

      }
      return floor((strtotime($day)-strtotime($bday))/($yearSeconds));
    }
  }
  ?>
