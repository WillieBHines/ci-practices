<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Workshops extends CI_Controller {

    function __construct() {
		parent::__construct();

		/* Standard Libraries */
		$this->load->model('user');
		$this->load->library('form_builder');
		$this->load->helper('form');

		$this->output->set_template('workshop_user');

		$this->data = array();
		if ($this->session->flashdata('message')) {
			$this->data['message'] = $this->session->flashdata('message');
		}
		if ($this->session->flashdata('error')) {
			$this->data['error'] = $this->session->flashdata('error');
		}
	}
 
 
    //public function index($name, $id)
    public function index() {

		// log in box / greetings
		if ($this->user->logged_in()) {
			$this->load->view('logged_in', $this->data);	
		} else {
			$this->load->view('login', $this->data);
		}
		
		// list of workshops / mailchimp form
		$this->load->view('front', $this->data);
		
    }

    public function comments()
    {
            echo 'Look at this!';
    }

 
}