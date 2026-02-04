
<div class="row">
	<h4>USER</h4>

	<table class="table">
		<thead>
		<tr><th>Field</th><th>Value</th></tr>
	</thead>
<?php
		foreach ($this->user as $field=>$value)
		{

			echo "<tr>";
			switch ($field)
			{
				case 'expires_at':
					echo "<td>expires_at</td><td>".date("Y-m-d H:i:s", $value).", ".$value."</td>";
					break;

				case 'pw':
					break;
				
				default:
					echo "<td>".$field."</td><td>".$value."</td>";
			}
			echo "</tr>";
		}
?>
	</table>
</div>
