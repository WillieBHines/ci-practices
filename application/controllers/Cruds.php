<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cruds extends Admin_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->output->set_template('admin_crud');
		$this->load->library('grocery_CRUD');

		$this->crud = new grocery_CRUD();
		$this->crud->set_theme('bootstrap');
	
	}

	public function index()
	{
		redirect('/cruds/workshops');
	}


	public function workshops()
	{
			$this->crud->set_table('workshops');
			$this->crud->set_relation('location_id','locations','place');
			$this->crud->display_as('location_id','Location');
			$output = $this->crud->render();
			
			$this->load->view('crud', $output);
	}

	public function users()
	{
			$this->crud->set_table('users');
			$this->crud->set_relation('group_id','groups','name');
			$output = $this->crud->render();

			$this->load->view('crud', $output);
	}

	public function registrations()
	{
		$this->crud->set_table('registrations');
		$this->crud->set_relation('workshop_id','workshops','title');
		$this->crud->display_as('workshop_id','Workshop');
		$this->crud->set_relation('user_id','users','email');
		$this->crud->display_as('user_id','User');
		$this->crud->set_relation('status_id','statuses','status_name');
		$this->crud->display_as('status_id','Status');
		$output = $this->crud->render();

		$this->load->view('crud', $output);
	}

	public function groups()
	{
		$this->crud->set_table('groups');
		$output = $this->crud->render();

		$this->load->view('crud', $output);
	}


	public function locations()
	{
		$this->crud->set_table('locations');
		$output = $this->crud->render();

		$this->load->view('crud', $output);
	}

	public function statuses()
	{
		$this->crud->set_table('statuses');
		$output = $this->crud->render();

		$this->load->view('crud', $output);
	}

	public function carriers()
	{
		$this->crud->set_table('carriers');
		$output = $this->crud->render();

		$this->load->view('crud', $output);
	}


} 