<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<h2>{$wk['showtitle']}</h2>\n";

	echo "<h3>Registations</h3>\n";
	echo "<ul>\n";
	foreach ($regs as $reg) {
		echo "<li>{$reg['email']} ({$reg['status_name']} - ".date('D M j Y g:ia', strtotime($reg['last_modified'])).")</li>\n";
	}
	echo "</ul>\n";

	echo  "</div></div> <!-- end of col and row -->\n";

?>
	