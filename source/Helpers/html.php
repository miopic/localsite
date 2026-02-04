<?php

class htmlHelper
{
	var $params = array('controller'=>null, 'action'=>null);
	var $columns = array();
	public function makeAttributes($attributes = null)
	{

		$out = "";
		foreach ($attributes as $key=>$value)
		{
			$out .= ' '. $key . '="' . $value .'"';
		}

		return $out;
	}
	public function image($image, $options = array())
	{
		if (isset($options['url']))
		{
			$image = $image;
		} else
		{
			$image = DEFAULT_IMAGE_DIR.$image;
		}
		$out = '<img src="'.$image.'" ';
		$out .= $this->makeAttributes($options);
		$out .= '>';
		return $out;
	}
	public function link($content, $link, $options = array())
	{
		if (isset($options['escape']))
		{
			unset($options['escape']);
		}

		$out = '<a href="'.$link.'" ';
		$out .= $this->makeAttributes($options);
		$out .= '>'.$content.'</a>';

		return $out;
	}
	public function jsonStreamArray($data, $sets = array())
	{
		$tempJson2Array = json_decode($data);
		$streams = array();
		foreach($tempJson2Array as $i=>$obj)
		{


			$name = $obj->type;

			if (in_array($name, $sets))
			{
				//$streams[$name] = array();
				foreach($obj->data as $i=>$value)
				{
					//$streams[$name][$i] = $value;
					$metricIndex = array_search($name, $sets);
					$streams[$i][$metricIndex] = $name == 'distance' ? $value / METERS_PER_KILOMETER: $value;
					ksort($streams[$i]);
				}
			}

		}

		return($streams);

	}
	public function quote($q ='', $who = '')
	{
?>
		<p style=""><?=$q?></p>
		<p style="padding-left: 60px; text-align: right;">—<em>&nbsp;<?=$who?></em></p>
<?php
	}
	public function addColumnValues(&$matrix, $data)
	{
		$j = 0;
		foreach ($matrix as $key=>$value)
		{
			if (isset($data[$key]))
			{
				$matrix[$key][count($matrix[$key])] = $data[$key];
			} else
			{
				$matrix[$key][count($matrix[$key])] = 0.0;
			}

		}
	}
	public function initDataColumn(&$columns)
	{
		$columns = array();
	}
	public function setDataColumn(&$columns, $name, $type)
	{
		$columns[$name] = $type;
	}
	public function deleteDataColumn(&$columns, $name)
	{
		unset($columns[$name]);
	}
	public function addDateColumns(&$matrix, $fromDate, $toDate,$dateFormat = DEFAULT_DATE_FORMAT, $dateIncrement = 'day')
	{
		$dates = array();
		$current = $fromDate;

		while (strtotime($current) < strtotime($toDate))
		{
			$matrix[$current] = array(date($dateFormat, strtotime($current)));
			$current = date(DEFAULT_DATE_FORMAT, strtotime($current." +1".$dateIncrement));
		}
	}
	public function initGraph($fromDate, $toDate, $data,$metric = 'distance', $dateFormat = DEFAULT_DATE_FORMAT, $dateIncrement = 'day', $running = false)
	//$data should be an array of data streams,
	{
		$graph = array();
		$current = $fromDate;
		$j = 0;
		$last = 0;
		while (strtotime($current) < strtotime($toDate))
		{
		  $formattedDate = date($dateFormat, strtotime($current));
		  if (isset($data[$current]))
		  {
		    $value = ($metric == 'distance') ? $data[$current][$metric] / METERS_PER_KILOMETER :  $data[$current][$metric] ;
		  } else
		  {
		    $value = (float) 0.0;
		  }

		  	$graph[$j] = array($formattedDate,  $value);

		  $j++;
		  $current = date(DEFAULT_DATE_FORMAT, strtotime($current . " +1 ".$dateIncrement));
		}

		return $graph;
	}
	//assumes meters
	function formatUnits($typeID, $distance, $decimalPlaces = 2)
	{
		$temp = 0;
		switch ($typeID) {
			case SWIMMING:
					$temp = number_format($distance * METERS_PER_YARD, 0);
				break;

			default:
				$temp = number_format($distance /	METERS_PER_KILOMETER, $decimalPlaces);
			break;
		}
		return $temp;
	}
}
?>
