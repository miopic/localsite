<?php
  define('GOO_ADMIN1','administrative_area_level_1');
  define('GOO_ADMIN2','administrative_area_level_2');
  define('GOO_ADMIN3','administrative_area_level_3');

  define('GOO_COUNTRY','country');
  define('GOO_GEOCODING_URL','https://maps.googleapis.com/maps/api/geocode/json?language=en&address=');
  define('GOO_FINDPLACE','https://maps.googleapis.com/maps/api/place/findplacefromtext/json?');
  define('GOO_REVERSE_GEOCODING_URL','https://maps.googleapis.com/maps/api/geocode/json?enable_address_descriptor=true&latlng=');
  define('GOO_TIMEZONE_URL', 'https://maps.googleapis.com/maps/api/timezone/json?location=');
  define('GOO_API_KEY', 'AIzaSyAS5815JrGPMSsO-cB-3nOuZaNcpeqW07U');
  define('GOO_API_TZKEY', 'AIzaSyAS5815JrGPMSsO-cB-3nOuZaNcpeqW07U');
  define('GOO_LONG_NAME','long_name');
  define('GOO_ROUTE', 'route');
  define('GOO_POSTALCODE', 'postal_code');
  define('GOO_PLACE_ID', 'place_id');
  define('GOO_SENSOR_URL_PARAMETER', '');
  define('GOO_SHORT_NAME','short_name');
  define('GOO_STREET_NUMBER', 'street_number');
  define('GOO_STREET_NEIGHBORHOOD', 'neighborhood');
  define('GOO_CITY', 'locality');
  define('GOO_ZOOM_LEVEL', 12);
  define('GOO_STATUS_OK', 'OK');

  class geocodeComponent
  {
    var $status = '';
    var $geoCodeURL = GOO_GEOCODING_URL;
    var $geoFindPlace = GOO_FINDPLACE;
    var $googleSensor = GOO_SENSOR_URL_PARAMETER;
    var $googleStatus = '';
    var $googleStatusMessage = '';
    var $json = null;
    var $results = array();
    var $model = 'Place';
    var $searchTest = '';
    var $controller = null;
    var $placeTypes = null;
    var $key = '&key='.GOO_API_KEY;
    var $curlHandle = null;
     var $curlDefaults = array(
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HEADER => 0,
          CURLOPT_URL => null,
          CURLOPT_FRESH_CONNECT => 1,
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_FORBID_REUSE => 1,
          CURLOPT_TIMEOUT => 4,
          CURLOPT_POSTFIELDS => null
      );

    function startup(Controller $controller) {
      return true;
    }
    function getAddressComponent($components = null, $title = null, $shortOrLongName = null) {

      if (!is_null($components) && !is_null($title) && !is_null($shortOrLongName)) {
        $found = false;
        for($i = 0; ($i < count($components)) && (!$found); $i++) {

          for ($j = 0;($j < count($components[$i]->types)) && (!$found); $j++) {

            if ($components[$i]->types[$j] == $title) {

              $found = true;

                $names = get_object_vars($components[$i]);

              if (isset($names[$shortOrLongName])) {

                return $names[$shortOrLongName];
              }
              return '';
            }
          }
        }
        return '';
      }
      return '';
    }
    function parseResults($json, $searchTerm = null) {


      if (isset($json->status)) {
        $this->status = $json->status;
      } else {
        $this->status = 'NO RESPONSE';
        return false;
      }
      if ($this->status != 'OK') {
        return false;
      }

      if (isset($json->results)) {
        $i = 0;
        $this->json = $json;

        foreach ($json->results as $key=>$metaData) {


          $this->results[$i]['zoom'] = GOO_ZOOM_LEVEL;
          if (isset($metaData->formatted_address)) {
            $this->results[$i]['formatted_address'] = $metaData->formatted_address;
          }
           $this->results[$i]['name'] =  !is_null($searchTerm) ? $searchTerm : $this->results[$i]['formatted_address'];
          $this->results[$i]['latitude'] = null;
          $this->results[$i]['longitude'] = null;
  	      $this->results[$i]['bounds_ne_latitude'] = null;
          $this->results[$i]['bounds_ne_longitude'] = null;
           $this->results[$i]['bounds_sw_latitude'] = null;
          $this->results[$i]['bounds_sw_longitude'] = null;
           $this->results[$i]['viewport_ne_latitude'] = null;
          $this->results[$i]['viewport_ne_longitude'] = null;
           $this->results[$i]['viewport_sw_latitude'] = null;
          $this->results[$i]['viewport_sw_longitude'] = null;

          if (isset($metaData->geometry->location->lat)  && isset($metaData->geometry->location->lng) ) {
            $this->results[$i]['latitude'] = $metaData->geometry->location->lat;
            $this->results[$i]['longitude'] = $metaData->geometry->location->lng;
          }

           if (isset($metaData->geometry->bounds->northeast->lat)  && isset($metaData->geometry->bounds->northeast->lng) ) {
            $this->results[$i]['bounds_ne_latitude'] = $metaData->geometry->bounds->northeast->lat;
            $this->results[$i]['bounds_ne_longitude'] = $metaData->geometry->bounds->northeast->lng;
          }
           if (isset($metaData->geometry->bounds->southwest->lat)  && isset($metaData->geometry->bounds->southwest->lng) ) {
            $this->results[$i]['bounds_sw_latitude'] = $metaData->geometry->bounds->southwest->lat;
            $this->results[$i]['bounds_sw_longitude'] = $metaData->geometry->bounds->southwest->lng;
          }
           if (isset($metaData->geometry->viewport->northeast->lat)  && isset($metaData->geometry->viewport->northeast->lng) ) {
            $this->results[$i]['viewport_ne_latitude'] = $metaData->geometry->viewport->northeast->lat;
            $this->results[$i]['viewport_ne_longitude'] = $metaData->geometry->viewport->northeast->lng;
          }
           if (isset($metaData->geometry->viewport->southwest->lat)  && isset($metaData->geometry->viewport->southwest->lng) ) {

            $this->results[$i]['viewport_sw_latitude'] = $metaData->geometry->viewport->southwest->lat;
            $this->results[$i]['viewport_sw_longitude'] = $metaData->geometry->viewport->southwest->lng;
          }
          if (isset($metaData->address_components)) {
            $this->results[$i]['aal3_short'] = $this->getAddressComponent($metaData->address_components,GOO_ADMIN3,GOO_SHORT_NAME);
            $this->results[$i]['aal3_long'] =  $this->getAddressComponent($metaData->address_components,GOO_ADMIN3,GOO_LONG_NAME);
            $this->results[$i]['aal2_short'] = $this->getAddressComponent($metaData->address_components,GOO_ADMIN2,GOO_SHORT_NAME);
            $this->results[$i]['aal2_long'] =  $this->getAddressComponent($metaData->address_components,GOO_ADMIN2,GOO_LONG_NAME);
            $this->results[$i]['aal1_short'] = $this->getAddressComponent($metaData->address_components,GOO_ADMIN1,GOO_SHORT_NAME);
            $this->results[$i]['aal1_long'] =  $this->getAddressComponent($metaData->address_components,GOO_ADMIN1,GOO_LONG_NAME);
            $this->results[$i]['country_short'] = $this->getAddressComponent($metaData->address_components,GOO_COUNTRY,GOO_SHORT_NAME);
            $this->results[$i]['country_long'] =  $this->getAddressComponent($metaData->address_components,GOO_COUNTRY,GOO_LONG_NAME);

            $this->results[$i][GOO_STREET_NUMBER] =  $this->getAddressComponent($metaData->address_components,GOO_STREET_NUMBER,GOO_LONG_NAME);
            $this->results[$i][GOO_ROUTE] =  $this->getAddressComponent($metaData->address_components,GOO_ROUTE,GOO_LONG_NAME);
            $this->results[$i][GOO_STREET_NEIGHBORHOOD] =  $this->getAddressComponent($metaData->address_components,GOO_STREET_NEIGHBORHOOD,GOO_LONG_NAME);
            $this->results[$i][GOO_POSTALCODE] =  $this->getAddressComponent($metaData->address_components,GOO_POSTALCODE,GOO_LONG_NAME);
            $this->results[$i][GOO_CITY] =  $this->getAddressComponent($metaData->address_components,'locality',GOO_SHORT_NAME);
              }

            $this->results[$i][GOO_PLACE_ID]  = $metaData->place_id;

          $i++;
        }
        if (isset($this->results[0]))
        {
          return $this->results;
        }
        return null;
      }
      return false;
    }
    function find($name)
    {
       debug('FIND');
       $this->key = '&key='.GOO_API_KEY;
      $this->errorCode = '';
      $this->searchText = $name;
      $name = urlencode($name);
      $fields = '&fields=formatted_address,geometry,name,place_id,plus_code,types&inputtype=textquery';
      $url = $this->geoFindPlace .'query='.$name. $this->key;
      debug($url);
       $curlOpts = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HEADER => 0,
             CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_HTTPHEADER =>  array("Authorization: Bearer "),

        );
        //if (!is_null($this->post))
        //{
        //  $curlOpts[CURLOPT_POSTFIELDS] = http_build_query($this->post);
        //}
        $this->curlHandle = curl_init();
      curl_setopt_array($this->curlHandle, ($curlOpts));
      $temp = curl_exec($this->curlHandle);
      $temp = json_decode($temp);

      debug($temp);
      echo '<BR>END<br>';
      exit;
      if ($temp->status = GOO_STATUS_OK)
      {
        $name = str_replace("+", " ", $name);
        $temp = $this->parseResults($temp, $name);

        return $temp;
      }

      return false;
    }
    function search($name) {
      $this->key = '&key='.GOO_API_KEY;
      $this->errorCode = '';
      $this->searchText = $name;
      $name = urlencode($name);
      $fields = 'formatted_address,geometry,name,place_id,plus_code,types';
      $url = $this->geoCodeURL .$name.$this->key;

       $curlOpts = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HEADER => 0,
             CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_HTTPHEADER =>  array("Authorization: Bearer "),

        );
        //if (!is_null($this->post))
        //{
        //  $curlOpts[CURLOPT_POSTFIELDS] = http_build_query($this->post);
        //}
        $this->curlHandle = curl_init();
      curl_setopt_array($this->curlHandle, ($curlOpts));
      $temp = curl_exec($this->curlHandle);
      $temp = json_decode($temp);
      if ($temp->status = GOO_STATUS_OK)
      {
        $name = str_replace("+", " ", $name);
        $temp = $this->parseResults($temp, $name);

        return $temp;
      }

      return false;
    }
    function dayinfo($day = null, $lat  = null, $lon = null)
    {
        //$tz = $this->getTimezone($day, $lat, $lon);
        //date_default_timezone_set($tz['timeZoneId']);
        $info = array();
        $day = is_null($day) ? date('Y-m-d') : $day;
        $lat = is_null($lat) ? $this->latitude : $lat;
        $lon = is_null($lon) ? $this->longitude : $lon;
        $info = date_sun_info(strtotime($day), $lat, $lon);
        $temp = (($info['sunset'] - $info['sunrise']) /3600);

        $hoursOfLight = round($temp,2);

        $info['light'] = $hoursOfLight;
        $info['latitude'] = $lat;
        $info['longitude'] = $lon;
        return $info;
   }
    function getTimezone($date = null, $latitude = null, $longitude = null)
    {
      $this->key = '&key='.GOO_API_TZKEY;
      $tstamp = strtotime($date);
      //debug('latitude:'.$latitude.' longitude:'.$longitude);
       $url = GOO_TIMEZONE_URL.$latitude.','.$longitude.'&timestamp='.$tstamp. $this->key;

      $curlOpts = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HEADER => 0,
             CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_HTTPHEADER =>  array("Authorization: Bearer "),

        );
        if (!is_null($this->post))
        {
          $curlOpts[CURLOPT_POSTFIELDS] = http_build_query($this->post);
        }
        $this->curlHandle = curl_init();
      curl_setopt_array($this->curlHandle, ($curlOpts));
      $temp = curl_exec($this->curlHandle);

      $tz = json_decode($temp);
      $tz = get_object_vars($tz);
      return $tz;
    }

    function reverse($latitude = '', $longitude = '', $firstonly = true) {

      $this->key = '&key='.GOO_API_KEY;
      $searchPlace = GOO_REVERSE_GEOCODING_URL.$latitude.','.$longitude.$this->googleSensor. $this->key;
      $url = $searchPlace;
      $curlOpts = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HEADER => 0,
             CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            //CURLOPT_HTTPHEADER =>  array("Authorization: Bearer ".$this->accessToken),
            CURLOPT_HTTPHEADER =>  array("Authorization: Bearer "),

        );
        if (isset($this->post) && !is_null($this->post)  && (count($this->post) > 0))
        {
          $curlOpts[CURLOPT_POSTFIELDS] = http_build_query($this->post);
        }
        $this->curlHandle = curl_init();
      curl_setopt_array($this->curlHandle, ($curlOpts));
      $temp = curl_exec($this->curlHandle);
        $temp = json_decode($temp);
        
      if (isset($temp->error_message))
      {
        return $temp->error_message;
      }
      $results =  $this->parseResults($temp);

      if ($firstonly)
      {
        return $results[0];
      }
      return $results;
    }
    function getName($i = 0) {
      if (count($this->results) > 0) {
        return $this->results[$i]['address'];
      }
    }
  }
?>
