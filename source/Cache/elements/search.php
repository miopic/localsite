<?
	$buttonText = isset($buttonText) ? $buttonText : 'Search';
	$formAction = isset($formAction) ? $formAction : '';
	$enableLeadingColumn = isset($enableLeadingColumn) ? $enableLeadingColumn : true;
	$searchColumnWidth = isset($searchColumnWidth) ? $searchColumnWidth : '8';
	if ($enableLeadingColumn)
	{
?>
		<div class="col-sm-1">
		  &nbsp;
		</div>
<?
	}
?>
<div class="col-sm-<?=$searchColumnWidth?>">
  <?=$this->tag->form('searchForm', $formAction, 'POST');?>

     <?=$this->tag->input('searchText', 'text', $searchText, array('class'=>'form-control float-left','style'=>'width:85%' ));
     ?>
    <button type="submit" class="btn btn-primary float-right"><?=$buttonText?></button>
  </form>
</div>

