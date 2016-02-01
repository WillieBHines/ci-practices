<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// a bit of overkill,
// but this model exists just to get the contents of the carriers
// table into a public array
// in two forms
// a array for drop downs
// and an array of array that has all the data
class Carrier extends MY_Model {
	
        public function __construct()
        {
                parent::__construct();
				$this->table_name = 'carriers';
				$this->nameCol = 'network';
				$this->set_dropdown_arrays();	
				
        }
}

?>