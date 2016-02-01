<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	if ($admin) {
		echo "<h2>All Workshops</h2>\n";
	} else {
		echo  "<h2>All Upcoming Workshops</h2>\n"; 
	}

	echo "<table class='table table-striped'>\n";
	echo "<tr><th>Title</th><th>When</th><th>Where</th><th>Cost</th><th>Spots</th></tr>\n";
	foreach ($workshops as $row) {


		if ($row['type'] == 'past' && !$admin) { continue; }
		if (strtotime($row['when_public']) > time() && !$admin) {
			continue;
		}
		
		$public = '';
		if ($admin && $row['when_public']) {
			$public = "<br><small>Public: ".date('D M j - g:ia', strtotime($row['when_public']))."</small>\n";
		}	
		
		
		$class = '';
		if ($row['type'] == 'soldout') {
			$class = 'warning';
		} elseif ($row['type'] == 'open') {
			$class = 'success';
		}
		
		echo "<tr class='$class'>
			<td><a href='".base_url('/workshops/edit/'.$row['id'])."'>{$row['title']}</a><br><small>{$row['notes']}</small></td>
			<td>{$row['friendly_when']}{$public}</td>
			<td>{$row['place']}</td>
			<td>{$row['cost']}</td>
			<td>".number_format($row['open'], 0)." of ".number_format($row['capacity'], 0).",<br> ".number_format($row['waiting']+$row['invited'])." waiting</td>
			</tr>\n";
			
	}
	echo "</table>\n";

	echo  "</div></div> <!-- end of col and row -->\n";

?>
	