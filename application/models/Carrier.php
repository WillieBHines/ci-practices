<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// a bit of overkill,
// but this model exists just to get the contents of the carriers
// table into a public array
// in two forms
// a array for drop downs
// and an array of array that has all the data
class Carrier extends CI_Model {
	

		public $carriers;		// id, network, email
		public $carriers_drop;	// for forms 

        public function __construct()
        {
                parent::__construct();
				$this->set_carriers_info();	
				
        }
		
		private function set_carriers_info() {
			$this->carriers[0] = array();
			$this->carriers_drop[0] = '';

			$this->db->order_by('id', 'ASC');
			$query = $this->db->get('carriers');
			foreach ($query->result_array() as $row) {
				$this->carriers[$row['id']] = $row; // $row has id, network and email
				$this->carriers_drop[$row['id']] = $row['network']; // just network name
			}
		}
		
		

}

?>