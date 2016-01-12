<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    function __construct() {
		parent::__construct();

		$this->load->model('user');
		$this->load->library('form_builder');
		$this->load->helper('form');

		$this->data = array();
		if ($this->session->flashdata('message')) {
			$this->data['message'] = $this->session->flashdata('message');
		}
		if ($this->session->flashdata('error')) {
			$this->data['error'] = $this->session->flashdata('error');
		}

		$this->output->set_template('workshop_user');

	}
 	
	public function index() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		if ($this->form_validation->run() == FALSE) {
			$this->data['message'] = validation_errors();
        } else {
			if ($this->user->send_link(set_value('email'))) {
				$this->session->set_flashdata('message', 'Ok! I have sent a link to your email address. Click it to log in.');  
			} else {
				$this->data['error'] = $this->user->error;
				$this->session->set_flashdata('error', $this->user->error);  
			}
			
        }
		redirect('/workshops');
	}
	
	public function login($key = null) {
		if (!$key || !$this->user->set_user_with_key($key)) {
			$this->session->set_flashdata('error', $this->user->error); 
		}
		redirect('/workshops');
	}
	
}
