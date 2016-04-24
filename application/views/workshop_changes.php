<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<h3>Change Log for Upcoming Workshops</h3>";
	echo "<table class='table table-condensed'>";
	echo "<tr><th>Workshop (start date)</th><th>Email</th><th>To Status</th><th>When</th></tr>\n";
	if (!$changes || count($changes) == 0) {
		echo "<tr><td colspan='4'>None!</td></tr>\n";
	} else {
		foreach ($changes as $c) {
			
			$last_enrolled = '';
			$rowclass = '';
			if ($c['status_name'] == 'dropped' && $c['last_enrolled']) {
				$last_enrolled = ' /<br>'.date('D M j Y g:ia', strtotime($c['last_enrolled']))." ({$c['hours_before']})";
				// turn row red
				if ($c['hours_before'] < $late_hours) {
					$rowclass = 'danger';
				}
				
			}
			
			echo "<tr class='$rowclass'>
				<td>{$c['title']} <small>(".date('M j', strtotime($c['start'])).")</small></td>
				<td>{$c['email']}</td>
				<td>{$c['status_name']}</td>
				<td>".date('D M j Y g:ia', strtotime($c['happened']))."$last_enrolled</td>
				</tr>\n";
		}
	}
	echo "</table>\n";
	
	echo "</div></div>\n";
	
	
	


?>
