<h2>Welcome</h2>

<p>You are logged in as <b><?php echo $this->user->cols['email']; ?></b>!</p>

<p>
<?php
if ($this->user->cols['send_text']) {
	echo "You have signed up for text notfications. ";	
} else {
	echo "You have <b>not</b> signed up for text notfications. ";
}
?>
	Set your <a class='btn btn-primary' href="<?php echo base_url('/users/text_preferences'); ?>">text preferences</a>.</p>
	
<p>You can change your email or log out at your <a href="<?php echo  base_url('/users/profile'); ?>">profile page</a>.</p>
	