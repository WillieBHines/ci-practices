<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<h2><a href='".base_url('/workshops/edit/'.$wk['id'])."'>{$wk['showtitle']}</a></h2>\n";
	echo  "</div></div> <!-- end of col and row -->\n";

	echo  "<div class='row'>\n";
	echo "<div class='col-md-6'>\n";

	echo "<h3>Registations</h3>\n";
	echo "<ul>\n";
	foreach ($statuses as $sid => $sname) {
		echo "<h3>$sname ({$wk[$sname]})</h3>\n<ul>";
		foreach ($regs as $reg) {
			if ($reg['status_id'] != $sid) { continue; }
			
			echo "<li><a href='".base_url('/users/edit/'.$reg['user_id'])."'>{$reg['email']}</a> <small>".date('D M j Y g:ia', strtotime($reg['last_modified']))."</small> - <a href='".base_url('/registrations/edit/'.$reg['id'])."'>change</a></li>\n";
		}
		echo "</ul>\n";

	}
	echo "</ul>\n";
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
	
	echo "</div>\n";

	echo  "</div> <!-- end of row -->\n";

?>
	