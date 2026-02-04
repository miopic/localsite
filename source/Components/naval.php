<?php
class navalComponent
{
	var $BASEURL = 'https://aa.usno.navy.mil/api/rstt/oneday?';
	public function getinfo($date = '', $lat = '', $lon = '', $utcoffset = -8)
	{
		
		if (($date == '') || ($lat == '')  || ($lon == ''))
		{
			return  '';
		}
	$url = $this->BASEURL . 'date='.$date.'&coords='.$lat.",".$lon."&tz=".$utcoffset;
	$curlOpts = array(
	      CURLOPT_CUSTOMREQUEST => "GET",
	      CURLOPT_HEADER => 0,
	       CURLOPT_URL => $url,
	      CURLOPT_FRESH_CONNECT => 1,
	      CURLOPT_RETURNTRANSFER => 1,
	      CURLOPT_FORBID_REUSE => 1,
	      CURLOPT_TIMEOUT => 4,
	     // CURLOPT_HTTPHEADER =>  array("Authorization: Bearer ".$this->accessToken),

	  );
	    
	  $this->curlHandle = curl_init();
	  curl_setopt_array($this->curlHandle, ($curlOpts));
	  $tempJson = curl_exec($this->curlHandle);

	  $this->lastJSON = $tempJson;
	  $tempJson = json_decode($tempJson);


	  if (isset($tempJson->errors)) {
	    $this->errorMessage = $tempJson->message;

	    return false;
	  }
	  
	  
	  return $tempJson;
  }
}

?>