<?php

class tagHelper extends htmlHelper
{
  var $headOpen = false;
  var $headings = array();
  var $oddColor = '#FFFFFF';
  var $evenColor = '#FFFFFF';
  var $enableActions = false;
  var $pointerColor = '#f0dc9f';
    var $alertTypes = array('alert-success', 'alert-info', 'alert-warning', 'alert-danger');
  var $params = null;
  var $headingClass = ".tableHeading";
  var $bgHeaderColor = 'black';

  public $helpers = array('Html');


  public function makeAttributes($attributes = null)
  {

    $out = "";
    if (!is_null($attributes))
    {

      foreach ($attributes as $key=>$value)
      {
        if (!is_null($value) && ($value != ''))
        {
        $out .= ' '. $key . '="' . $value .'"';
        }
      }
    }
    return $out;
  }

  public function randomString($n = 10)
  {
        $s = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $n; $i++) {
            $randstring .= substr($s, rand(0, strlen($s)), 1);
        }
        return $randstring;
    }

  public function open($what = '', $options = array())
  {

    $this->headOpen = $what == 'thead' ? true : false;
    $out = '<'.$what.' ' ;
    $out .= $this->makeAttributes($options).'>';
    return $out;
  }

  public function cell($value, $options=array())
  {
    $out = '';
    if (isset($options['show']) && (!$options['show']))
    {
      return $out;
    }
    $out = $this->headOpen ? '<th ' : '<td ';

    $out .= $this->makeAttributes($options).'>';
    $out .= $value . ' ';
    $out .= $this->headOpen ? '</th> ' : '</td>';

    return $out;
  }

  public function headings($hs = array(), $headingRowOptions = array())
  {

    $out = '<tr ' . $this->makeAttributes($headingRowOptions) . ' >';
    foreach ($hs as $h=>$options)
    {
      $value = $h;
      if (isset($options['show']) && (!$options['show']))
      {
        continue;
      }
      if (isset($options['value']))
      {
        if (isset($options['type']))
        {
          switch ($options['type']) {
            case 'icon':
              $value = $this->image(LOCATION_ICONS.$options['value'], array('height'=>20, 'width'=>20));

              break;
            case 'link':
              //if ($this->params['uri'] != $options['value'])
              {
                $value = $this->link($value, $options['value']);
              }
            break;
            default:
              debug($options['value']);
              break;
          }
        } else
        {
          $value = $options['value'];
        }
        unset($options['value']);
      }
      $out .= $this->cell($value, $options);
    }
    $out .= '</tr>';
    return $out;
  }
  // row - returns a formatted row of an html table
  public function row($rowData, $fieldOptions = array(), $rowOptions = array())
  {
    $out = '<tr '.$this->makeAttributes($rowOptions). ' >';
    foreach ($fieldOptions as $f=>$option)
    {
      //if show option is set to false then skip the loop
      if (isset($option['show']) && (!$option['show']))
      {
        continue;
      }
      $out .= '<td '. $this->makeAttributes($option).' >'.$rowData[$f].'</td>';
    }
    $out .= '</tr>';
    return $out;
  }
  public function close($what)
  {
    if ($what == 'thead')
    {
      $this->headOpen = false;
    }

    return '</'.$what.'>';
  }

  public function getDateNavigation($fromDate = '', $toDate = '',$delta, $typeID = DEFAULT_ACTIVITY, $navLabel = "")

  {

    $navDates = array();
      $navDates['previousFromDate'] = date (DATE_YMD, strtotime($fromDate." -1 ".$delta));
      $navDates['previousToDate'] = date (DATE_YMD, strtotime($toDate." -1 ".$delta));
      $navDates['nextFromDate'] = date (DATE_YMD, strtotime($fromDate." +1 ".$delta));
      $navDates['nextToDate'] = date (DATE_YMD, strtotime($toDate." +1 ".$delta));
    $u =DS.$this->params['controller'].DS.$this->params['action'];
      $leftNavImg = $this->image(LOCATION_ICONS.ICON_PREVIOUS);
      $rightNavImg = $this->image(LOCATION_ICONS.ICON_NEXT);
    $out = $this->link($leftNavImg.'&nbsp;&nbsp;', $u.DS.$navDates['previousFromDate'].DS.$navDates['previousToDate'].DS.$typeID, array('escape'=>false));
    $out .= $navLabel;
    $out .= $this->link('&nbsp;&nbsp;'.$rightNavImg, $u.DS.$navDates['nextFromDate'].DS.$navDates['nextToDate'].DS.$typeID, array('escape'=>false));
    return $out;
  }

  public function table($options)
  {
    return $this->open('table', $options);
  }
  public function thead($options)
  {
    return $this->open('thead', $options);
  }
   public function tbody($options)
  {
    return $this->open('tbody', $options);
  }
  public function select( $name = 'select', $values = array(),$options = array(), $selected = null)
  {
    $options['name'] = $name;
    $out = $this->open('select', $options);
    foreach($values as $value=>$label)
    {
      $selectedAttribute = $selected == $value ? 'selected' : '';
      $out .= '<option value="'.$value.'" '.$selectedAttribute.' >'.$label.'</option>';
    }
    $out .= $this->close('select');
    return $out;
  }
  public function input($name = 'name', $type = 'text', $value = '', $options = array())
  {
    if ($type != 'textarea')
    {
      $options['id'] = isset($options['id']) ? $options['id'] : $name;
      $options['name'] = $name;
      $options['type'] = $type;
      $options['value'] = $value;

      $out = $this->open('input', $options);
    } else
    {
        $options['id'] = isset($options['id']) ? $options['id'] : $name;
        $options['name'] = $name;
        $options['cols'] = 80;
        $options['rows'] = 10;


       $out = $this->open('textarea', $options);
       $out .= $value;
       $out .= '</textarea>';
    }

    return $out;
  }
  public function form($id = 'form', $action = '/', $method = 'GET',  $options = array())
  {
    $options['action'] = $action;
    $options['id'] = $id;
        $options['method'] = $method;
    $out = $this->open('form', $options);
    return $out;
  }
  public function popAlert()
  {
    if (count($this->alerts) > 0)
    {
      $temp = array_pop($this->alerts);

      $out = '<div class="alert '.$this->alertTypes[$temp['type']].' alert-dismissible fade show" role="alert">';
      $out .='<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>';
      $out .= $temp['message'];
      $out .= '</div>';
      echo $out;
      return count($this->alerts);
    }
    return false;
  }
  public function recordListHeading($headings, $tableStyle)
  {
      $out = $this->open('table',array('class'=>$tableStyle));
      $out .= $this->open('thead',array('class'=>'thead-dark'));
      $out .= $this->headings($headings, array('style'=>'thead-dark'));
      $out .= $this->close('thead');

      return $out;
  }
  public function recordList($records, $fieldOptions, $headings, $tableStyle, $rowAttributes = null)
  {

    $out = $this->recordListHeading($headings, $tableStyle);
    $out .= $this->open('tbody');
    foreach($records as $index=>$row)
    {
      $out .= $this->row($row, $fieldOptions, $rowAttributes);
    }

    $out .= $this->close('tbody');
    $out .= $this->close('table');
    return $out;
  }

  public function inputGroup($label = '', $options = null)
  {
   $out = (($label != '') ? '<label for="anything">'.$label.'</label>': "");
   $out .= '<div class="input-group mb-3">'."\n";
   foreach ($options as $option)
   {
      if (isset($option['preText'])  && ($option['preText'] != ''))
      {
        $out .= '<div class="input-group-prepend">'."\n";
        $out .= '<span class="input-group-text">'.$option['preText'].'</span>'."\n";
        $out .= '</div>'."\n";
      }

      //input string formation
      $inputString ='<input class="form-control" ';
      $inputString .= isset($option['name']) ? 'name="'.$option['name'].'" ' : 'name="name" ';
      $inputString .= isset($option['value']) ? 'value="'.$option['value'].'" ' : 'value="name" ';
      $tempID = 'id'.rand(1,1000);
      $inputString .= isset($option['id']) ? 'id="'.$option['id'].'" ' : 'id="'.$tempID.'" ';

      $inputString .= isset($option['type']) ?  'type="'.$option['type'].'" ' : 'type="text" ';


      $inputString .= isset($option['aria-describedby']) && ($option['aria-describedby']!= '')  ?  'aria-describedby="'.$option['aria-describedby'].'" ' : 'aria-describedby="basic-addon3" ';
      $inputString .= isset($option['aria-label']) && ($option['aria-label']!= '')  ?  'aria-describedby="'.$option['aria-describedby'].'" ' : 'aria-describedby="basic-addon3" ';
      foreach($option as $key=>$value)
      {
        switch ($key)
        {
          case 'type':
          case 'id':
          case 'name':
          case 'aria-describedby':
          case 'aria-label':
          case 'preText':
          case 'value':
          break;
          default:
            $inputString .= $value != '' ? $key . '="'. $value . '" ' : '';
          break;
        }

      }
      $inputString .= ">\n";
      $out .= $inputString;
    }
    $out .= "</div>\n";
   return $out;
  }
}
?>
