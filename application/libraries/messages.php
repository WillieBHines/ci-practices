<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class messages {


	private $from = "will@willhines.net"; // webmaster email 
	private $from_name = "Will Hines";
	protected $CI;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('email');
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
		
		//$this->CI->email->send();
		$this->CI->email->send(FALSE);
		echo $this->CI->email->print_debugger();
	}
}