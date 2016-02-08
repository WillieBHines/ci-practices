<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Registration extends MY_Model {

		public $table_name = 'registrations';	
        
		public function __construct()
        {
                parent::__construct();
				$this->load->model('workshop');
								
        }
		
		public function set_data($id) {
			$this->db->where('registrations.id', $id);
			$this->db->join('statuses', 'registrations.status_id = statuses.id');
			$this->db->join('users', 'registrations.user_id = users.id');
			$this->db->join('workshops', 'registrations.workshop_id = workshops.id');
			$this->db->select('registrations.*, workshops.title, users.email, statuses.status_name');
			$query = $this->db->get('registrations');
			foreach ($query->result_array() as $row) {
				$this->cols = $row;
			}
			return $this->cols;
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
	