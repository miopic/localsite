
<div class="container">
	<div class="row">
	
	</div>
	<div class="row">
		<table class="table table-striped table-hover">
			<thead class="table-success">
				<tr style="background-color: honeydew;"><th width="10%" style="text-align: center">date</th><th>sunrise</th><th>sunset</th><th>Daylength</th></tr>
			</thead>
			<tbody>
	<?php

		if (isset($sun) && (!is_null($sun)))
		{
			foreach($sun as $key=>$sunRowData)
			{
				$dayLength = round(($sunRowData['sunset'] - $sunRowData['sunrise'])/ HOUR, 2);
				$hour = floor($dayLength);
				$minutes = number_format(($dayLength - $hour)*MINUTES, 2);
				$backcolor='';
				if (date('Y-m-d') == $key)
				{ $backcolor = ' style="background-color:yellow"';}
				echo '<tr'.$backcolor.'>';
				echo '<td align="center"  style="background-color: honeydew">'.date('d',strtotime($key)).'</td>';
				echo '<td>'.date('H:i:s', $sunRowData['sunrise']).'</td>';
				echo '<td>'.date('H:i:s', $sunRowData['sunset']).'</td>';
				echo '<td>'.$hour.':'.$minutes.'</td>';
				echo '</tr>';
			}
		}

	?>
			</tbody>
		</table>
	</div>
</div>
