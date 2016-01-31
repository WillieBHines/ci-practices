<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<h2>{$wk['showtitle']}</h2>\n";
	echo "<h4>{$wk['enrolled']} / {$wk['waiting']} / {$wk['dropped']} /  {$wk['invited']}</h4>\n";

	echo "
<ul>
	<li>Title: {$wk['title']}</li>
	<li>When: {$wk['when']}</li>
	<li>Place: {$wk['place']}</li>
	<li>Capacity: {$wk['capacity']}</li>
	<li>Cost: {$wk['cost']}</li>
</ul>";


	echo "<h3>Registations</h3>\n";
	echo "<ul>\n";
	foreach ($statuses as $sid => $sname) {
		echo "<h3>$sname ({$wk[$sname]})</h3>\n<ul>";
		foreach ($regs as $reg) {
			if ($reg['status_id'] != $sid) { continue; }
			
			echo "<li><a href='".base_url('/users/edit/'.$reg['user_id'])."'>{$reg['email']}</a> <small>".date('D M j Y g:ia', strtotime($reg['last_modified']))."</small></li>\n";
		}
		echo "</ul>\n";

	}
	echo "</ul>\n";

	echo  "</div></div> <!-- end of col and row -->\n";

?>
	