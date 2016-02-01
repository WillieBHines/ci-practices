<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends MY_Model {
	
        public function __construct()
        {
                parent::__construct();
				$this->table_name = 'groups';
				$this->set_dropdown_arrays();					
        }
}

?>