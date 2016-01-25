<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admins extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_CRUD');
	}

	public function _example_output($output = null)
	{
		$this->load->view('admin_screens.php',$output);
	}


	public function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}


	public function workshops()
	{
			$crud = new grocery_CRUD();
			$crud->set_theme('bootstrap');
			$crud->set_table('workshops');
			$crud->set_relation('location_id','locations','place');
			$crud->display_as('location_id','Location');
			$output = $crud->render();

			$this->_example_output($output);
	}

	public function users()
	{
			$crud = new grocery_CRUD();
			$crud->set_theme('bootstrap');
			
			$crud->set_table('users');
			$crud->set_relation('group_id','groups','name');
			$output = $crud->render();
			$this->_example_output($output);
	}

	public function registrations()
	{
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->set_table('registrations');
		$crud->set_relation('workshop_id','workshops','title');
		$crud->display_as('workshop_id','Workshop');
		$crud->set_relation('user_id','users','email');
		$crud->display_as('user_id','User');
		$crud->set_relation('status_id','statuses','status_name');
		$crud->display_as('status_id','Status');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function groups()
	{
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->set_table('groups');
		$output = $crud->render();
		$this->_example_output($output);
	}


	public function locations()
	{
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->set_table('locations');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function statuses()
	{
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->set_table('statuses');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function carriers()
	{
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->set_table('carriers');
		$output = $crud->render();
		$this->_example_output($output);
	}


} 