<?php
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<h2>Add A Workshop</h2>\n";
	echo  "</div></div> <!-- end of col and row -->\n";

	echo  "<div class='row'>\n";
	echo "<div class='col-md-12'>\n";

	echo validation_errors('<div class="alert alert-danger">', '</div>');
	
	echo $this->form_builder->open_form(array('action' => 'workshops/add/'));
	echo $this->form_builder->build_form_horizontal(
	        array(

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
							'label' => 'Add'
	                )
	        ),
			$wk
		);
	echo $this->form_builder->close_form();
	
	echo  "</div> <!-- end of col -->\n";
	echo "</div>\n";

	
	


?>
