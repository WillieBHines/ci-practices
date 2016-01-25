<div class='row'><div class='col-md-12'>

<h2>Text Notifications</h2>
<p>If you want notifications via text, pick "yes" for the first option, then set your network and phone number.</p>
<?php

echo $this->form_builder->open_form(array('action' => 'users/text_preferences'));

echo $this->form_builder->build_form_horizontal(
        array(

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
                      'options' => $this->carrier->carriers_drop
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
						'label' => 'Set Text Preferences'
                )
        ),
		array(
			'send_text' => $this->user->cols['send_text'], 
			'phone' => $this->user->cols['phone'], 
			'carrier_id' => $this->user->cols['carrier_id'])
	);
echo $this->form_builder->close_form();
?>

</div></div>
<div class='row'><div class='col-md-12'>

<h3>Never Mind</h3>
<p>Just <a href="<?php echo base_url('/workshops'); ?>">go back to the main page</a>.</p>

</div></div>