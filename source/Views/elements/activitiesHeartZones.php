<?php
		//$record['hrzones'] = json_decode($record['hrzones'] );
		//->debug($record['hrzones']);
		$totalZoneTime = $record['z1time'] + $record['z2time'] + $record['z3time'] + $record['z4time'] + $record['z5time'];
		if ($totalZoneTime > 0)
		{
 ?>

		<table class="table">
      <thead class="table-dark">
        <tr>
          <th>Zone</th>
          <th class="text-center">Min</th>
          <th class="text-center">Max</th>
          <th class="text-center">Time</th>
          <th class="text-center">%</th>
        </tr>
      </thead>
			<tbody>
<?php
				for($i = 1; $i <= 5; $i++)
				{
					$min = 'z'.($i).'min';
					$max = 'z'.($i).'max';
					$tim = 'z'.($i).'time';
					echo "<tr>";
          echo "<td> Zone ".($i).'</td>';
					echo '<td class="text-center">'.$record[$min];

					echo "</td>";

					if ($record[$max] > 0)
					{
						echo '<td class="text-center">'.$record[$max]."</td>";
					} else {
						echo "<td>&nbsp;</td>";
					}
					if ($record[$tim] > 0)
					{
						echo'<td class="text-center">'.$this->stopwatch->elapsed($record[$tim])."</td>";
					} else
					{
						echo '<td class="text-center">'.$this->stopwatch->elapsed(0)."</td>";
					}
					echo '<td class="text-center">'.round(100*$record[$tim]/$totalZoneTime,1)."%</td>";

					echo "</tr>";
				}
				echo '<tr class="table-success"><td class="">TOTAL</td><td class="text-center">&nbsp;</td><td>&nbsp;</td><td class="text-center">&nbsp;</td><td class="text-center">100%</tr>'
?>

			</tbody>
		</table>
		<?php
	}

		 ?>
