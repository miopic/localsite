
<div class="row mb-4">
  <div class="col-10">
    <form method="POST" action="/app/links/search" class="rounded p-4" style="background:lightgrey;">
      <div class="form-group">

        <input class="form-control" type="text" placeholder="" name="search" value="<?=$textSearch?>">
      </div>
    </form>
  </div>

<div class="row mt-4">
<?php
  $this->set('recordList', $records);

?>
<?php
  echo $this->element('activitiesRecords');?>

</div>
