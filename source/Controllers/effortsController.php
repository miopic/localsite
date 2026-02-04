<?php
require_once('Controller.php');

class effortsController extends Controller
{
    var $otherModels = array('users', 'segments');

	var $components = array('auth', 'strava');
	var $allow = array('login');
	var $page = '';
	var $helpers = array('html', 'tag', 'pages', 'stopwatch');

	public function beforeAction()
	{

		parent::beforeAction();

    $this->auth->initialize();
    $this->strava->userModel = 'user';
    if ($this->auth->loggedIn())
    {

         $this->strava->refreshToken = $this->auth->getUser('refresh_token');
         $this->strava->accessToken = $this->auth->getUser('access_token');
         $this->strava->expiresAt = $this->auth->getUser('expires_at');


        $user = $this->auth->getUser();
        $timeRemaining = $this->strava->timeRemaining($user['user']['expires_at']);
        //$this->set('statusMessage', '['.$user['user']['expires_at'].']['.date('Y-m-d H:i:s', $user['user']['expires_at']).']['.$timeRemaining.']');
        if (($timeRemaining < HOUR) && !is_null($user))
        {

             // debug($user);
              //

             if ($this->strava->refreshAccessToken($user))
              {

                $this->users->update($user['user']);
                $this->auth->setUser($user['user']);


              } else
              {
                $this->app->pushAlert("Unable to retrieve a Strava token");
                return false;
              }

            $this->app->pushAlert('Token refreshed');
            $this->set('expiresAt', date('Y-m-d H:i',$user['user']['expires_at']));
            $timeRemaining = ($user['user']['expires_at'] - time())/HOUR;
            $this->set('timeRemaining', date('Y-m-d H:i',$user['user']['expires_at']));
          } else
          {
          }
        }



	}

    public function data($n = 1)
  {

    $temp = $this->efforts->modelFields;
    $fields = implode(', ', $temp);

      $options = array('limit'=>$n,'order'=>'start_date_local DESC', 'fields'=>$fields);

      $records = $this->efforts->find($options);
      $this->exportCSV($records);
      exit;
  }
  public function index()
  {
    debug('hello efforts');
  }

}

?>
