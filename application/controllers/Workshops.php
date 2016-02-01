<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Workshops extends Public_Controller {

    function __construct() {
		parent::__construct();
		$this->load->model('workshop');
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
		$this->db->select('workshops.*, locations.place, locations.lwhere');
		$this->db->join('locations', 'workshops.location_id = locations.id');
		$this->db->order_by('start', 'DESC');
		$query = $this->db->get('workshops');
		
		$workshop_rows = array();
		foreach ($query->result_array() as $row) {
			$row = $this->workshop->prep_workshop_data($row);
			$workshop_rows[] = $row;
		}
		$this->data['workshops'] = $workshop_rows;
		$this->data['admin'] = false; // not admin view
				
		$this->load->view('workshop_list', $this->data);
		
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
	
	public function edit($id) {
		$this->force_admin();
		$this->load->model(array('status', 'location'));
		$this->data['statuses'] = $this->status->statuses;
		
		$this->data['wk'] = $this->workshop->set_data($id);
		$this->data['regs'] = $this->workshop->set_registrations();
			
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('capacity', 'Capacity', 'numeric');
        $this->form_validation->set_rules('cost', 'Cost', 'numeric');		
				
	    if ($this->form_validation->run() == TRUE) {
			$this->workshop->update_cols_from_form();
			$this->workshop->update_db_from_cols();
			$this->data['wk'] = $this->workshop->set_data($id);
			$this->data['message'] = "Workshop updated!";
	    }
		
		$this->load->view('workshop_edit', $this->data);
	}
	

 
}