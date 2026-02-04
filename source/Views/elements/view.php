<?php

if (is_null($book['finished'])  || ($book['finished'] == '0000-00-00'))
{
	$book['finished'] = '';
}
if (is_null($book['started'])  || ($book['started'] == '0000-00-00'))
{
	$book['started'] = '';
}


?>


<div class="row mb-3">
  <div class="col-1 mr-3 mb-1">
    <a href="<?=APP_NAME?>/books/edit/<?=$book['id']?>">
    	<img src="<?=$book['smallThumbnail']?>" class="" height="<?=$height?>"  width="<?=$width?>">
	  </a>
  </div>
  <div class="col-7 mb-1 float-start">
  	<h1><?=$book['title']?></h1>
  	<h3><?=$book['subtitle']?></h3>
  </div>
	<div class="col-3 mb-1 float-center">
		<div class="btn-group float-end"  role="group" aria-label="" >
		<?=$this->element('recordNav');?>
		</div>
 		<?= $book['smallThumbnail'] == '' ? '&nbsp;' : (str_contains($book['smallThumbnail'], 'google') > 0 ? "Y" : "");?>
 		
	 

	</div>
</div> 
<div class="row">
	<div class="col-7 mb-3">
		<?=$book['description']?>
	</div>
	<div class="col-1 mb-3">
		&nbsp;
	</div>
	<div class="col-4 mb-3">
			<div class="row">
	 		<table class="table table-borderless">
	 			<tbody>
	 				<tr>Shelf</td><td><?=$book['shelf']?></td>
	 				</tr>
	 				<tr>
	 					<td>Status</td><td><?=$book['status']?></td>
	 				</tr>
	 				<tr><td>Type</td><td><?=$book['type']?></td></tr>
	 				<tr><td>Finished</td><td><?=$book['finished']?></td></tr>	
	 				<tr><td>Started</td><td><?=$book['started']?></td></tr>
	 				<tr><td>Pages</td><td class="float-start"><?=$book['pageCount']?></td></tr>
					<tr><td>Publisher</td><td class="float-start"><?=$book['publisher']?></td></tr>
					<tr><td>Published</td><td class="float-start"><?=$book['publishedDate']?></td></tr>
	 					<?= isset($book['ISBN_13'])  && ($book['ISBN_13'] != "") ? "<tr><td>ISBN 13</td><td>".$book['ISBN_13']."</td></tr>" : ""?>
					<?= isset($book['ISBN_10'])  && ($book['ISBN_10'] != "") ? "<tr><td>ISBN 10</td><td>".$book['ISBN_10']."</td></tr>" : ""?>
					<?= isset($book['goodreads_id'])  && ($book['goodreads_id'] != "") ? "<tr><td>Goodreads ID</td><td>".$book['goodreads_id']."</td></tr>" : "<tr><td>Goodreads ID</td><td>&nbsp;</td></tr>"?>
					<?= isset($book['asin'])  && ($book['asin'] != "") ? "<tr><td>Asin</td><td>".$book['asin']."</td></tr>" : "<tr><td>Asin</td><td>&nbsp;</td></tr>"?>
	 		</table>
	 	</div>
	</div>
</div>
<div class="row mb-5">
	<div class="col-8">
		<h6>Categories</h6>
		<?=$book['categories']?>
	</div>
</div>	
<div class="row">
	<div class="col-9 text-end" >
		&nbsp;
	</div>
	
</div>

