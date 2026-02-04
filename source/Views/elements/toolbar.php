<?php
  $currentLink = '';
  $previousLink = '';
  $nextLink = '';
  $date = "";
  switch($this->action)
  {
    case "yearbymonth":
      $date = date("Y", strtotime($this->fromDate));
      $currentLink = DS . $this->app->params['controller'] . DS . $this->app->params['action'] .DS;
      $currentLink .= $this->fromDate . DS . $this->toDate.DS.$this->typeID;
      $currentLink = $this->html->link($date, $currentLink);
      $previousLink = DS . $this->app->params['controller'] . DS . $this->app->params['action'] .DS;
      $previousLink .= $this->fromPreviousDate . DS . $this->toPreviousDate.DS.$this->typeID;
      $previousLink = $this->html->link(SYM_PREVIOUS, $previousLink);
      if (strtotime($this->fromNextDate) < strtotime(date(DATE_DAY)))
      {
        $nextLink = DS . $this->app->params['controller'] . DS . $this->app->params['action'] .DS;
        $nextLink .= $this->fromNextDate . DS . $this->toNextDate.DS.$this->typeID;
        $nextLink = $this->html->link(SYM_NEXT, $nextLink);
      } else
      {
        $nextLink = '';
      }
    break;
    case 'monthbyday':
     $date = date("Y-M", strtotime($this->fromDate));
      $currentLink = DS . $this->app->params['controller'] . DS . $this->app->params['action'] .DS;
      $currentLink .= $this->fromDate . DS . $this->toDate.DS.$this->typeID;
      $currentLink = $this->html->link($date, $currentLink);
      $previousLink = DS . $this->app->params['controller'] . DS . $this->app->params['action'] .DS;
      $previousLink .= $this->fromPreviousDate . DS . $this->toPreviousDate.DS.$this->typeID;
      $previousLink = $this->html->link(SYM_PREVIOUS, $previousLink);
      if (strtotime($this->fromNextDate) < strtotime(date(DATE_DAY)))
      {
        $nextLink = DS . $this->app->params['controller'] . DS . $this->app->params['action'] .DS;
        $nextLink .= $this->fromNextDate . DS . $this->toNextDate.DS.$this->typeID;
        $nextLink = $this->html->link(SYM_NEXT, $nextLink);
      } else
      {
        $nextLink = '';
      }
    break;
    case 'weekbyday':
     $date = date("Y-M-d", strtotime($this->fromDate));
      $currentLink = DS . $this->app->params['controller'] . DS . $this->app->params['action'] .DS;
      $currentLink .= $this->fromDate . DS . $this->toDate.DS.$this->typeID;
      $currentLink = $this->html->link($date, $currentLink);
      $previousLink = DS . $this->app->params['controller'] . DS . $this->app->params['action'] .DS;
      $previousLink .= $this->fromPreviousDate . DS . $this->toPreviousDate.DS.$this->typeID;
      $previousLink = $this->html->link(SYM_PREVIOUS, $previousLink);
      if (strtotime($this->fromNextDate) < strtotime(date(DATE_DAY)))
      {
        $nextLink = DS . $this->app->params['controller'] . DS . $this->app->params['action'] .DS;
        $nextLink .= $this->fromNextDate . DS . $this->toNextDate.DS.$this->typeID;
        $nextLink = $this->html->link(SYM_NEXT, $nextLink);
      } else
      {
        $nextLink = '';
      }
    break;
    case "alltime":
    break;
  }

 
?>
  
    <div class="float-right">
<?php
    foreach($this->modelObject->activityTypes as $key=>$value)
    {

      if ($value['activitytypes']['id'] <> 0)
      {
        $styleImage = $value['activitytypes']['id'] == $this->typeID ? 'border-bottom:1px solid black' : '';
        if ($value['activitytypes']['image'] != "")
        {
          $imageLink = "/".$this->app->params['controller']."/".$this->app->params['action']."/".$this->fromDate."/".$this->toDate."/".$value['activitytypes']['id'];
          $image = $this->html->image('/types/'.$value['activitytypes']['image'], array('height'=>24, 'width'=>24, 'style'=>$styleImage)).'&nbsp;';
          echo $this->html->link($image, $imageLink);
        }
      }
    }
?>   </div>
