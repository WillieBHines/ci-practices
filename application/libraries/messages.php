<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class messages {

	public $message;
	public $error;
	private $from = "will@willhines.net"; // webmaster email 
	private $from_name = "Will Hines";
	private $late_hours;
	protected $CI;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('email');
		$this->CI->load->model('carrier');
		$this->CI->load->model('status');
		$this->CI->load->model('registration');
		
		$this->late_hours = $this->CI->config->item('late_hours');
		
	}

	public function send_activation_link($to, $key) {
		$link = base_url('/users/login/'.$key);
		$body = "You are: {$to}
Use this link to log in:
$link

Thanks!
-Will";

		return $this->send_email($to, "Link for willhines.net practices", $body);
		
	}


	public function send_change_email_link($new_email, $key, $temp_key) {
		$link = base_url('/users/invoke/'.$key.'/'.$temp_key);
		$body = "Someone (you?) has requested to change your email on Will Hines practices to {$new_email}. 

Just click this link to make that your new email. 			
$link

If you want to keep your old email, just ignore this.

Thanks!
-Will";

		return $this->send_email($new_email, "New email for willhines.net practices", $body);
		
	}


	private function send_email($to, $subject, $body) {
		$this->CI->email->from($this->from, $this->from_name);
		$this->CI->email->to($to);
		$this->CI->email->subject($subject);
		$this->CI->email->message($body);
		
		$this->CI->email->send();
		//$this->CI->email->send(FALSE);
		//echo $this->CI->email->print_debugger();
	}
	
	
	// you pass in a full workshop object and full user object
	function send_confirmation_email($wk, $user, $status_id = ENROLLED) {


		if ($user->is_id_set()) {
			$key = $user->cols['ukey'];
		} else {
			$this->error = 'User not logged in.';
			return false;
		}
		if (!$wk->is_id_set()) {
			$this->error = 'Workshop not set';
			return false;
		}
		
		$uid = $user->cols['id'];
		$wid = $wk->cols['id'];
		$key = $user->cols['ukey'];
		$reg = $this->CI->registration;
		$status = $this->CI->status;
		
		$reg->set_data_by_workshop_user($wid, $uid);
		$drop = base_url("/registrations/drop/{$wid}/{$key}"); 
		$front = base_url("/workshops/index/{$key}"); 
		$accept = base_url("/registrations/accept/{$wid}/{$key}"); 
		$decline = base_url("/registrations/decline/{$wid}/{$key}"); 
		$enroll = base_url("/registrations/enroll/{$wid}/{$key}"); 
		$textpref = base_url("/users/profile/{$key}"); 
		$call = '';
		$late = '';
		$message = '';
		
		if ($reg->cols['while_soldout']) { 
			$message .= '<br><br>'.wbh_get_dropping_late_warning();
		}
	
		$send_faq = false;
		switch ($status_id) {
			case $status->enrolled:
				$sub = "ENROLLED: {$wk->cols['showtitle']}";
				$point = "You are ENROLLED in {$wk->cols['showtitle']}.";
				$call = "To DROP, click here:\n{$drop}";
				if ($wk->cols['cost'] > 0) {
					$call .= "\n\nPay in person or venmo. On the day of the workshop is fine. Venmo link:\nhttp://venmo.com/willhines?txn=pay&share=friends&amount={$wk->cols['cost']}&note=improv%20workshop";
				}
				$send_faq = true;
				break;
			case $status->waiting:
				$sub = "WAIT LIST: {$wk->cols['showtitle']}";
				$point = "You are wait list spot {$reg->cols['rank']} for {$wk->cols['showtitle']}:";
				$call = "To DROP, click here:\n{$drop}";
				break;
			case $status->invited:
				$sub = "INVITED: {$wk->cols['showtitle']}";
				$point = "A spot opened in {$wk->cols['showtitle']}:";
				$call = "To ACCEPT, click here:\n{$accept}\n\nTo DECLINE, click here:\n{$decline}";
				break;
			case $status->dropped:
				$sub = "DROPPED: {$wk->cols['showtitle']}";
				$point = "You have dropped out of {$wk->cols['showtitle']}";
				if ($reg->cols['while_soldout'] == 1) {
					$late .= "\n".wbh_get_dropping_late_warning();
				}
				$call = "If you change your mind, re-enroll here:\n{$enroll}";
				break;
			default:
				$sub = "{$statuses[$status_id]}: {$wk->cols['showtitle']}";
				$point = "You are a status of '{$this->status->status_names[$status_id]}' for {$wk->cols['showtitle']}";
				break;
		}

		$text = '';
		if ($user->cols['send_text']) {
			$textmsg = $point.' for more info: '.$this->shorten_link($front);
			$this->send_text($user, $textmsg);
		}
	
		$notifications = '';
		if (!$user->cols['send_text']) {
			$notifications = "\nWould you want to be notified via text? You can set text preferences:\n".$textpref;
		}

		$body = "You are: {$user->cols['email']}

$point $late
$notifications

Description: {$wk->cols['notes']}
When: {$wk->cols['when']}
Where: {$wk->cols['place']} {$wk->cols['lwhere']}
Cost: {$wk->cols['cost']}

$call

".$this->email_footer($send_faq);	
	
	
		return mail($user->cols['email'], $sub, $body, "From: ".$this->from);
	}


	function dropping_late_warning() {
		return "NOTE: You are dropping within {$this->late_hours} hours of the start, and there was a waiting list. If I can't get someone to take your spot, I might ask you to pay anyway.";
	
}


	function send_text($user, $msg) {
		if (!$user->cols['send_text'] || !$user->cols['carrier_id'] || !$user->cols['phone'] || strlen($user->cols['phone']) != 10) {
			return false;
		}
		$carrier_email = $this->CI->carrier->fuller_array[$user->cols['carrier_id']]['email'];
		$to = $user->cols['phone'].'@'.$carrier_email;	
		return mail($to, '', $msg, "From: ".$this->from);
	}


	function shorten_link($link) {
	
		// bit.ly registered token is: 70cc52665d5f7df5eaeb2dcee5f1cdba14f5ec94
		// under whines@gmail.com / meet1962
	
		//tempoary while working locally
		$link = preg_replace('/localhost:8888/', 'www.willhines.net', $link);
		$link = urlencode($link);
		$response = file_get_contents("https://api-ssl.bitly.com/v3/shorten?access_token=70cc52665d5f7df5eaeb2dcee5f1cdba14f5ec94&longUrl={$link}&format=txt");
		return $response;
	
	}

	function email_footer($faq = false) {

		$faqadd = '';
		if ($faq) {
			$faqadd = strip_tags($this->get_faq());
		}
		return "
$faqadd
	
Thanks!
	
-Will Hines
HQ: 1948 Hillhurst Ave. Los Angeles, CA 90027
";
	}

	function get_faq() {
	
	return "<h2>Questions</h2>
<dl>
<dt>Can I drop out?</dt>
<dd>Yes. Go to the web site or use the link in this email.</dd>

<dt>If there is a cost, how should I pay?</dt>
<dd>In cash, at the practice. Or Venmo it to Will Hines (whines@gmail.com)
Venmo link: <a href='http://venmo.com/willhines?txn=pay&share=friends&note=improv%20workshop'>http://venmo.com/willhines?txn=pay&share=friends&note=improv%20workshop</a></dd>

<dt>What if I'm on a waiting list?</dt>
<dd>You'll get an email the moment a spot opens up, with a link to ACCEPT or DECLINE.</dd>

<dt>What's the late policy? Or the policy on leaving early?</dt>
<dd>Arriving late is fine. </dd>

<dt>What levels?</dt>
<dd>Generally anyone who's taken any improv level can sign up.</dd>
</dl>";
	}
	
	
}