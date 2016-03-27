<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tests extends Public_Controller {

    function __construct() {
		parent::__construct();
		$this->load->library('unit_test');
	}
 
 
 
 
 
	function index() {
		$this->data['output'] = "Hello!";
		$this->load->view('tests', $this->data);
	}
 
}