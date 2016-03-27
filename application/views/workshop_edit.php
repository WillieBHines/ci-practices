<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<h2><a href='".base_url('/workshops/edit/'.$wk['id'])."'>{$wk['showtitle']}</a></h2>\n";
	echo  "</div></div> <!-- end of col and row -->\n";

	echo  "<div class='row'>\n";
	echo "<div class='col-md-6'>\n";

	echo "<h3>Registations</h3>\n";
	foreach ($statuses as $sid => $sname) {
		echo "<h3>$sname ({$wk[$sname]})</h3>\n";
		foreach ($regs as $reg) {
			if ($reg['status_id'] != $sid) { continue; }
			
			echo "<div class='row'><div class='col-md-6'><a href='".base_url('/users/edit/'.$reg['user_id'])."'>{$reg['email']}</a><br><small>".date('D M j Y g:ia', strtotime($reg['last_modified']))."</small></div>
				<div class='col-md-6'>
					<a class='btn btn-primary btn-xs' href='".base_url('/registrations/edit/'.$reg['id'])."'>change</a>
					<!--<a class='btn btn-danger btn-xs' href='".base_url('/registrations/delete/'.$reg['id'])."'>remove</a>-->
				</div></div>\n";
		}

	}
	echo "</div>\n";
	
	echo "<div class='col-md-6'>\n";

	echo validation_errors('<div class="alert alert-danger">', '</div>');
	
	echo $this->form_builder->open_form(array('action' => 'workshops/edit/'.$wk['id']));
	echo $this->form_builder->build_form_horizontal(
	        array(

                array(/* HIDDEN */
                        'id' => 'id',
                        'type' => 'hidden',
                        'value' => $wk['id']
                ),
	                array(/* INPUT */
	                        'id' => 'title',
							'value' => set_value('title')
						
	                ),
	                array(/* INPUT */
	                        'id' => 'notes',
							'type' => 'textarea',
							'value' => strip_tags(set_value('notes'))
						
	                ),
					  array(/* DROP DOWN */
	                        'id' => 'location_id',
	  					 	 'label' => 'Where',
	                        'type' => 'dropdown',
	                        'options' => $this->location->dropdown_array
	                ),			  
					
	                array(/* INPUT */
	                        'id' => 'start',
							'value' => set_value('start')
						
	                ),
	                array(/* INPUT */
	                        'id' => 'end',
							'value' => set_value('end')
						
	                ),
	                array(/* INPUT */
	                        'id' => 'capacity',
							'value' => set_value('capacity')
						
	                ),
	                array(/* INPUT */
	                        'id' => 'cost',
							'value' => set_value('cost')
						
	                ),
	                array(/* INPUT */
	                        'id' => 'when_public',
							'value' => set_value('when_public')
						
	                ),
	                array(/* SUBMIT */
	                        'id' => 'submit',
	                        'type' => 'submit',
							'label' => 'Update'
	                )
	        ),
			$wk
		);
	echo $this->form_builder->close_form();
	
	echo  "</div> <!-- end of col -->\n";
	echo "</div> <!-- end of row -->\n";

	// change log
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<h3>Change Log</h3>";
	echo "</div></div>\n";
	
	if (isset($changes) && count($changes) > 0) {
		foreach ($changes as $c) {
			echo "<div class='row'>
				<!--<div class='col-sm-3'>{$c['title']} ({$c['start']})</div>-->

				<div class='col-sm-3'>{$c['email']}</div>
				<div class='col-sm-1'>{$c['status_name']}</div>
				<div class='col-sm-3'>".date('D M j Y g:ia', strtotime($c['happened']))."</div>
				</div>\n";
		}
	} else {
		echo "<div class='row'><div class='col-sm-7'>No changes!</div></div>\n";
	}
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<br><p><a class='btn btn-danger' href='".base_url("/workshops/delete/{$wk['id']}")."'>Delete this workshop!</a></p>";
	echo "</div></div>\n";
	
	
	


?>
