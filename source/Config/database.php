<?php
class DATABASE_CONFIG
{
	var $connection = null;
	var $default = array(
	 'datasource' => 'Database/Mysql',
	 'persistent' => false,
	 'host' => 'localhost',
	 'login' => 'root',
	 'password' => '*ucFQGYPtqmn.vqXzd2GW2Pyo*Zfnt',
	 'database' => 'app',
	);
   function __construct()
   {

         $host = $this->default['host'];
         $user = $this->default['login'];
         $pass = $this->default['password'];
         $db = $this->default['database'];
         $dsn = 'mysql:host='.$host.';dbname='.$db;
       return $this->connection;
   }

}

?>
