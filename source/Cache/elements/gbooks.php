<?php
class gbooksComponent
{
	var $APIKEY = "AIzaSyAS5815JrGPMSsO-cB-3nOuZaNcpeqW07U";
	var $BASEURL = 'https://www.googleapis.com/books/v1/';
	var $lastJSON = '';
	var $errorMsg = "";
	public function initbook()
	{
			$book = array();
			$book['publisher'] = "";
			$book['author'] = "";
			$book['external_id'] = "";
			$book['ISBN_10'] = "";
			$book['ISBN_13'] = "";
			return $book;
	}
	public function parseVolumeInfo($parseVolumeInfo, &$book)
	{
		

		foreach($parseVolumeInfo as $field=>$value)
		{

			switch ($field)
			{
				
				case 'infoLink':
				case 'title':
				case 'subtitle':
				case 'publisher':
				case 'publishedDate':
				case 'description':
				case 'pageCount':
					$book[$field] = $value;
				break;
				case 'authors':
					if (count($value) >= 1)
					{
					 	$book['author'] = $value[0];
					}
				break;
				case 'industryIdentifiers':
					foreach ($value as $i=>$identifier)
					{
						if (($identifier->type == 'ISBN_13')  || ($identifier->type == 'ISBN_10'))
						{
							$book[$identifier->type] = $identifier->identifier;
						}
					}
				break;
				case 'categories':
					$cats = '';
					foreach ($value as $i=>$category)
					{
						$cats .= " ".strtolower($category);
					}
					$book['categories'] = trim(substr(trim($cats),0, 500));
				break;
				case 'language':
				$book['language'] = $value;
				break;
				case 'imageLinks':
					$book['smallThumbnail'] = isset($value->smallThumbnail) ? $value->smallThumbnail : "";
					$book['thumbnail'] = isset($value->thumbnail) ? $value->thumbnail : "";;
					$book['small'] = isset($value->small) ? $value->small : "";
					$book['medium'] = isset($value->medium) ? $value->medium : "";
					$book['large'] = isset($value->large) ? $value->large : "";
					$book['exgtraLarge'] = isset($value->extraLarge) ? $value->extraLarge : "";
				break;

			}
		}

		return true;
	}
	public function parseBook($bookObj)

	{
		
		//debug($bookObj);
		//exit;
		$book = $this->initbook();

		foreach ($bookObj as  $field=>$value )
		{	
			switch ($field)
			{
				case 'id':
				
					$book['external_'.$field] = $value;
				break;
				case 'selfLink':
		
					$book[$field] = $value;
				break;
				case 'volumeInfo':
					$this->parseVolumeInfo($value, $book);
					
				break;
				case 'searchInfo':
					$book['textSnippet'] = $value->textSnippet;
				break;

			}
		
		}
		return $book;
	}

	public function parseBooks($booksObj = null)
	{

		$books = array();
		$m = 0;
		foreach ($booksObj as $bookObj)
		{	
						
				$books[$m] = $this->parseBook($bookObj);
				$m = $m + 1;
			
		}
		return $books;

	}

	public function find($searchText = '')
  {
  	$tempJson = null;
  	if ($searchText != "")
  	{
	  	$searchText = str_replace(" ", "+", $searchText);
	    $url = $this->BASEURL . 'volumes?q='.$searchText.'&maxResults=30&key='.$this->APIKEY;
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
	      debug($url);

	      $tempJson = json_decode($tempJson);
	  // 	debug($tempJson);
	    
	      if (isset($tempJson->errors)) {
	        echo "RATE LIMITED EXCEEDED<BR>";

	        return false;
	      }

	      if (isset($tempJson->error))
	      {
	      	$this->errorMsg = $this->tempJson->message;
	      	return false;
	      }
	      if ($tempJson->totalItems > 0)
	      {
	      	$books = ($tempJson) ? $this->parseBooks($tempJson->items) : array();
	    	} else
	    	{
	    		return $tempJson;
	    	}
	      return $books;
	    }
	    return array();
  }
 
  public function getByISBN($f='')
  {
  	echo "<h3>GEt By ISBN</h3>";
  	$x = $this->find(''.$f);
  	debug(is_array($x));
  	debug($x);

  	if ($x)
  	{
  		debug(count($x));
  		$found = false;
  		debug("How many:".$count($x));
  		$i = 0;
  		while ((!$found) && ($i < count($x)))
  		{
  			if ($f == $x[$i]['ISBN_13'])
  			{
  				$found = true;
  				debug($x[$i]);
  				return $x[$i];
  			}
  			$i + 1;
  		}
  		return false;
  	}
  	return false;
  }
  public function getFromID($external_id = "")
  {
  	echo "external id=".$external_id.B;
  	$url = 'https://www.googleapis.com/books/v1/volumes/'.$external_id;
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
     // debug($tempJson);
      $book = $this->parseBook($tempJson);
    	return $book;
  }


}
?>