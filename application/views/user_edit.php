<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<h1>{$user['email']}</h1>\n";

	echo "<h3>Workshops</h3>\n";
	echo "<table class='table table-striped table-condensed'><tbody>\n";
		foreach ($workshops as $wk) {
			echo "<tr><td><a href='".base_url('/workshops/edit/'.$wk['id'])."'>{$wk['title']}</a></td><td>{$wk['friendly_when']}</td><td>{$wk['place']}</td><td>{$wk['status_name']}</td></tr>\n";
		}
	echo "</tbody></table>\n";

	echo  "</div></div> <!-- end of col and row -->\n";

?>
	