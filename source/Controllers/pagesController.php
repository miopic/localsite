<?php
require_once('Controller.php');
class pagesController extends Controller
{
	  var $allow = array('addme', 'sun');
  var $otherModels = array('users');
  var $components = array('auth', 'naval');
	public function __construct($controllerName, $controllerAction)
	{
    parent::__construct($controllerName, $controllerAction);
		$x = debug_backtrace();

	}
	
	public function sun()
	{
		$sun = array();
		$today = date(YMD);
		$currentDate = date('Y-m-01',strtotime($today));
		debug($currentDate);
		$i = count($sun);
		//47.6695671,-122.2626624,14
		$lat = DEFAULT_LATITUDE;
		$lon = DEFAULT_LONGITUDE;

		//$sun[$currentDate] = date_sun_info(strtotime($currentDate), DEFAULT_LATITUDE, DEFAULT_LONGITUDE);
		//$sun[$currentDate] = date_sun_info(strtotime($c urrentDate), 47.6695671, -122.2626624);
		while (date('m', strtotime($today))  == date('m', strtotime($currentDate)))
		{
			$sun[$currentDate] = $this->naval->getinfo($currentDate, $lat, $lon);
			$currentDate = date(YMD, strtotime("+1 day ". $currentDate));
		}
		
		debug($sun);
		exit;

//https://aa.usno.navy.mil/api/rstt/oneday?date=2025-02-06 &coords=47.6695671,-122.2626624=-8&dst=true

		$this->set('sun', $sun);
		$this->set('today', $today);
		$this->set('currentDate', $currentDate);
		$this->set('lat', $lat);
		$this->set('lon', $lon);

	}
}
?>