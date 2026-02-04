
<div class="row">
  <?=$this->tag->open('table',array('class'=>'table table-bordered table-hover'))?>

  <?=$this->tag->open('tbody')?>
<?php
	foreach($fields as $index=>$field)
	{
?>
    <?=$this->tag->open('tr')?>
    <?=$this->tag->open('td')?>
    <?=$field?>
    <?=$this->tag->close('tr')?>
    <?=$this->tag->close('td')?>
<?    
	}
?>
  <?=$this->tag->close('tbody')?>

<?=$this->tag->close('table')?>
</div>	

   
