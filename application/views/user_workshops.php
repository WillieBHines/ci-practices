<h2>Previous Workshops You've Taken</h2>

<?php

	echo "<table class='table table-striped table-condensed'><tbody>\n";
		$total = 0;
		foreach ($this->user->workshops as $wk) {

			if (strtotime($wk['start']) >=  strtotime("now")) {
				continue;
			}
			$total++;
			echo "<tr><td><a href='".base_url('/workshops/view/'.$wk['id'])."'>{$wk['title']}</a></td><td>{$wk['when']}</td><td>{$wk['place']}</td><td>{$wk['status_name']}";
			if ($wk['status_name'] == 'waiting') {
				echo " (spot {$wk['rank']})";
			}
			echo "</td></tr>\n";

		}
		if (!$total) {
			echo "<tr><td>No past registrations.</td></tr>\n";
		}
	echo "</tbody></table>\n";

?>
