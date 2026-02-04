<?php
class authComponent
{
	var $loginHere = APP_NAME.'/users/login';
	var $logoutRedirect = "/";
	var $loginRedirect = APP_NAME.'/activities/view';
	var $sessionName = "user";
	var $userSessionFields = array('id', 'username', 'fullname', 'strava_id', 'firstname', 'lastname', 'created', 'modified', 'access_token', 'refresh_token',  'expires_at', 'dob', 'email', 'units');
	var $allow = null;
	var $action = null;
	public function __construct($action, $allow='hellow world')
	{
		$status = session_status();
		if ($status == PHP_SESSION_ACTIVE)
		{

		} else {

		}
		$this->action = $action;
		$this->allow = $allow;

		return true;

	}
	public function initialize()
	{
		if (!headers_sent())
		{
			header("Access-Control-Allow-Origin: http://dwm.io/folders/list/");

			session_start();

		}
		if (in_array($this->action, $this->allow) )
		{

		} elseif ($this->loggedIn())
		{

		} else
		{
			$cookie_name = "referer";
				$cookie_value = $_SERVER['REQUEST_URI'];
				setcookie($cookie_name, $cookie_value, time() + (3600), "/");
			if (!headers_sent())
			{


				header('Location: '.$this->loginHere );
			}
		}

		return true;
	}
	public function loggedIn()
	{
		return (isset($_SESSION['user']['username'])) && ($_SESSION['user']['username'] != '');
	}
	public function logout()
	{

			session_destroy();

		if ($this->logoutRedirect != '')
		{

			header('Location: '.$this->logoutRedirect);
		}
		return true;
	}
	public function login($data = null, $user = null)
	{

		$isLoggedIn = password_verify($data['pw'], $user['pw']);

		if ($isLoggedIn)
		{
			if (session_status() != PHP_SESSION_ACTIVE)
			{
				session_start();
			}
			foreach($this->userSessionFields as $key)
			{
			 $temp[$key] = $user[$key];
			}
			$this->setUser($temp);

        		$goHere = $this->loginRedirect;
			if (isset($_COOKIE['referer']))
			{
				$goHere = $_COOKIE['referer'];
				setcookie("referer", "", time() - 3600);

			}
			header("Location: ". $goHere);
			//header("Location: https://dwm.io/app/books/library");

		} else
		{
			return false;
		}
		exit;

	}
	public function user($field)
	{
		return isset($_SESSION['user'][$field]) ? $_SESSION['user'][$field] : '';
	}

	public function setUser($user = null)
	{
		if (!is_null($user)  && is_array($user))
		{

			$_SESSION['user'] = $user;

			return true;
		}
		return false;
	}
	public function getUser($field = '')
	{
		if ($field == '')
		{

			$temp['user'] = isset($_SESSION['user']) ? $_SESSION['user'] : null;
			return $temp;
		}
		return isset($_SESSION['user'][$field]) ? $_SESSION['user'][$field] : false;
	}
}
?>
