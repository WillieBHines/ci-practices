<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends MY_Model {
	
        public function __construct()
        {
                parent::__construct();
				$this->table_name = 'locations';
				$this->nameCol = 'place';
				$this->set_dropdown_arrays();					
        }
}

?>