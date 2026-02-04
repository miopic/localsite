<?php
#[\AllowDynamicProperties]
class Controller
{
	var $request = null;

	var $modelObjectName = "";
	var $modelObject = null;
	var $viewVars = array();
	var $layout = DEFAULT_TEMPLATE;
	var $name = '';
	var $helpers = array('html', 'tag');
	var $controller = null;
	var $action = null;

	var $alerts = array();
	var $point;
	var $allow = array();
	var $timezone =DEFAULT_TMZ;
	var $timeframes = array('year'=>'year', 'month'=>'month');
	var $units = DEFAULT_UNITS;

	var $user = null;
	var $efforts = null;
	var $segments = null;

	var $status = array(3);



	public function __construct($controllerName = null, $action = null)
	{
		foreach($this->status as $i=>$v)
		{
			$this->status[$i] = '';
		}
		$this->controller = $controllerName;
		$this->action = $action;
		$this->name = get_class ($this );
		$this->name = str_replace("Controller", "",$this->name);

		if (file_exists(MODELS.$this->name.".php"))
		{
			include(MODELS.$this->name.".php");
			//Instantiate the model the controller will use to get data
			$this->modelObjectName = $this->name."Model";
			if (class_exists($this->modelObjectName))
			{
				${$this->name} = null;
				$this->{$this->name} = new $this->modelObjectName ;
			}
		}
		if (isset($this->otherModels))
		{
			foreach($this->otherModels as $name)
			{
				if (file_exists(MODELS.$name.".php"))
				{
					include(MODELS.$name.".php");
					//Instantiate the model the controller will use to get data
					$modelObjectName = $name."Model";
					if (class_exists($modelObjectName))
					{
						${$name} = null;
						//$this->modelObject = ${$this->name};
						$this->{$name} = new $modelObjectName;
					}
				}
			}

		}
		//load helpers;
		if (count(($this->helpers)) > 0)
		{
			foreach($this->helpers as $name)
			{
				//Does file exist;
				if (file_exists(HELPERS.$name.".php"))
				{
					include(HELPERS.$name.".php");
					$className = $name.'Helper';
					$this->$name = new $className;
				} else
				{
					echo "<h1>ERROR: ".HELPERS.$name.".php". " was not found</h1>";
				}
			}
		}
		if (isset($this->components) && count(($this->components)) > 0)
		{
			foreach($this->components as $name)
			{
				//Does file exist;
				if (file_exists(COMPONENTS.$name.".php"))
				{
					include(COMPONENTS.$name.".php");
					$className = $name.'Component';

					switch ($name)
					{
						case 'auth':
							$this->$name = new $className($this->action,$this->allow);
						break;

						default:
						$this->$name = new $className;
						break;
					}

				} else
				{
					echo "<h1>ERROR: ".HELPERS.$name.".php". " was not found</h1>";
				}
			}
			$keys = array(0, 1, 2, 3);
			$this->status = array_fill_keys($keys, '');
			unset($keys);

		}

		//$this->user = $this->users->getByID(DEFAULT_USER);
		/*
		if (is_null($this->user))
		{
			echo "<h1>ERROR: access token not retrieved from default user</h1>";
			exit;
		}
		*/
		if (DEFAULT_UNITS == UNITS_METRIC)
		{

			if ($this->user['units'] == UNITS_METRIC)
				$$this->units = UNITS_METRIC;
		} else {
				$this->units = DEFAULT_UNITS;
		}
		//$this->set('measurementUnits', $measurementUnits);
		$this->set('timeframes', $this->timeframes);
		date_default_timezone_set($this->timezone);
		return true;
	}
	public function beforeAction()
	{
	}

	public function fields()
	{
		debug($this->name);
		debug($this->{$this->name}->describe($this->name));

		exit;
		$this->set('fields', $this->{$this->name}->modelFields);

	}
	public function data()
	{

	}
	public function exportCSV( $records)
	{
		if (count($records))
		{
			$fs = array_keys($records[0]);
		    $idx = 0;
		    header('Content-Type: text/csv');
				$fieldsOut = '';
		    foreach($fs as $f)
		    {
		      $fieldsOut .= '"'.$f.'"';
		        if ($idx < count($fs)-1)
		        {
		         $fieldsOut .=  ',';
		        }
		        $idx++;
		    }
				echo $fieldsOut;

		    //echo "\n";
		    foreach($records as $r)
		    {
		      $idx = 0;
		      foreach($r as $field=>$value)
		      {
		        echo '"'.$value.'"';
		        if ($idx < count($r)-1)
		        {
		          echo ',';
		        }
		        $idx++;

		      }
		      echo "\n";
		    }
		    exit;
		   }
	}
	public function element($element)
	{


		if (file_exists(ELEMENTS.$element.ELEMENT_EXT))
		{

			ob_start();
			foreach($this->viewVars as $key=>$value)
			{
				$$key = $value;
			}
			include(ELEMENTS.$element.ELEMENT_EXT);
			$result = ob_get_contents();
			ob_end_clean();
			return $result;
		}

		return false;
	}
	public function render()
	{
		if (isset($this->pages))
		{


		}

		foreach($this->viewVars as $key=>$value)
		{
			$$key = $value;
		}
		$page = null;
		if (($this->params['controller']  == 'pages'))
		{
			$page = $this->params['action'];
		}

		ob_start();
		if (is_null($page))
		{
			$viewFile = $this->action.VIEW_EXT;
		} else
		{
			$viewFile = $page.VIEW_EXT;
		}
		if (file_exists(VIEWS.$this->name.DS.$viewFile))
		{
			if (file_exists(VIEWS.$this->name.DS.$viewFile))
			{
				include(VIEWS.$this->name.DS.$viewFile);
			} else {
				echo "<h2>VIEW NOT FOUND: ". VIEWS.$this->name.DS.$viewFile."</h2>";
				exit;
			}
			$content = ob_get_contents();
			ob_end_clean();
			if ($this->layout != '')
			{
				include(TEMPLATES.$this->layout.'.php');
			}
			return true;
		} else
		{
			debug(VIEWS);
			debug(DS);

			debug($this->name);

			echo "<h1>ERROR: Missing view file [".VIEWS.$this->name.DS.$viewFile."]</h1>";
			exit;
		}

	}
	public function set($one, $two = null)
	{

		if (is_array($one)) {
			if (is_array($two)) {
				$data = array_combine($one, $two);
			} else {
				$data = $one;
			}
		} else {
			$data = array($one => $two);
		}

		$this->viewVars = $data + $this->viewVars;
	}
	public function getWeek($d = null)
    {
        if (is_null($d))
        {
            $d = date('Y-m-d');
        }
        $w = date('w',strtotime($d));
        $w == 0 ? $n = 6 : $n = $w - 1;
        $m = date('Y-m-d',strtotime($d." -".$n." days"));
        return $m;
    }

	public function redirect($url)
	{
		header ("Location: ".$url);

	}
	public function msg($name, $value)
	{
		echo "<h2>".$name.": [".$value."]</h2>";
	}

}
?>
