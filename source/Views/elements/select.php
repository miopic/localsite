<?php
	$selectName = isset($selectName) ? $selectName : 'name';
	$selectedID = isset($selectedID) ? $selectedID : 0;
	$selectWidth = isset($selectWidth) ? $selectWidth : '175px';
	$selectID = isset($selectID) ? $selectID : 'id';
	if (isset($elements)  && (count($elements) > 0))
	{
?>
		<select class="form-control"  name="<?=$selectName?>" id="<?=$selectID?>">
<?php
			if (isset($elements))
			{
				foreach($elements as $key=>$val)
				{
					$selected = "";
					if (($key == $selectedItem)  || ($val == $selectedItem))
					{
						$selected = " selected";
					}
?>
					  <option <?=$selected?> value="<?=$key?>"><?=$val?></option>
<?php
				}
			}
?>
</select>

<?php
	}
?>
