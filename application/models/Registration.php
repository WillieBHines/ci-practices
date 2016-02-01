<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Registration extends MY_Model {

		public $table_name = 'registrations';	
        
		public function __construct()
        {
                parent::__construct();
								
        }
		

		public function count_enrollments($wid, $status_id = 1) {
			$this->db->where('workshop_id', $wid);
			$this->db->where('status_id', $status_id);
			$query = $this->db->get('registrations');
			return $query->num_rows();
		}
		
		// what spot on the waiting list are you
		public function figure_rank($workshop_id, $user_id) {
			$this->load->model('status');
			$this->db->order_by('last_modified');
			$this->db->where('workshop_id', $workshop_id);
			$this->db->where('status_id', $this->status->status_names['waiting']);
			$query = $this->db->get('registrations');
			
			$i = 1;
			foreach ($query->result_array() as $row) {
				if ($row['user_id'] == $user_id) {
					break;
				}
				$i++;
			}
			return $i;
		}
}
	