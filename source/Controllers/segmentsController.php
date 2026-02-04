<?php
require_once('Controller.php');

class segmentsController extends Controller
{
    var $otherModels = array('users', 'efforts', 'activitytypes');

	var $components = array('auth', 'strava');
	var $allow = array('login');
	var $page = '';
  var $helpers = array( 'html', 'stopwatch', 'tag');
  var $types;
	public function beforeAction()
	{
		parent::beforeAction();
    $this->types = $this->activitytypes->find();
    $this->set('types', $this->types);

    $this->auth->initialize();
    $this->strava->userModel = 'user';
    if ($this->auth->loggedIn())
    {
      //Refresh strava token for the user
        $this->strava->refreshToken = $this->auth->getUser('refresh_token');
        $this->strava->accessToken = $this->auth->getUser('access_token');
        $this->strava->expiresAt = $this->auth->getUser('expires_at');
        $user = $this->auth->getUser();
        $timeRemaining = $this->strava->timeRemaining($user['user']['expires_at']);
        //$this->set('statusMessage', '['.$user['user']['expires_at'].']['.date('Y-m-d H:i:s', $user['user']['expires_at']).']['.$timeRemaining.']');
        if (($timeRemaining < HOUR) && !is_null($user))
        {
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
	//json endpoint for getting a list of folders
	public function list()
	{

		header('Content-type: application/json');
		$options['fields'] = '*';
		$rs = $this->segments->find($options);
		$this->layout = 'empty';
		$x = array('david','marks', 'albertson');
		json_encode($rs);
		echo json_encode($rs);exit;

	}
	public function search()
	{

		$searchText = "";


		if ($this->request == POST)
		{
			$searchText = $this->data['searchText'];


		}

		$this->limit = 50;
    $conditions = $searchText != "" ? "name Like '%".$searchText."%'" : "";

    $records = $this->segments->find(array('order'=>'activitytype_id, name', 'conditions'=>$conditions));
		$this->set('records', $records);
		$this->set('searchText', $searchText);
	}
	public function view($segment_id = null)
  	{

  		$record = $this->segments->getByID($segment_id);

  		if (is_null($record))
  		{
    		$record = $this->strava->segment($segment_id);
    		$saveResult = $this->segments->insert($record);

        if ($saveResult)
        {
            $this->app->pushAlert('Segment "'.$record['name'].'" saved.');
        } else
        {
          $this->app->pushAlert('Segment "'.$record['name'].'" NOT saved.');
        }
    	}
      $filter = "segment_id = ".$segment_id;
      $totalEfforts = $this->efforts->count($filter);
      $fields = "id, activity_id, segment_id, activitytype_id, start_date_local, distance, average_heartrate, max_heartrate , moving_time";
      $efforts = $this->efforts->find(
      array('fields'=>$fields, 'conditions' => $filter, 'order' => 'moving_time ASC', 'limit' => 50 ));
      $totalEfforts = $this->efforts->count($filter);

      $order = 'efforts.moving_time ASC';
      if (isset($this->params['named']['sort']))
      {
        $order = $this->params['named']['sort']." DESC";
      }

    	$this->set('record', $record);
      $this->set('efforts', $efforts);
      $this->set('count', $totalEfforts);
    	$this->set('points', $this->strava->decode($record['polyline']));
      $this->set('mapsEnabled', true);
  	}
    public function data($n = 1)
  {
    $fields = 'id, activity_type, name, distance, average_grade, maximum_grade, total_elevation_gain, pr_elapsed_time, pr_date, effort_count, city, state, country, polyline';
      $options = array('limit'=>$n,'order'=>'start_date_local DESC', 'fields'=>$fields);

      $records = $this->segments->find($options);
      $this->exportCSV($records);
      exit;
  }

}

?>
