<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	//Structure of application
	    define('ROOT', (dirname(dirname(__FILE__))));
	    define('SOURCE', ROOT.'/source/');
	    define('CONFIG', SOURCE.'/Config/');
	    define('CONTROLLERS', SOURCE.'Controllers/');
	    define('LOGS', SOURCE.'logs/log.txt');

	    define('VIEWS', SOURCE.'Views/');
	    define('ELEMENTS', SOURCE.'/Views/elements/');
	    define('TEMPLATES', SOURCE.'/Views/templates/');
	    define('MODELS', SOURCE.'/Models/');
	    define('HELPERS', SOURCE.'/Helpers/');
	    define('COMPONENTS', SOURCE.'/Components/');
	    define('MAINTENANCE', SOURCE.'Maintenance/');


	include(CONFIG."utilities.php");
	include(CONFIG."settings.php");

	include(CONFIG."database.php");
	include(CONFIG."routes.php");

 	include (MODELS.'/model.php');
 	$foobar = "david";

	class ApplicationClass
	{
		const DEFAULT_ACTION = 'logbook';
		var $params = array();
		var $alerts = array();
		var $request = '';
		var $data = null;

		public function __construct()
		{
			global $DEFAULT_DOMAINS;
			global $ROUTES;
			$this->request = $_SERVER['REQUEST_METHOD'];
			$this->data = count($_POST) > 0 ? $_POST : null;
			$this->params['passed'] = array();
			$this->params['named'] = array();
			$this->params['uri'] = $_SERVER['REQUEST_URI'];
			$this->params['domain'] = $_SERVER['HTTP_HOST'];
			$this->params['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
			$temp = substr($this->params['uri'],1,strlen($this->params['uri']));
			$uriParts = explode("/", $temp);
			$uriParts[0] = isset($uriParts[0]) && ($uriParts[0] != '') ? $uriParts[0] : '/';
			$uriParts[1] = empty($uriParts) ? DEFAULT_CONTROLLER : $uriParts[1];
			if (count($uriParts) == 2)
			{
				//has a controller been specified?
				if ($uriParts[1] != '')
				{
					$this->params['controller'] = $uriParts[1];
				} else {
					$this->params['passed'] = array();
						$this->params['controller'] = DEFAULT_CONTROLLER;
						$this->params['action'] = DEFAULT_ACTION;
				}
			} elseif (count($uriParts) == 3)
			{
				$this->params['controller'] = $uriParts[1];
				$this->params['action'] = !empty($uriParts[2]) ? $uriParts[2] : DEFAULT_ACTION;
			}
			if (isset($ROUTES[$uriParts[1]]) && isset($ROUTES[$uriParts[1]]['controller']) && isset($ROUTES[$uriParts[1]]['action']))
			{
				$this->params['controller']= $ROUTES[$uriParts[1]]['controller'];
				$this->params['action']= $ROUTES[$uriParts[1]]['action'];
			}
			$this->params['passed'] = array();
			if (count($uriParts) > 3)
			{
				$this->params['controller']= $uriParts[1];
				$this->params['action']= $uriParts[2];
				for ($i = 3; $i < count($uriParts); $i++)
				{
					$this->params['passed'][count($this->params['passed'])] = $uriParts[$i];
				}
			}
			if (count($this->params['passed']) > 0)
			{
				foreach ($this->params['passed'] as $key=>$value)
				{
					if (strpos($value, ":"))
					{
						$keyValue = explode(":", $value);
						if (count($keyValue) >= 2)
						{
							$keyValue[0] = strtolower($keyValue[0]);
							$this->params['named'][$keyValue[0]] = $keyValue[1];
						}
					}
				}
			}
			//exception for strava_callback
			if (strpos( 'x'.$this->params['action'], 'strava_callback'))
			{
				$this->params['action'] = 'strava_callback';
			}
		}

		public function redirect($url = "")
		{
			header('Location: '.$url);
			return true;
		}
		public function countAlerts()
		{
			return (count($this->alerts));
		}
		public function pushAlert($message = '', $alertType = 'alert-primary')
		{
			if ($message != '')
			{
				$i = count($this->alerts);
				$this->alerts[$i]['message'] = $message;
				$this->alerts[$i]['type'] = $alertType;
				return $i;
			}
		}
		public function popAlert()
		{
			if (count($this->alerts) > 0)
			{
				$lastIndex = count($this->alerts) -1;
				$poppedAlert = $this->alerts[$lastIndex];

				unset($this->alerts[$lastIndex]);
				return $poppedAlert;

			}
			return false;
		}

		public function clearAlerts()
		{
		   while ($this->popAlert());
		}

	}
	$app = new ApplicationClass;
	class errorHandler extends Exception { }
	if ($app->params['controller'] != "")
	{
		try
		{

			$controllerFilename = CONTROLLERS.$app->params['controller'].'Controller.php';
			if (!file_exists($controllerFilename))
			{
				throw new errorHandler('ERROR: '.$app->params['controller'].' Controller does not exist');
				exit;
			}

			include($controllerFilename);
			$className = $app->params['controller'].'Controller';
			$controller = new $className($app->params['controller'], $app->params['action']);

			$controller->app = $app;
			$controller->params = $app->params;
			$controller->request = $controller->app->request;
			$controller->data = $controller->app->data;
			//$controller->tag->params['controller'] = $app->params['controller'];
			//$controller->tag->params['action']   = $app->params['action'] ;

			if (method_exists($controller, $controller->action))
			{
				$controller->beforeAction();

				call_user_func_array(array($controller,$controller->action), $app->params['passed']);
				$controller->render();
			} else
			{
				throw new errorHandler('ERROR: '.$controller->action.' Action does not exist');
				exit;

			}
		}
		catch (errorHandler $e)
		{
			echo 'Caught MY exception: ',  $e->getMessage(), "\n";
			exit;
		}

	}



?>
