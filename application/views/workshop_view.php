<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<h2>{$row['title']}</h2>\n";

?>
	
	<table class='table'>
		<tr><td>When</td><td><?php echo $row['when']?></td></tr>
		<tr><td>Where</td><td><?php echo $row['place']?><br><small><?php echo $row['lwhere']?></small></td></tr>
		<tr><td>Capacity</td><td><?php echo $row['capacity']?></td></tr>
		<tr><td>Cost</td><td><?php echo $row['cost']?></td></tr>
		<tr><td>Enrolled / Waiting</td><td><?php echo $row['enrolled']?> / <?php echo $row['waiting']+$row['invited']?></td></tr>
		<tr><td>Your status</td><td><?php echo $row['action']; ?></td></tr>
	</table>
	<a class='btn btn-primary' href='<?php echo base_url('/workshops')?>'>back to the front</a>
