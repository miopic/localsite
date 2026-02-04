<?php
class stopwatchHelper {
	const UNITS_METRIC = 1;
	const MATH_PRECISION = 2;
	const DAY = 86400;
	const HOUR = 3600;
	const MINUTE = 60;
	var $units = self::UNITS_METRIC;



	public function lz($n) {

		if ($n < 10) {
			return '0'.$n;
		} else
		{
			$n = number_format($n,0);
		}
		return $n;
	}
	public function simple($seconds)
	{
		$tmpSeconds = $seconds;
		$data = $tmpSeconds / self::HOUR;
		$hours = floor($data);
		$tmpSeconds = $tmpSeconds - ($hours*self::HOUR);
		$data = $tmpSeconds / self::MINUTE;
		$minutes = floor($data);
		return $hours.'h&nbsp;'.$minutes.'m';
	}
	public function elapsed($seconds,$options = array()) {
		$options['decimals'] = isset($options['decimals'] ) ? $options['decimals'] : 0;
		$options['showdays'] = isset($options['showdays'] ) ? $options['showdays'] : false;
		$tmpSeconds = $seconds;

		if (isset($options['showdays'])  && ($options['showdays']))
		{

			$days = $tmpSeconds / self::DAY;
			$days = floor($days);
			$tmpSeconds = $tmpSeconds - ($days * self::DAY);
		}

		$data = $tmpSeconds / self::HOUR;
		$hours = floor($data);
		$tmpSeconds = $tmpSeconds - ($hours*self::HOUR);

		$minutes = $tmpSeconds / self::MINUTE;

		$minutes = floor($minutes);
		$tmpSeconds = $tmpSeconds - ($minutes * self::MINUTE);
		$remainingSeconds = floor($tmpSeconds);
		/*
		if (isset($options['decimals'])) {
			$remainingSeconds = round($remainingSeconds, $options['decimals']);
			$remainingSeconds = number_format($remainingSeconds,$options['decimals']);
		} else
		{
			echo "i went else<br>";
			$remainingSeconds = number_format($remainingSeconds,self::MATH_PRECISION);
		}*/

		if (!isset($options['terse'])) {

			if ($hours > 0) {
				$out = $this->lz($hours).':'.$this->lz($minutes).':'.$this->lz($remainingSeconds);
			} else  // hours are <= 0
			{
					$out = $this->lz($minutes).':'.$this->lz($remainingSeconds);
			}
		} else {

			if ($hours > 0) {
					$out = $hours.'h '. round($minutes+($remainingSeconds/60),0). 'm';
			} else {
				$out = round($minutes+($remainingSeconds/60),0). 'm';
			}
		}

		return $out;
	}

	public function speed($units, $details)
	{
		if ($details['moving_time'] == 0)
		{
			return 0;
		}

		if (($units == UNITS_METRIC)  && ($details['activitytype_id'] == SWIMMING))
		{
			return $details['meters'] / ($details['moving_time'] / HOUR);
		} elseif ($units == UNITS_METRIC) 
		{
			return $details['kilometers'] / ($details['moving_time'] / HOUR);
		} elseif (($details['activitytype_id'] == SWIMMING))
		{
			return $details['yards'] / ($details['moving_time'] / HOUR);
		} else
		{
			return $details['miles'] / ($details['moving_time'] / HOUR);
		}


	}
	public function pace($units, $details)
	{
		if ($details['distance'] == 0)
		{
			return 0;
		}

		if (($units == UNITS_METRIC)  && ($details['activitytype_id'] == SWIMMING))
		{
			return $this->elapsed($details['moving_time'] / ($details['distance'] / 100));
		} elseif ($units == UNITS_METRIC) 
		{
			return $this->elapsed($details['moving_time']/$details['kilometers']);
		} elseif (($details['activitytype_id'] == SWIMMING))
		{
			return $this->elapsed($details['moving_time'] / ($details['yards'] / 100));
		} else
		{
			return $this->elapsed($details['moving_time'] / $details['miles']);
		}


	}

	public function convertToUnits(&$data, $measurementUnits, $options = null)
	{
		if (!isset($data['activitytype_id']))
		{
			$typeID = 9999;
		} else {
			$typeID = $data['activitytype_id'];
		}
		if (($measurementUnits == UNITS_METRIC)  || ($measurementUnits == METRIC_LABEL))
		{
			//METRIC calculations for speed and pace
			switch($typeID)
			{
				case SWIMMING:
					if (isset($options['longSwimUnits']))
					{
						$data['distance'] = $data['distance'] / METERS_PER_KILOMETER;
						$data['speed'] = $data['moving_time'] != 0 ? $data['distance'] / ($data['moving_time'] / HOUR) : 0;

					} else
					{
						//calculate meters
							$data['distance'] = $data['distance'];
							$data['speed'] = $data['moving_time'] != 0 ? $data['distance'] / ($data['moving_time'] / HOUR) : 0;
					}
					//calculate seconds per 100 meters
					$data['pace'] = $data['distance'] != 0 ? $data['moving_time'] / ($data['distance'] / 100) : 0;

				break;

				default:
					//calculate seconds per kilometer
					$data['pace'] = $data['distance'] != 0 ? $data['moving_time'] / ($data['distance'] / METERS_PER_KILOMETER) : 0;
					//calculate kilometers
					$data['distance'] = $data['distance'] / METERS_PER_KILOMETER;
					$data['speed'] = $data['moving_time'] != 0 ? $data['distance']  / ($data['moving_time'] / HOUR) : 0;
					$data['total_elevation_gain'] = $data['total_elevation_gain'];

				break;
			}

		} else
		{
			//IMPERIAL calculations for speed and pace
			switch($typeID)
			{
				case SWIMMING:
				//calculate seconds per 100 yards
				$data['pace'] = $data['distance']  != 0 ? $data['moving_time'] / (($data['distance'] / METERS_PER_YARD) / 100) : 0;

					if (isset($options['longSwimUnits']))
					{
						$data['distance'] = ($data['distance'] / METERS_PER_MILE);
						$data['speed'] = $data['moving_time'] != 0 ? (($data['distance']) * HOUR) / ($data['moving_time'] ) : 0;

					} else
					{
						//calculate yards
						$data['distance'] = round(($data['distance'] / METERS_PER_YARD),0);
						$data['speed'] = $data['moving_time'] != 0 ? (($data['distance']) * HOUR) / ($data['moving_time'] ) : 0;
					}


				break;

				default:
					//calculate seconds per miles
					$data['pace'] = $data['distance'] != 0 ? $data['moving_time'] / ($data['distance'] / METERS_PER_MILE) : 0;
					//calculate miles
					$data['distance'] = $data['distance'] / METERS_PER_MILE;
					$data['speed'] = $data['moving_time'] != 0 ? $data['distance']  / ($data['moving_time'] / HOUR) : 0;
					$data['total_elevation_gain'] = $data['total_elevation_gain'] * FEET_PER_METER;

				break;
			}

		}
		return $data;
	}


	public function howmanydecimals($number = 0)
	{
		if (($number >= 0) && ($number < 100))
		{
			return 2;
		} elseif (($number >= 100)  && ($number < 1000))
		{
			return 1;
		} else
		{
			return 0;
		}
	}
	public function meters2kilometers($meters)
	{
		return $meters / METERS_PER_KILOMETER;
	}
	public function meters2miles($meters)
	{
		return $meters / METERS_PER_MILE;
	}
	public function meters2mph($meters, $duration)
	{
		if ($duration == 0 )
		{
			return 0;
		}
		return $this->meters2miles($meters) / ($duration / HOUR);
	}
	public function meters2feet($meters)
	{
		return $meters * FEET_PER_METER;
	}
	public function meters2yards($meters)
	{
		return ($meters * FEET_PER_METER)/3;
	}
	public function meters2kph($meters, $duration)
	{

	}

	public function meters2SecsPer100Yards($meters, $duration)
	{

	}
	public function meters2SecsPer100Meters($meters, $duration)
	{

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

	function graphStreamData($record, $whichStream, $streamDataName)
	{
		$graphData = null;

		if (($record[$whichStream] != '')  && ($record['streamDistance'] != ''))
		{
					$streamDistanceArray = json_decode($record['streamDistance']);
					$streamArray = json_decode($record[$whichStream]);

					if (is_array($streamDistanceArray) && is_array($streamArray))
					{
							$graphData = array();
						$itemCount = min(count($streamDistanceArray), count($streamArray));
						$graphData[0][0] = 'distance';
						$graphData[0][1] = $streamDataName;

						for($k = 0; $k < $itemCount ; $k++)
						{
								if ($this->units ==UNITS_METRIC)
								{
									$graphData[$k+1][0] = round(($streamDistanceArray[$k] / METERS_PER_KILOMETER),4);
								} else {

									$graphData[$k+1][0] = round($streamDistanceArray[$k] / METERS_PER_MILE, 4);
								}
								switch ($whichStream)
								{
									case 'streamAltitude':
										$graphData[$k+1][1] = $this->units ==UNITS_METRIC ? $streamArray[$k] : $streamArray[$k] * FEET_PER_METER;
									break;
									case 'streamTemp':
										$graphData[$k+1][1] = $this->celsius2fahrenheit($streamArray[$k]);
										echo $whichStream.':'.$streamArray[$k].'->'.$graphData[$k+1][1]."<br>";
									break;
									default:
										$graphData[$k+1][1] = $streamArray[$k];
									break;
								}
						}
					}
		}
		return $graphData;
	}
	function celsius2fahrenheit($temperature)
	{
		return 9*($temperature/5) + 32;
	}
}
?>
