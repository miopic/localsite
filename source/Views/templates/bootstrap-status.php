<?php
   $showStatus = isset($showStatus) ? $showStatus : true;
   if ($showStatus)
   {
?>
<header class="border-bottom mb-2 py-2" style="background-color: #f5f5f5;color:darkgrey;font-size:15px">
  <div class="container-fluid" style="">
    <div class="row">
      <div class="col-sm-5" style="padding-left:20px">
        <?= isset($statusMessage) ? $statusMessage : DEFAULT_STATUSMESSAGE ?>
      </div>

      <div class="col-sm-1">
        <?=isset($this->status[0]) ? $this->status[0] : '' ?>
      </div>
      <div class="col-sm-1">
          <?=isset($this->status[1]) ? $this->status[1] : ''?>
      </div>
      <div class="col-sm-1">
        <?=isset($this->status[2]) ? $this->status[2] : ''?>
      </div>
      <div class="col-sm-1">
        <?=isset($this->status[3]) ? $this->status[3] : ''?>
      </div>
      <div class="col-sm-1">
          <span id="day" class="text-justify-center" style="display:block"><a href="<?=APP_NAME?>/users/changeunits/" style="color:darkgrey; text-decoration:none"><?=$measurementUnits?></a></span>
      </div>
      <div class="col-sm-2">
       
      
      </div>
    </div>
  </div>
</header>
<?php

  }
 ?> 