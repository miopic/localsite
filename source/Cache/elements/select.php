<?php
	$selectName = isset($selectName) ? $selectName : 'name';
	$selectedID = isset($selectedID) ? $selectedID : 0;
	$width = isset($width) ? $width : '175px';
	if (isset($elements)  && (count($elements) > 0))
	{
?>
		<select class="form-control" style="float:left;width:<?=$width?>" name="<?=$selectName?>">
<?php

			if (isset($elements))
			{
				foreach($elements as $key=>$val)
				{
					$selected = "";
					if ($key == $typeID)
					{
						$selected = " selected";
					}
?>
					  <option <?=$selected?> value="<?=$key?>"><?=$val?></option>
<?php
				}
			}
?>
</select>&nbsp;<button type="submit" class="btn btn-primary" style="float:left">submit</button>&nbsp;&nbsp;

<?php
	}
?>
