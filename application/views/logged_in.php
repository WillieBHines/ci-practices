<h2>Welcome</h2>

<p>You are logged in as <?php echo $this->user->cols['email']; ?>! (You can change your email, log out and other things at <a href="<?php echo  base_url('/users/profile'); ?>">your profile page</a>.</p>

<?php
//echo "cookie: ".get_cookie('ckey')."<br>\n";
?>

<p>You have signed up for text notfications. Set your text preferences.</p>