<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<h1><a href='".base_url('/users/edit/'.$user['id'])."'>{$user['email']}</a></h1>\n";
	echo "<p>Joined: ".date('D M j, Y - g:ia', strtotime($user['joined']))."</p>\n";
	
	$link = base_url('/users/login/'.$user['ukey']);
	echo "<p>Link to log in: <a href='$link'>$link</a></p>\n";

	echo validation_errors('<div class="alert alert-danger">', '</div>');
	
	echo $this->form_builder->open_form(array('action' => 'users/edit/'.$user['id']));
	echo $this->form_builder->build_form_horizontal(
	        array(

                array(/* HIDDEN */
                        'id' => 'id',
                        'type' => 'hidden',
                        'value' => $user['id']
                ),
	                array(/* INPUT */
	                        'id' => 'email',
							'value' => set_value('email')
						
	                ),
		          array(/* DROP DOWN */
	                      'id' => 'group_id',
						  'label' => 'Group',
	                      'type' => 'dropdown',
	                      'options' => $this->group->dropdown_array
	              ),					
		            array(/* DROP DOWN */
		                      'id' => 'send_text',
		  				  'label' => 'Send text notifications?',
		                      'type' => 'dropdown',
		                      'options' => array(1 => 'Yes', 0=> 'No')
		              ),
			          array(/* DROP DOWN */
		                      'id' => 'carrier_id',
							  'label' => 'Carrier',
		                      'type' => 'dropdown',
		                      'options' => $this->carrier->dropdown_array
		              ),			  
		                array(/* INPUT */
		                        'id' => 'phone',
								'label' => 'Phone Number',
								'value' => set_value('phone'),
								'help' => '10 digit phone number'
						
		                ),
	                array(/* SUBMIT */
	                        'id' => 'submit',
	                        'type' => 'submit',
							'label' => 'Update'
	                )
	        ),
			$user
		);
	echo $this->form_builder->close_form();

	echo "<h3>Workshops</h3>\n";
	echo "<table class='table table-striped table-condensed'><tbody>\n";
		foreach ($workshops as $wk) {
			echo "<tr><td><a href='".base_url('/workshops/edit/'.$wk['id'])."'>{$wk['title']}</a></td><td>{$wk['friendly_when']}</td><td>{$wk['place']}</td><td>{$wk['status_name']}";
			if ($wk['status_name'] == 'waiting') {
				echo " (spot {$wk['rank']})";
			}
			echo "</td></tr>\n";
		}
	echo "</tbody></table>\n";

	echo  "</div></div> <!-- end of col and row -->\n";

?>
	