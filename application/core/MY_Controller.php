<?php
	
class MY_Controller extends CI_Controller {
	
	public $message;
	public $error;
	
	function __construct() {
		parent::__construct();
		
		date_default_timezone_set ( 'America/Los_Angeles' );
		
		// essential models, libraries, helpers
		$this->load->model('user');
		$this->load->library(array('form_builder', 'form_validation'));
		$this->load->helper('form');

		// for the admin template
		$this->data['js_files'] = array();
		$this->data['css_files'] = array();

		// error, info messages
		$this->data = array();
		if ($this->session->flashdata('message')) {
			$this->data['message'] = $this->session->flashdata('message');
		}
		if ($this->session->flashdata('error')) {
			$this->data['error'] = $this->session->flashdata('error');
		}
		
	}
	
	
	function force_admin() {
		if (!$this->user->logged_in() || !$this->user->is_admin()) {
			$this->session->set_flashdata('error', "You don't have permission to see that page!");
			redirect('/workshops'); // not logged in? back to front
		} else {
			$this->output->set_template('admin');
			$this->load->view('navbar_admin');
		}
	}
	
}


class Public_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		$this->output->set_template('user');
    }
}


class Admin_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		$this->force_admin();
    }
}





