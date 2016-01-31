<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admins extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_CRUD');
		$this->load->model('workshop');
		
		$this->data['js_files'] = array();
		$this->data['css_files'] = array();
		
		$this->load->view('navbar_admin');
		
		
	}

	public function index()
	{
		$this->load->model('workshop');
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
		$this->data['admin'] = true; // not admin view
				
		$this->load->view('workshop_list', $this->data);
		
	}

	public function edit($id) {
		$this->data['wk'] = $this->workshop->set_data($id);
		$this->data['regs'] = $this->workshop->set_registrations();
		$this->load->view('workshop_edit', $this->data);
		
		
	}


} 