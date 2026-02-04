<?php

class usersModel extends ModelClass
{
	var $model = 'users';

    public function __construct()
    {
        parent::__construct();

        return true;
    }
    public function getUserByName($name)
    {
    	$options['fields'] = '*';
    	$options['table'] = $this->model;

    	$options['conditions'] = "username = '".$name."'";
    	$temp = $this->find($options);
			$user = null;
    	foreach($temp as $key=>$data)
    	{
    		$user = $data;
    		break;
    	}

    	return $user;
    }

 }
 ?>
