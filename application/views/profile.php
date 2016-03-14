<div class='row'><div class='col-md-12'>
<h2>Your Profile Page</h2>

<p>Hello! You are currently logged in as '<b><?php echo $this->user->cols['email']; ?></b>'. If you wish, you can <a class='btn btn-primary' href="<?php echo base_url('/users/logout'); ?>">log out</a> or <a class='btn btn-primary' href="<?php echo base_url('/workshops'); ?>">go back to the main page</a>.</p>


</div></div>
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


<h3>Change Your Email Address</h3>
<p>If you have a new email, enter it below. We will send a link to your new email. Click that link and we'll reset your account to use that email.</p>
<?php
echo $this->form_builder->open_form(array('action' => 'users/change_email'));
echo $this->form_builder->build_form_horizontal(
        array(
                array(/* INPUT */
                        'id' => 'new_email',
                        'placeholder' => 'new email address',
						'value' => set_value('new_email'),
						
                ),
                array(/* SUBMIT */
                        'id' => 'submit',
                        'type' => 'submit',
						'label' => 'Change Email'
                )
        ),
		array('new_email' => set_value('new_email'))
	);
echo $this->form_builder->close_form();

?>

</div></div>
<div class='row'><div class='col-md-12'>

<h3>Reset Your Link</h3>
<p>For the paranoid: This will log you out, generate a new key, and a send a link to your email. If you don't even understand this then don't worry about it. <a class='btn btn-primary' href="<?php echo base_url('/users/reset'); ?>">Reset My Login Link</a></p>

</div></div>
<div class='row'><div class='col-md-12'>

<h3>Never Mind</h3>
<p>Just <a class='btn btn-primary' href="<?php echo base_url('/workshops'); ?>">go back to the main page</a>.</p>
</div></div>