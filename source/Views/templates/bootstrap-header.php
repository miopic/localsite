<?php
    $showHeader = isset($showHeader) ? $showHeader : true;
    if ($showHeader)
    {
      $headerMenu = "";
      switch ($this->controller)
      {
       
        default:
          $headerMenu = 'menuActivities';
        break;

      }
?>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between pt-2 pb-2 mb-3 mt-2 border-bottom" style="padding-top:8px">
  <div class="container d-flex flex-wrap">
    <?=$this->element($headerMenu)?>
   
  </div>
</header>
<?php
}
?>