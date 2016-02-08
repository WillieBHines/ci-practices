<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Registrations extends Public_Controller {

    function __construct() {
		parent::__construct();
		$this->load->model('registration');
	}

	
	public function edit($id) {
		$this->force_admin();
		
		$this->registration->set_data($id);
		$this->data['reg'] = $this->registration->cols;
		$this->load->model('status');
		$this->data['statuses'] = $this->status->statuses;
		
							
        $this->form_validation->set_rules('status_id', 'Status', 'required');							
							
	    if ($this->form_validation->run() == TRUE) {
			$this->registration->update_cols_from_form();
			$this->registration->update_db_from_cols();
			$this->data['reg'] = $this->registration->set_data($id);
			$this->session->set_flashdata('message', "Registration updated!");
			redirect('/workshops/edit/'.$this->registration->cols['workshop_id']);
	    }
		
		$this->load->view('registration_edit', $this->data);
	}
	

 
}