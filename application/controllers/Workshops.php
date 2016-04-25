<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Workshops extends Public_Controller {

    function __construct() {
		parent::__construct();
		$this->load->model('workshop');
	}
 
 
    //public function index($name, $id)
    public function index($key = null) {

		if ($key) {
			if (!$this->user->set_user_with_key($key)) {
				$this->data['error'] = $this->user->error;
			}
		}

		// log in box / greetings
		$this->data['your workshops'] = array();
		if ($this->user->logged_in()) {
			$this->load->view('front_logged_in', $this->data);	
		} else {
			$this->load->view('front_login', $this->data);
		}
		
		$this->load->view('front_mailchimp');
		
		// list of workshops / mailchimp form
		$this->db->select('workshops.*, locations.place, locations.lwhere');
		$this->db->join('locations', 'workshops.location_id = locations.id');
		$this->db->order_by('start', 'DESC');
		$query = $this->db->get('workshops');
		
		$workshop_rows = array();
		foreach ($query->result_array() as $row) {
			$row = $this->workshop->prep_workshop_data($row, $this->user);
			$workshop_rows[] = $row;
		}
		$this->data['workshops'] = $workshop_rows;
		$this->data['admin'] = false; // not admin view
						
		$this->load->view('workshop_list', $this->data);
		
		// past workshops 
		if ($this->user->logged_in()) {
			$this->load->view('front_past_workshops', $this->data);	
		}		
    }
	
	public function view($id) {
		$this->data['row'] = $this->workshop->set_data($id, $this->user);
		$this->load->view('workshop_view', $this->data);
	}
	
	public function admin()
	{
		$this->force_admin();

		// get data
		$this->db->select('workshops.*, locations.place, locations.lwhere');
		$this->db->join('locations', 'workshops.location_id = locations.id');
		$this->db->order_by('start', 'DESC');
		$query = $this->db->get('workshops');
		
		// set it up for the view
		$workshop_rows = array();
		foreach ($query->result_array() as $row) {
			$row = $this->workshop->prep_workshop_data($row);
			$workshop_rows[] = $row;
		}
		$this->data['workshops'] = $workshop_rows;
		$this->data['admin'] = true; // not admin view
				
		$this->load->view('workshop_list', $this->data);
		
	}
	
	public function add($wid = null) {
		$this->force_admin();
		
		$this->data['wk'] = array();
		if ($wid) {
			$this->workshop->set_cols_with_id($wid);
			$this->data['wk'] = $this->workshop->cols;
		}
		
		$this->load->model(array('status', 'location'));
		$this->data['statuses'] = $this->status->statuses;
					
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('capacity', 'Capacity', 'numeric');
        $this->form_validation->set_rules('cost', 'Cost', 'numeric');		
				
	    if ($this->form_validation->run() == TRUE) {
			$this->workshop->update_cols_from_form();
			$this->workshop->insert_db_from_cols();
			//$this->data['wk'] = $this->workshop->set_data($this->db->insert_id());
			$this->session->set_flashdata('message', "Workshop '{$this->workshop->cols['title']}' added!");
			redirect('/workshops/admin');
	    }
		
		$this->load->view('workshop_add', $this->data);	
	}
	
	public function edit($id) {
		$this->force_admin();
		$this->load->model(array('location', 'registration'));
		$this->data['statuses'] = $this->status->statuses;
		
		$this->data['wk'] = $this->workshop->set_data($id);
		$this->data['regs'] = $this->workshop->set_registrations();
		$this->data['changes'] = $this->workshop->get_changes();
		
		
		// was the edit workshop form submitted?
		if ($this->input->post('submit1')) {
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			if ($this->form_validation->run() == TRUE) {
				$email = $this->input->post('email');
				$status_id = $this->input->post('status_id');
			
				// remember current user (admin)
				// then set new user and enroll
				$admin_key = $this->user->cols['ukey'];
				$this->user->set_user_with_email($email);
			
				$this->registration->change_status(
					$this->workshop->cols['id'], 
					$this->user->cols['id'],
					$status_id,
					$this->input->post['send_email']);
				
					$this->data['message'] = $this->registration->message;
			
					// refresh data
					$this->data['wk'] = $this->workshop->set_data($id);
					$this->data['regs'] = $this->workshop->set_registrations();
					$this->data['changes'] = $this->workshop->get_changes();
			
				// put admin back as the user
				$this->user->set_user_with_key($admin_key);				
			}
		
		}

		// was the edit workshop form submitted?
		if ($this->input->post('submit2')) {
	        $this->form_validation->set_rules('title', 'Title', 'required');
	        $this->form_validation->set_rules('capacity', 'Capacity', 'numeric');
	        $this->form_validation->set_rules('cost', 'Cost', 'numeric');		
				
		    if ($this->form_validation->run() == TRUE) {
				$this->workshop->update_cols_from_form();
				$this->workshop->update_db_from_cols();
				$this->data['wk'] = $this->workshop->set_data($id); // refresh data
				$this->data['message'] = "Workshop updated!";
		    }
		}
			
		$this->data['late_hours'] = $this->config->item('late_hours');
		$this->load->view('workshop_edit', $this->data);
	}
	
	public function delete($wid) {
		$this->force_admin();
		if ($this->workshop->set_data($wid)) {
			$this->session->set_flashdata('message', "Do you wish to really delete workshop '{$this->workshop->cols['title']}' <a class='btn btn-danger' href='".base_url("/workshops/condelete/{$wid}")."'>Yes delete</a>");
			
		} else {
			$this->session->set_flashdata('error', "Tried to delete workshop number '$wid' but I could not find that workshop.");
		}
		redirect("/workshops/edit/{$wid}");
	}
	
	public function condelete($wid) {
		if ($this->workshop->set_data($wid)) {
			$this->session->set_flashdata('error', "Workshop '{$this->workshop->cols['title']}' deleted!");
			
			$this->db->where('workshop_id', $wid);
			$this->db->delete('registrations');
			$this->db->where('workshop_id', $wid);
			$this->db->delete('status_change_log');
			$this->db->where('id', $wid);
			$this->db->delete('workshops');
			
		} else {
			$this->session->set_flashdata('error', "Tried to delete workshop number '$wid' but I could not find that workshop.");
		}
		redirect('/workshops/admin');
		
	}

	public function changes() {
		$this->force_admin();
		$this->load->model('registration');
		$this->data['changes'] = $this->registration->get_changes();
		$this->data['late_hours'] = $this->config->item('late_hours');
		$this->load->view('workshop_changes', $this->data);	
	}

 
}