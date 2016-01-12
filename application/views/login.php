<h2>Log In To This Site</h2>
<p>To sign up for a workshop, you must log in. You don't need a password or a Facebook account but you do need an email account. This is separate from the mailing list.</p>

<p>Submit your email with this form and we will email you a link to log in:</p>

<?php

echo $this->form_builder->open_form(array('action' => 'users/index'));
echo $this->form_builder->build_form_horizontal(
        array(
                array(/* INPUT */
                        'id' => 'email',
                        'placeholder' => 'Email',
						'value' => set_value('email')
                ),
                array(/* SUBMIT */
                        'id' => 'submit',
                        'type' => 'submit',
						'label' => 'Send Link'
                )
        ));
echo $this->form_builder->close_form();
	
?>