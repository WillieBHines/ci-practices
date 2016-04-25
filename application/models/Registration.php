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


		public function set_data_by_workshop_user($wid, $uid) {
			$this->db->where('registrations.workshop_id', $wid);
			$this->db->where('registrations.user_id', $uid);
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
		
		
		// add or update a user's registration to the new status id
		public function change_status($wid, $uid, $new_status_id, $send_email = true) {
			
			$this->load->model('status');
			$status_name = $this->status->statuses[$new_status_id];
		
			if (!$wid) {
				$this->error = 'No workshop set';
				return false;
			}

			if (!$uid) {
				$this->error = 'No user set';
				return false;
			}
			
			// does this person have a registration?
			$this->db->where('workshop_id', $wid);
			$this->db->where('user_id', $uid);
			$query = $this->db->get('registrations');
			if ($query->num_rows() > 0) {
				foreach ($query->result_array() as $row) {
					if ($row['status_id'] == $new_status_id) {
						$this->error = "You are already '{$status_name}' for this workshop.";
						return false; // already at that status
					}
				}
				// update registration
				$this->db->where('workshop_id', $wid);
				$this->db->where('user_id', $uid);
				$this->db->set('status_id', $new_status_id);
				$this->db->update('registrations');
			} else {
				// add registration
				$this->db->set('workshop_id', $wid);
				$this->db->set('user_id', $uid);
				$this->db->set('status_id', $new_status_id);
				$this->db->set('registered', date("Y-m-d H:i:s"));
				$this->db->set('last_modified', date("Y-m-d H:i:s"));
				$this->db->insert('registrations');
			}
		
			$this->update_status_log($wid, $uid, $new_status_id);
			
			if ($new_status_id == $this->status->waiting) {
				$rank = $this->figure_rank($wid, $uid);
				$this->message = "'{$this->user->cols['email']}' has been set to '{$status_name}' (spot: {$rank}) for this workshop.";
			} else {
				$this->message = "'{$this->user->cols['email']}' has been set to '{$status_name}' for this workshop.";
			}
			
			// check and send email if needed
			if ($send_email) {
				$this->load->library('messages');
				$this->messages->send_confirmation_email($this->workshop, $this->user, $new_status_id);
			}
						
			return true;
		}

		public function update_status_log($wid, $uid, $status_id) {
			$this->db->set('user_id', $uid);
			$this->db->set('workshop_id', $wid);
			$this->db->set('status_id', $status_id);
			$this->db->set('happened', date("Y-m-d H:i:s"));
			$this->db->insert('status_change_log');
			return true;
		}
		
		
		public function get_changes($wid = null, $cleave = true) {
			
			$this->changes = array(); // clear it out first
						
			$this->db->select('users.email, workshops.title, workshops.start, statuses.status_name, status_change_log.happened, status_change_log.workshop_id, status_change_log.user_id, status_change_log.status_id');
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
				
				if ($row['status_id'] == $this->status->dropped) {
					// when was last enrollment
					$row['last_enrolled'] = $this->get_last_enrolled($row['workshop_id'], $row['user_id'], $row['happened']);
				
					// how much in advance was this change
					$row['hours_before'] = round((strtotime($row['start']) - strtotime($row['happened'])) / 3600);
				}
				
				$this->changes[] = $row;
			}
			return $this->changes;
		}
		
		private function get_last_enrolled($wid = 0, $uid = 0, $before = null) {
			
			$this->db->select('status_change_log.*');
			$this->db->where('workshop_id', $wid);
			$this->db->where('user_id', $uid);
			$this->db->where('status_id', $this->status->enrolled);
			if ($before) {
				$this->db->where('happened < ', date( 'Y-m-d H:i:s', strtotime($before) ));
			}
			$this->db->order_by('happened desc');
			$query = $this->db->get('status_change_log');
			foreach ($query->result_array() as $row) {
				return $row['happened'];
			}
			return false;
		}
}
	