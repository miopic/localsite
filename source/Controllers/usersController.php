<?php
require_once('Controller.php');

class usersController extends Controller
{
	var $page = '';
	public function beforeAction()
	{
		parent::beforeAction();
		$this->auth->initialize();
		return true;
	}
	var $components = array('auth');
	var $allow = array('login','register');
	public function index()
	{
		echo "hello";
	}
	public function login()
	{
		if ($this->request == 'POST')
		{
			if ($this->data['username'] == '')
			{

				$this->app->pushAlert('ERROR: login failed!');

			}elseif ($this->data['pw'] == '')
			{
				$this->app->pushAlert('ERROR:  login failed!');

			} else
			{
				//Retrieve the user

				$user = $this->users->getUserByName($this->data['username']);

				$isLoggedIn = $this->auth->login($this->data, $user);
				if (!$isLoggedIn)
				{
					$this->app->pushAlert('Not logged in', 'alert-danger');
				} else {
					echo "Logged in<br>";
				}
			}

		}
	}
	public function register()
	{
		if ($this->request == 'POST')
		{
			debug($this->request);debug($this->data);
			if ($this->data['pw'] == '')
			{
				$this->pushAlert('ERROR: No password specified!');
				echo "<h1>ERROR: No password specified for registration</h1>";
				exit;
			}
			$this->data['pw'] = password_hash($this->data['pw'], PASSWORD_DEFAULT);
			$result = $this->users->insert( $this->data);
			if ($result)
			{
				echo 'Registered</br>';
				echo $this->users->error.'<br>';
			} else
			{
				echo 'Not Registered</br>';
				echo $this->users->error.'<br>';
				exit;
			}
		}
	}
	public function logout()
	{
		$this->auth->logout();
	}
	public function profile($username)
	{
		$user = $this->users->getUserByName($username);

		unset($user['pw']);
		unset($user['password']);
		$this->set('user', $user);

	}
	public function changeunits()
	{
		$updateInfo = array('id'=>$this->user['id'], 'units'=>$this->user['units']);
		if ($this->user['units'] == METRIC)
		{
			$this->user['units'] = IMPERIAL;
		} else {
			$this->user['units'] = METRIC;
		}
		$updateInfo['units'] = $this->user['units'];
		$this->users->update($updateInfo);
		$this->auth->setUser($this->user);
		$this->redirect($_SERVER['HTTP_REFERER']);
	}

}

?>
