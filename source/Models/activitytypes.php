<?php
class activitytypesModel extends ModelClass
{
  public function __construct()
  {
    parent::__construct();
  }
  public function find($options = array(), $format = null)
  {
    $temp = parent::find($options);
    $records = array();
    foreach($temp as $i=>$info)
    {
      $records[$info['activitytype_id']] = $info;
    }
    unset($temp);
    return $records;
  }
}
?>
