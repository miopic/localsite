<?php
#[\AllowDynamicProperties]
class ModelClass
{
   var $config;
   var $connection;
   var $SQL = '';
   var $resultCount = null;
   var $model = 'activities';
   var $modelFields = array();
   var $orderDirection = '';
   var $created = 'created';
   var $modified = 'modified';
   var $error = '';
   var $logHandle = null;
   var $typeField = null;
   var $typeResults = array();
   var $limit = 20;
   var $idField = 'id';
   var $db = "";
   const DEF_ORDER = 'id';
   const DEF_PAGE = 1;
   const DEF_LIMIT = 20;
   function __construct()
   {

       try
     {
        $this->config = new DATABASE_CONFIG;
        $host = $this->config->default['host'];
        $user = $this->config->default['login'];
        $pass = $this->config->default['password'];
         $db = $this->db == "" ? $this->config->default['database'] : $this->db;
         $dsn = 'mysql:host='.$host.';dbname='.$db;
         $this->connection = new mysqli($host, $user, $pass, $db);
         $name = get_class($this);
        $this->model = str_replace('Model', '', $name);

        if ($this->connection->connect_errno)
        {
                  throw new errorHandler('ERROR: failed to connect to the database.<BR>'.$mysqli->connect_errno.' '.$mysqli->connect_error );
          return false;
        }
         $this->getFields();
      } catch (errorHandler $e)
      {
        echo 'Caught MY exception: ',  $e->getMessage(), "\n";
      }

      return true;
   }

    //Constructs a sql SELECT statement
    private function setSelect($options = array())
    {

      $whereClause = isset($options['conditions']) && ($options['conditions'] != '')? " WHERE ".$options['conditions'] . " " : "";
      $groupClause = isset($options['group']) ? " GROUP BY ".$options['group'] . " " : "";
      $fromClause = isset($options['table']) ? $options['table'] : $this->model;
      $fromClause = ' FROM '.$fromClause;
       //Assumes fields are a comma seperated list
      $fieldClause = isset($options['fields']) ? $options['fields'] : '*';
      $fieldClause = 'SELECT '.$fieldClause;
      
      $limitClause = '';
      $startingAtRecord = 0;
      if (isset($options['limit']))
      {
        $limit = $options['limit'];
      } else {
        $limit = $this->limit;
      } 
      if (isset($options['page']))
      {
        $startingAtRecord = $options['page'] * $limit;
        
      } else
      {
        
        $startingAtRecord = 0;
      }
      
      $limitClause =  ' LIMIT '.$startingAtRecord.','  . $limit;

      $joinClause = isset($options['join']) ? ' LEFT JOIN ' . $options['join'] . ' ' : '';
      $orderClause = isset($options['order']) ? $options['order'] : '';
      $orderClause = $orderClause!= '' ?' ORDER BY '. $orderClause : $orderClause;
      $whereClause = isset($options['conditions'])  && ($options['conditions'] != '') ? " WHERE ".$options['conditions']: '';
      //$whereClause .= $whereClause == '' ? '' : ' AND deleted is null';
      $this->SQL = $fieldClause . $fromClause . $joinClause.$whereClause . $groupClause . $orderClause . $limitClause.';';
      $temp = array(1, 2, 3, array(4, 5, 6));
      $this->countSQL = '';
      if (SHOW_SQL)
      {
        debug($this->SQL);
      }
      //echo "<h5>".$this->SQL."<h5>";
      return $this->SQL;
  }
   //Constructs a sql UPDATE statement
  private function setUpdate($table = '', $data = array())
  {

    if (($table == '') || (count($data) == 0))
    {
      return false;
    }
    if (!isset($data['id']) || ($data['id'] == '') )
    {
      return false;
    }
    $temp = 'UPDATE '.$table.' SET ';
    $fieldValues = array();
    $i = 0;

    //if the model has a modified field set it.
    if (array_search($this->modified, $this->modelFields))
    {

      $n = count($fieldValues);
      $fieldValues[$n] = " ".$this->modified." = '".date('Y-m-d H:i:s')."'";
    }
    foreach($data as $key=>$value)
    {
      switch ($key) {
        //exceptions for calculated fields
        case 'miles':
        case 'yards':
        case 'kilometers':
        case 'total_elevation_gain_feet':
        case 'total_elevation_loss_feet':
        case 'rest_time';
          break;
        
        default:
        
          $n = count($fieldValues);
          if (is_numeric($value))
          {
            $fieldValues[$n] = $key.'='.$value;
          } else
          {
            $fieldValues[$n] = $key."= '".$value."'";
          }
          break;
      }

    }
    $temp = $temp . implode(', ',$fieldValues);
    if (is_numeric($data['id']))
    {
      $temp .= ' WHERE id = '.$data['id'];
    } else
    {
      $temp .= " WHERE id = '".$data['id']."';";
    }
    $this->SQL = $temp;
    if (SHOW_SQL)
    {
      debug($this->SQL);
    }
    return $this->SQL;
  }
  public function setInsert($table = '', $data = array())
  {
    //If the model has a created field add the date to the created field
  if (array_search($this->created, $this->modelFields))
    {
      $data[$this->created] = date('Y-m-d H:i:s');
    }
    //If the model has a modified field add the date to the modified field
    if (array_search($this->modified, $this->modelFields))
    {
      $data[$this->modified] = date('Y-m-d H:i:s');
    }

    $saveThis = array();


    //get the columns from the data
    $columns = array_keys($data);
    $columns = implode(', ', $columns);
    $values = implode("', '", $data);
    $values = str_replace("''", 'NULL', $values);

    $temp = 'INSERT INTO '.$table. " (".$columns.") VALUES ('".$values."')".";";
    $this->SQL = $temp;

    return $this->SQL;
  }

  public function describe($table = 'activities')
  {
    $q = "DESCRIBE ".$table.";";
     $result = array();
    if ($this->connection)
    {

      $fields = $this->connection->query($q);
    //  debug($fields);exit;
      /*
      foreach ($fields as $key=>$attribs)
      {
        $result[$attribs['Field']] = $attribs['Type'];
      }*/
      return $fields;
    } else
    {
      echo "why is there no connection";
    }
    exit;
  }
  public function getFields()
  {
    if ($this->connection)
    {
      $sql = 'SELECT * FROM '. $this->model.' LIMIT 0,0';
      $result = $this->connection->query($sql);
      $r = $result->fetch_fields();
      $this->modelFields = array();
      foreach($r as $i=>$obj)
      {
        $this->modelFields[count($this->modelFields)] = $obj->orgname;
      }
      return true;
    }
  }
  public function insert($data)
  {
    // debug('data passed to insert');
    // debug($data);
      $table = $this->model;
    foreach ($data as $key=>$value)
    {
      // debug($key.':['.$value.']');
      if (in_array($key, $this->modelFields))
      {
        if (is_null($value) || ($value=='') || empty($value))
        {
          unset($data[$key]);
        } else
        {
          try {
            $data[$key] = !is_null($value) ? $this->connection->real_escape_string($value) : 0;
          } catch (Exception $error)
          {
            $this->app->pushAlert('Caught exception: '.  $e->getMessage());
          }
        }
      } else
      {
         unset($data[$key]);
      }
    }

    $sql = $this->setInsert($table, $data);
    $result = $this->connection->query($sql);
     if (!$result)
     {
      $this->error = $this->connection->error;
      $returnVal = false;
      } else
    {
        $returnVal = $this->connection->insert_id;
        if ($returnVal == 0)
        {
          $returnVal = $data['id'];
        }
      $this->error = '';
    }

      return $returnVal;
  }
  public function insertAll($dataToAdd = array())
  {
    $table = $this->model;
    $sql = '';
    $result = true;

    foreach ($dataToAdd as $key=>$data)
    {
      foreach ($data as $field=>$value)
      {
        if (in_array($field, $this->modelFields))
        {
          if (is_null($value) || ($value=='') || empty($value))
          {
            unset($data[$field]);
          } else
          {
              $data[$field] = $this->connection->real_escape_string($value);
          }

        } else
        {
           unset($data[$field]);
        }
      }

       $sql = $this->setInsert($table, $data);
       try
       {
         $result = $this->connection->query($sql);
       } catch (Exception $exc)
       {
         echo 'Caught exception: ', $exc->getMessage(), "<BR>\n";
         echo $sql;
         exit;
       }

       if (!$result)
       {
        $this->error = $this->connection->error;
        } else
      {
        $this->error = '';
      }
    }



      return $result;
  }
  public function update($data)
  {
    $table = $this->model;
    if ($table == '')
    {
      return false;
    }
    //debug($data);

    //validate fields
    //unset any fields not in the model;
    foreach ($data as $key=>$value)
    {
      if (in_array($key, $this->modelFields))
      {

        try {

          $data[$key] = !is_null($value) ? $this->connection->real_escape_string($value) : 0;
        } catch (Exception $error)
        {
          $this->app->pushAlert('Caught exception ['.$value.']: '.  $e->getMessage());
        }
      } else
      {
         unset($data[$key]);
      }
    }

    $sql = $this->setUpdate($table, $data);
    if ($sql)
    {
      $this->SQL = $sql;

      $result = $this->connection->query($this->SQL);

      if (!$result)
      {
        $this->error = $this->connection->error;
        debug($this->error);
      } else
      {
        $this->error = '';
      }
      return $result;
    }
    return false;


  }
  public function count($conditions = '')
  {
      $options['conditions'] = $conditions;
      $options['fields'] = 'count(*) as count';
      $options['nolimit'] = 1;
      $c = $this->find($options);

      if ($c)
      {
        foreach ($c as $i=>$data)
        {
          $result = $data['count'];
          $result = $result + 0;
          break;
        }
      }
      return $result;

  }
  public function find($options = array(), $format = null)
  {
    $this->setSelect($options);
    $rs = array();
    if ($this->SQL != '')
    {
      try {
        $this->result = $this->connection->query($this->SQL);
      } catch (Exception $e) {
        echo 'Caught exception: '.  $e->getMessage().':<br><br>';
        echo $this->SQL.'<br>';
      }
    } else
    {
      $this->pushAlert("No SQL specified", "alert-danger");
    }

    if ($this->result)
    {
    } else
    {
      echo "<h1>".$this->SQL."</h1>";
      echo "fuck: (" . $this->connection->errno . ") " . $this->connection->error.'<br>';
      debug($this->SQL);
      $this->SQL = '';
      exit;
    }
    $this->result->data_seek(0);
    $i = 0;
    $indexKey = isset($options['indexKey']) ? $options['indexKey'] : 'id';

    while ($row = $this->result->fetch_assoc())
    {
       $options['table'] = isset($options['table']) ? $options['table'] : $this->model;
       $indexKeyValue =  isset($row[$indexKey]) ? $row[$indexKey] : $i;
       $rs[$i]= array();
       $rs[$i] = array();

       foreach($row as $key=>$value)
       {
         // echo $indexKey.','.$options['table'].','.$key.','.$value.'<br><br>';
          //$rs[$indexKeyValue][$options['table']][$key] = $value;
           $rs[$i][$key] = $value;
       }
       $i++;

    }
    return $rs;
  }
  public function delete($id = null)
  {
    $result = $this->getByID($id);

    if ($result)
    {
      $thing['id'] = $id;
      $thing['deleted'] = 1;
      $this->SQL = "DELETE FROM ". $this->model." WHERE id = ".$id.";";
      //$this->result = $this->update($thing);
      $this->connection->query($this->SQL);
      return $this->result;
    }
    return false;
  }
  public function deleteWhere($where = null)
  {

    if (is_null($where))
    {
      debug("ERROR: NO WHERE CLAUSE SPECIFIED.");
      exit;
    }
    {
    $this->SQL = "DELETE FROM ". $this->model." ".$where.";\n";

    debug($this->SQL);
    exit;
    $this->result = $this->connection->query($this->SQL);
    debug($this->result);
    }
    exit;
  }
  public function getByID($id = null)
  {
    
    if (($id > 0) || ($id != ''))
    {
      $id = is_string($id) ? "'".$id."'" : $id;
      $options = array(
        'order' => $this->model.'.'.$this->idField.' DESC',
        'conditions' => $this->model.'.'.$this->idField.' = '. $id,
        'limit' => 1
      );
      $temp = $this->find($options);    
      if (isset($temp[0]))
      {
        $result = $temp[0];
      } else
      {
        $result= null;
      }
      
      return $result;
    }
    return false;
  }
}


?>
