<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct()
       {
           parent::__construct();

           /* Standard Libraries */
           $this->load->database();
           $this->load->helper('url');
		   $this->_init();
       }
 
 
   	private function _init()
   	{
   		$this->output->set_template('workshop_user');
   	}
	
	public function index()
	{
		$this->load->view('front');
		
	}
}
