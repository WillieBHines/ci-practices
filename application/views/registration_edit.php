<?php
	
	echo  "<div class='row'><div class='col-md-12'>\n";
	echo "<h1><a href='".base_url('/users/edit/'.$reg['user_id'])."'>{$reg['email']}</a> in <a href='".base_url("/workshops/edit/".$reg['workshop_id'])."'>{$reg['title']}</a></h1>\n";
	
	echo validation_errors('<div class="alert alert-danger">', '</div>');
	
	echo $this->form_builder->open_form(array('action' => 'registrations/edit/'.$reg['id']));
	echo $this->form_builder->build_form_horizontal(
	        array(

                array(/* HIDDEN */
                        'id' => 'id',
                        'type' => 'hidden',
                        'value' => $reg['id']
                ),
		          array(/* DROP DOWN */
	                      'id' => 'status_id',
						  'label' => 'Status',
	                      'type' => 'dropdown',
	                      'options' => $statuses
	              ),					
		            array(/* DROP DOWN */
		                      'id' => 'confirm',
		  				  'label' => 'Send confirmation email?',
		                      'type' => 'dropdown',
		                      'options' => array(1 => 'Yes', 0=> 'No')
		              ),
  	                array(/* INPUT */
  	                        'id' => 'last_modified',
  							'value' => set_value('last_modified')
						
  	                ),
	                array(/* SUBMIT */
	                        'id' => 'submit',
	                        'type' => 'submit',
							'label' => 'Update'
	                )
	        ),
			$reg
		);
	echo $this->form_builder->close_form();

	echo "<p><a  class='btn btn-danger'  href='".base_url('/registrations/delete/'.$reg['id'])."'>Remove</a> this registration or <a  class='btn btn-info' href='".base_url("/workshops/edit/".$reg['workshop_id'])."'>return to workshop page</a>.</p>\n";
	echo  "</div></div> <!-- end of col and row -->\n";

?>
	