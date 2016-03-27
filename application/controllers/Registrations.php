<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Registrations extends Public_Controller {


    function __construct() {
		parent::__construct();
		$this->load->model(array('user', 'workshop', 'status', 'registration'));
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

			// update the status column and status change log first
			$this->registration->change_status(
			$this->registration->cols['workshop_id'], 
			$this->registration->cols['user_id'], 
			$this->registration->cols['status_id']); 

			// now update the rest
			$this->registration->update_db_from_cols();
			
			$this->data['reg'] = $this->registration->set_data($id);
			$this->session->set_flashdata('message', "Registration updated!");
			redirect('/workshops/edit/'.$this->registration->cols['workshop_id']);
	    }
		
		$this->load->view('registration_edit', $this->data);
	}
	
	public function enroll($wid, $key) {
		
		$this->figure_workshop($wid);
		$status_id = $this->status->status_names['enrolled'];
		
		// change enroll to waiting if workshop is sold out
		if ($this->workshop->cols['type'] == 'soldout') {
			$status_id = $this->status->status_names['waiting'];
		}
		
		$this->enact_status_change($wid, $key, $status_id);
		
	}
	
	public function accept($wid, $key) {
		$status_id = $this->status->status_names['enrolled'];
		$this->enact_status_change($wid, $key, $status_id);
	}
	public function decline($wid, $key) {
		$status_id = $this->status->status_names['dropped'];
		$this->enact_status_change($wid, $key, $status_id);
	}
	public function drop($wid, $key) {
		$this->figure_workshop($wid);
		$this->session->set_flashdata('message', "Do you wish to really drop out of workshop '{$this->workshop->cols['title']}' <a class='btn btn-danger' href='".base_url("/registrations/condrop/{$wid}/{$key}")."'>Yes, drop out.</a>");
		redirect("/workshops/view/{$wid}");
		
	}
	public function condrop($wid, $key) {
		$status_id = $this->status->status_names['dropped'];
		$this->enact_status_change($wid, $key, $status_id);
	}
	

	private function enact_status_change($wid, $key, $status_id) {
		
		$this->figure_user($key);
		$this->figure_workshop($wid);	
		
		if ($this->registration->change_status(
			$this->workshop->cols['id'], 
			$this->user->cols['id'], 
			$status_id)) {
				
			$this->session->set_flashdata('message', $this->registration->message);
		} else {
			$this->session->set_flashdata('error', $this->registration->error);
		}
		
		redirect('/workshops/view/'.$wid); // return to the view page for that workshop
		return true;
	}


	public function figure_user($key) {
		
		// check key
		if (!$key || ($key && !$this->user->set_user_with_key($key))) {
			// no key or we couldn't set user with the key
			$this->session->set_flashdata('error', "Can't log in the user.");
			redirect('/workshops/'); 
			return false; 
		}
		return $this->user; // we have set the correct user in that if statement
		
	}

	public function figure_workshop($wid) {
		if (!$wid) {
			$this->session->set_flashdata('error', "No workshop set.");
			redirect('/workshops/'); 
			return false;
		}
		
		if (isset($this->workshop->cols['id']) && $this->workshop->cols['id'] == $wid) {
			return $this->workshop; // already set
		}
		
		if (!$this->workshop->set_data($wid)) {
			$this->session->set_flashdata('error', $this->workshop->error);
			redirect('/workshops/'); 
			return false; // workshop empty
		}
		
		return $this->workshop;
		
	}

}