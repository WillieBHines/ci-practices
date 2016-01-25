<?php
	
class MY_Controller extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
}


class Public_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
		$this->load->model('user');
		$this->load->library('form_builder');
		$this->load->helper('form');

		$this->data = array();
		if ($this->session->flashdata('message')) {
			$this->data['message'] = $this->session->flashdata('message');
		}
		if ($this->session->flashdata('error')) {
			$this->data['error'] = $this->session->flashdata('error');
		}
		$this->output->set_template('workshop_user');
    }
}


class Admin_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        
		// check here for being logged in
		$this->load->model('user');

		if (!$this->user->logged_in() || !$this->user->is_admin()) {
			$this->session->set_flashdata('error', "You don't have permission to see that page!");
			redirect('/workshops'); // not logged in? back to front?
		}
    }
}





