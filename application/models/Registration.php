<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Registration extends MY_Model {

		public $table_name = 'registrations';
		public $changes;	
        
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
		
		public function get_changes($wid = null, $cleave = true) {
			$this->db->select('users.email, workshops.title, workshops.start, statuses.status_name, status_change_log.happened');
			if ($wid) {
				$this->db->where('workshop_id', $wid);
			}
			$this->db->join('workshops', 'workshops.id = status_change_log.workshop_id');
			$this->db->join('users', 'users.id = status_change_log.user_id');
			$this->db->join('statuses', 'statuses.id = status_change_log.status_id');
			
			$this->db->order_by('happened', 'DESC');
			$query = $this->db->get('status_change_log');
			foreach ($query->result_array() as $row) {
				if ($cleave) {
					if (strtotime($row['start']) < strtotime("now")) {
						continue; // skip past ones if cleave is true
					}
				}
				$this->changes[] = $row;
			}
			return $this->changes;
		}
		
		
}
	