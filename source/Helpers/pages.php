<?
class pagesHelper extends tagHelper
{
	var $chapter = 1;
	var $limit = 20;
	var $link = null;
	var $page = 1;
	var $pagesPerChapter = 10;
	var $totalPages = 1;
	var $totalRecords = null;

	public function variables()
	{
		return true;
	}

	public function initialize($params = null)
	{
		if (isset($params['named']['page']))
		{
			$this->page = $params['named']['page'];
		}
		if (isset($params['named']['limit']))
		{
			$this->limit = $params['named']['limit'];
		}
		$this->chapter = floor(($this->page-1) /$this->pagesPerChapter);
		$this->totalPages = round(($this->totalRecords / $this->limit)+.5);
	}
	public function pageMetrics($limit, $totalRecords)
	{

?>
<!--
		<div class="row rowSpacing20"  style="background-color: #f5f5f5; padding-top:5px; padding-bottom: 5px">
			<div class="col-sm-2">
				<span class="text-center"><?=($this->page-1)*$limit+1?> / <?=(($this->page-1)*$limit)+($limit)?> </span>

			</div>
			<div class="col-sm-2">
				<span class="text-center"><?=$this->page?> / <?=round(($totalRecords / $limit)+.5)?> </span>

			</div>
			<div class="col-sm-1">
				<span class="text-center"><?=$totalRecords?></span>
			</div>
		</div>
	-->
<?

	}
	public function pageNumbers()
	{

?>

		<ul class="pagination">
	    <li class="page-item">
<?
			$disabledPrev = $this->chapter < 1 ? ' disabled btn' : '';
			$displayPage = ($this->chapter * $this->pagesPerChapter);

			if ($this->chapter >= 1)
			{
?>
			      <a class="page-link <?=$disabledPrev?>" href="<?=$this->link?><?=DS?><?=$displayPage?>" aria-label="Previous">
			        <span aria-hidden="true">&laquo;</span>
			      </a>
	    </li>
<?
	}
		for($i = 0; $i <= ($this->pagesPerChapter-1); $i++)
		{
			$displayPage = ($this->chapter * $this->pagesPerChapter) + $i + 1;
			$isCurrentPage = $this->page == $displayPage ? ' style="font-weight:bold; font-color:green" ' : '';
			if ($this->totalPages >= $displayPage)
			{
?>
 				<li class="page-item" <?=$isCurrentPage?> ><a class="page-link" <?=$isCurrentPage?> href="<?=$this->link?><?=DS?><?=$displayPage?>"><?=$displayPage?></a></li>
<?
			}
		}
		$displayPage = ($this->chapter * $this->pagesPerChapter) + $i + 1;
		if ($displayPage <= $this->totalPages)
		{
?>

		    <li class="page-item">
		      <a class="page-link" href="<?=$this->link?><?=DS?><?=$displayPage?>" aria-label="Next">
		        <span aria-hidden="true">&raquo;</span>
		      </a>
		    </li>
<?
		}
?>
  </ul>
<?
	}
}
?>
