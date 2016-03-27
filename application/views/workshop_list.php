<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	if ($admin) {
		echo "<h2>All Workshops</h2>\n";
	} else {
		echo  "<h2>All Upcoming Workshops</h2>\n"; 
	}

	echo "<table class='table table-striped'>\n";
	echo "<tr><th>Title</th><th>When</th><th>Where</th><th>Cost</th><th>Spots</th>";
	if ($admin) { 
		echo "<th>Actions</th>";
	} else {
		echo "<th>Your Status</th>"; 
	}
	echo "</tr>\n";
	foreach ($workshops as $row) {

		if (!$admin && $row['type'] == 'past') { continue; }
		if (!$admin && strtotime($row['when_public']) > time()) {
			continue;
		}
		
		$public = '';
		if ($admin && $row['when_public']) {
			$public = "<br><small>Public: ".date('D M j - g:ia', strtotime($row['when_public']))."</small>\n";
		}	
		
		
		// row color
		$class = '';
		if (date('z', strtotime($row['start'])) == date('z'))  {
			$class = 'info';
		} elseif ($row['type'] == 'soldout') {
			$class = 'warning';
		} elseif ($row['type'] == 'open') {
			$class = 'success';
		}
		
		if ($admin) { $row['action'] = ''; }
						
		echo "<tr class='$class'>
			<td><a href='".base_url('/workshops/'.($admin ? 'edit' : 'view').'/'.$row['id'])."'>{$row['title']}</a><br><small>{$row['notes']}</small></td>
			<td>{$row['when']}{$public}</td>
			<td>{$row['place']}<br><small>{$row['lwhere']}</small></td>
			<td>{$row['cost']}</td>
			<td>".number_format($row['open'], 0)." of ".number_format($row['capacity'], 0).",<br> ".number_format($row['waiting']+$row['invited'])." waiting</td>";
			if ($admin) { 
				echo "<td><a class='btn btn-primary' href='".base_url('/workshops/add/'.$row['id'])."'>Clone</a></td>"; 
			} else {
				echo "<td>{$row['action']}</td>"; 
			}
			echo "</tr>\n";
			
	}
	echo "</table>\n";

	echo  "</div></div> <!-- end of col and row -->\n";

?>
	