<?php
echo "<h4>From ".$fromDate." to ".$toDate."</h4>";
echo "<h4>Records in Date Range: ".count($records)."</h4>";
echo "<h4>Results from Strava: ".count($results)." calls: ".$calls."</h4>";
echo '<div class="row"><table width="50%"><tr><th>ID</th><th>STRAVA_ID</th><th>DATE</th><th>NEW</th><th>OLD</th></tr>';
foreach ($results as $i => $result)
{
	echo $result['message'];
}
echo "</table></div>";
?>