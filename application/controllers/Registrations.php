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

			// update the status column and status change log first
			$this->change_status($this->registration->cols['workshop_id'], $this->registration->cols['user_id'], $this->registration->cols['status_id']); 

			// now update the rest
			$this->registration->update_db_from_cols();
			
			$this->data['reg'] = $this->registration->set_data($id);
			$this->session->set_flashdata('message', "Registration updated!");
			redirect('/workshops/edit/'.$this->registration->cols['workshop_id']);
	    }
		
		$this->load->view('registration_edit', $this->data);
	}
	
	public function enroll($wid, $uid) {
		$this->load->model(array('user', 'workshop', 'status'));
		$this->workshop->set_data($wid);
		
		// figure target status: enrolled or waiting?
		if ($this->workshop->cols['type'] == 'soldout') {
			$status_id = $this->status->status_names['waiting'];
		} else {
			$status_id = $this->status->status_names['enrolled'];
		}	
		
		// set status change log first
		if ($this->change_status($wid, $uid, $status_id)) {
			if ($this->status->statuses[$status_id] == 'waiting') {
				$this->session->set_flashdata('message', "Added user '{$this->user->cols['email']}' to waiting list for workshop '{$this->workshop->cols['title']}'.");
			} else {
				$this->session->set_flashdata('message', "Enrolled user '{$this->user->cols['email']}' in workshop '{$this->workshop->cols['title']}'.");
			}
		} else {
			if ($this->status->statuses[$status_id] == 'waiting') {
				$this->session->set_flashdata('message', "User '{$this->user->cols['email']}' is already on the waiting list for workshop '{$this->workshop->cols['title']}'.");
			} else {
				$this->session->set_flashdata('message', "User '{$this->user->cols['email']}' is already enrolled for workshop '{$this->workshop->cols['title']}'.");
			}
		}
		
		redirect('/workshops/view/'.$wid); // return to the view page for that workshop
		return true;
		
	}

	// add or update a user's registration to the new status id
	public function change_status($wid, $uid, $new_status_id) {
		
		// does this person have a registration?
		$this->db->where('workshop_id', $wid);
		$this->db->where('user_id', $uid);
		$query = $this->db->get('registrations');
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				if ($row['status_id'] == $new_status_id) {
					return false; // already at that status
				}
			}
			// update registration
			$this->db->where('workshop_id', $wid);
			$this->db->where('user_id', $uid);
			$this->db->set('status_id', $new_status_id);
			$this->db->update('registrations');
		} else {
			// add registration
			$this->db->set('workshop_id', $wid);
			$this->db->set('user_id', $uid);
			$this->db->set('status_id', $new_status_id);
			$this->db->set('registered', date("Y-m-d H:i:s"));
			$this->db->set('last_modified', date("Y-m-d H:i:s"));
			$this->db->insert('registrations');
		}
		
		//log it
		$this->db->set('user_id', $uid);
		$this->db->set('workshop_id', $wid);
		$this->db->set('status_id', $new_status_id);
		$this->db->set('happened', date("Y-m-d H:i:s"));
		$this->db->insert('status_change_log');
		
		return true;
	}
 
}