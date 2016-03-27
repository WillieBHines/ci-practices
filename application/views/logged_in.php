<h2>Welcome</h2>

<p>You are logged in as <b><?php echo $this->user->cols['email']; ?></b>! <?php
	if ($this->user->cols['send_text']) {
		echo "You <b>have</b> signed up for text notfications. ";	
	} else {
		echo "You have <b>not</b> signed up for text notfications.";
	}
?></p>

<p>Go to your <a class='btn btn-primary' href="<?php echo  base_url('/users/profile'); ?>">profile page</a> to set your <b>texting preferences</b>, <b>change your email</b> or <b>logout</b>.</p>
	
		