<?php
  $status = isset($status) ? $status : "All";
  $foo = $this->auth->loggedIn();
  $this->set('loggedIn', $foo); 
   
?>
<div class="row">
  <h2>Library</h2>
</div>

  
    <form method="POST" action="/app/books/library" class="rounded p-3 mb-4" style="background:lightgrey;">
<div class="row mb-1">
      <?php 
        $select = array('All'=>'', 'To Read'=>'', 'Reading'=>'', 'Read' =>'' );
        $select[$status] = 'selected';
      ?>
      <div class="col-5">
        <input class="form-control" type="text" placeholder="" name="searchText" value="<?=$searchText?>">
      </div>
      <div class="col-2">
          <select name="status" id="status" class="form-select" aria-label="">
            <option <?=$select['All']?> value="All">All</option>
            <option <?=$select['To Read']?> value="To Read">To Read</option>
            <option <?=$select['Reading']?> value="Reading">Reading</option>
            <option  <?=$select['Read']?> value="Read">Read</option>
          </select>
      </div>
      <div class="col-2 float-center">
          <button type="submit" class="btn btn-primary">Submit</button>&nbsp;<?=$count?>&nbsp;/&nbsp;<?=$booksInLib?>  <h5><?=$pageNum+1?></h5>
  
      </div>
      
</div>
    </form>

<?=$this->element('booksList');?>