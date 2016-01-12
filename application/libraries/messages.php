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

		$this->send_email($to, "Link for willhines.net practices", $body);
		
	}

	private function send_email($to, $subject, $body) {
		$this->CI->email->from($this->from, $this->from_name);
		$this->CI->email->to($to);
		$this->CI->email->subject($subject);
		$this->CI->email->message($body);
		$this->CI->email->send(FALSE);
		echo $this->CI->email->print_debugger();
	}
}