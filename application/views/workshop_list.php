<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo  "<h2>All Upcoming Workshops</h2>\n"; 

	echo "<table class='table table-striped'>\n";
	echo "<tr><th>Title</th><th>When</th><th>Where</th><th>Cost</th><th>Spots</th></tr>\n";
	foreach ($workshops as $row) {
		echo "<tr>
			<td>{$row['title']}</td>
			<td>{$row['friendly_when']}</td>
			<td>{$row['place']}</td>
			<td>{$row['cost']}</td>
			<td>".number_format($row['open'], 0)." of ".number_format($row['capacity'], 0).",<br> ".number_format($row['waiting']+$row['invited'])." waiting</td>
			</tr>\n";
			
	}
	echo "</table>\n";

	echo  "</div></div> <!-- end of col and row -->\n";

?>
	