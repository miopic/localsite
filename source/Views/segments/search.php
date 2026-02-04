<?php
  $searchText = isset($searchText) ? $searchText : "";
?>

<div class="row mb-4">
  <form action="https://dwm.io<?=APP_NAME?>/segments/search" id="searchForm" method="POST">        <div class="row" style="width:100%">
  	<div class="row mb-4">

  			<div class="col-3">
  	    	<?=$this->tag->input('searchText', 'text', $searchText, array('class'=>'form-control'));?>
  			</div>
  			<div class="col-2">
      		<button type="submit" class="btn btn-primary">Submit</button>&nbsp;&nbsp;
  			</div>
  			<div class="col-1">
  				&nbsp;
  		</div>
  	</div>
  </form>
</div>
<div class="row">
  <table class="table">
    <table class="table table-striped table-hover">
      <thead class="">
        <tr style="border-top:2px solid black; border-bottom:2px solid black">
          <th class="text-center">Activity</th>
          <th  class="text-left">Name</th>
          <th  class="text-center">Avg Grade</th>
          <th  class="text-center">Max Grade</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($records as $id=>$record)
        {
        ?>
          <tr style="font-weight:normal; border-bottom:1px solid black; border-top:1px solid black">
            <td class="text-center"><?=isset($types[$record['activitytype_id']]['image']) ? $this->html->image(LOCATION_TYPEIMAGES.$types[$record['activitytype_id']]['image'],array('height'=>'20', 'width'=>'20')) : '&nbsp;'?></td>
            <td class="text-left"><?=$this->html->link($record['name'], APP_NAME.'/segments/view/'.$record['id'])?></td>
            <td class="text-center"><?=$record['average_grade']?>%</td>
            <td class="text-center"><?=$record['maximum_grade']?>%</td>
          </tr>
        <?php
        }
        ?>
      </tbody>
  </table>
</div>
<?php

 ?>
