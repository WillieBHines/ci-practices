<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Status extends CI_Model {

		public $statuses;		
		public $status_names;
		public $enrolled;
		public $dropped;
		public $invited;
		public $waiting;

        public function __construct()
        {
                parent::__construct();
				$this->set_statuses();	
				
				$this->enrolled = $this->status_names['enrolled'];
				$this->dropped = $this->status_names['dropped'];
				$this->invited = $this->status_names['invited'];
				$this->waiting = $this->status_names['waiting'];
				
				
        }
		
		private function set_statuses() {

			$this->db->order_by('id', 'ASC');
			$query = $this->db->get('statuses');
			foreach ($query->result_array() as $row) {
				$this->statuses[$row['id']] = $row['status_name']; 
				$this->status_names[$row['status_name']] = $row['id']; 
			}
		}
		
		

}

?>