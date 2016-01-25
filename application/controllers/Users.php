<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Public_Controller {

    function __construct() {
		parent::__construct();
	}
 	
	public function index() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		if ($this->form_validation->run() == FALSE) {
			$this->data['error'] = validation_errors();
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
	
	
	public function profile() {
		$this->check_logged_in();
		$this->load->view('profile');
	}

	public function text_preferences() {
		$this->check_logged_in();
		$this->load->model('carrier'); // for the form on the profile page
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('phone', 'Phone Number', 'min_length[10]|numeric');
		$this->form_validation->set_rules('carrier_id', 'Carrier', 'greater_than[0]', array('greater_than' => "You must pick a carrier."));
		if ($this->form_validation->run() == FALSE) {
			$this->data['error'] = validation_errors();
        } else {
			if ($this->user->update_text_preferences(
				$this->input->post('send_text'), 
				$this->input->post('carrier_id'),
				$this->input->post('phone')
													)) {
				$this->data['message'] = "Text preferences updated. Return to the <a href='".base_url('/workshops')."'>main page</a>.";
			} else {
				$this->data['error'] = $this->user->error;
			}
		
		}
		$this->load->view('text_preferences', $this->data);
	}
	
	public function reset() {
		$this->check_logged_in();
		$this->user->send_link($this->user->cols['email'], true);
	}
	
	public function logout() {
		$this->user->logout();
		redirect('/workshops');
	}
	
	public function change_email() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('new_email', 'New email', 'trim|required|valid_email');
		if ($this->form_validation->run() == FALSE) {
			$this->data['error'] = validation_errors();
        } else {		
			if ($this->user->propose_new_email($this->input->post('new_email'))) {
				$this->data['message'] = "A link was sent to the new email '$new_email.' Click on it to activate your new email address.";
			} else {
				$this->data['error'] = $this->user->error;
			}
		}
		print_r($this->data);
		$this->load->view('profile', $this->data);
	}

	public function invoke($key, $temp_key) {
		if ($this->user->invoke_new_email($key, $temp_key)) {
			$this->data['message'] = $this->user->message;
			$this->load->view('profile', $this->data);
		} else {
			$this->data['error'] = $this->user->error;
			redirect('/workshops');
		}
	}

	private function check_logged_in() {
		if (!$this->user->logged_in()) {
			$this->session->set_flashdata('You need to be logged in to see your profile.');
			redirect('/workshops');
			return false;
		}
		return true;
	}
	
}
